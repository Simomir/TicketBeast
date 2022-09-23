<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function forTickets($tickets, $email)
    {
        $order = self::create([
            'email' => $email,
            'amount' => $tickets->sum('price'),
        ]);

        foreach ($tickets as $ticket) {
            $order->tickets()->save($ticket);
        }

        return $order;
    }

    public function concert()
    {
        return $this->belongsTo(Concert::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function cancel(): void
    {
        foreach ($this->tickets as $ticket)
        {
            $ticket->release();
        }

        $this->delete();
    }

    public function ticketQuantity(): int
    {
        return $this->tickets()->count();
    }

    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'ticket_quantity' => $this->ticketQuantity(),
            'amount' => $this->amount,
        ];
    }
}
