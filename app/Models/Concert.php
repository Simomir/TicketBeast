<?php

namespace App\Models;

use App\Exceptions\NotEnoughTicketsException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Concert extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $dates = ['date'];

    protected $appends = ['formatted_date', 'formatted_start_time', 'ticket_price_in_dollars'];

    public function scopePublished($query) {
        return $query->whereNotNull('published_at');
    }

    public function getFormattedDateAttribute() {
        return $this->date->format('F j, Y');
    }

    public function getFormattedStartTimeAttribute() {
        return $this->date->format('g:ia');
    }

    public function getTicketPriceInDollarsAttribute(): string
    {
        return number_format($this->ticket_price / 100, 2);
    }

    public function orders() {
        return $this->belongsToMany(Order::class, 'tickets');
    }

    public function tickets() {
        return $this->hasMany(Ticket::class);
    }

    public function findTickets(int $quantity)
    {
        $tickets = $this->tickets()->available()->take($quantity)->get();

        if ($tickets->count() < $quantity) {
            throw new NotEnoughTicketsException;
        }

        return $tickets;
    }

    public function createOrder(string $email, $tickets)
    {
        return Order::forTickets($tickets, $email);
    }

    public function orderTickets(string $email, $ticketQuantity): Order
    {
        $tickets = $this->findTickets($ticketQuantity);

        return $this->createOrder($email, $tickets);
    }

    public function addTickets(int $quantity): Concert
    {
        foreach (range(1, $quantity) as $i) {
            $this->tickets()->create([]);
        }

        return $this;
    }

    public function ticketsRemaining(): int
    {
        return $this->tickets()->available()->count();
    }

    public function hasOrderFor($customerEmail): bool
    {
       return $this->orders()->where('email', $customerEmail)->count() > 0;
    }

    public function ordersFor(string $customerEmail)
    {
        return $this->orders()->where('email', $customerEmail)->get();
    }
}
