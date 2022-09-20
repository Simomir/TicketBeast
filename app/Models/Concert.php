<?php

namespace App\Models;

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

    public function getTicketPriceInDollarsAttribute(): string {
        return number_format($this->ticket_price / 100, 2);
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }

    public function tickets() {
        return $this->hasMany(Ticket::class);
    }

    public function orderTickets($email, $ticketQuantity): Order {
        $order = $this->orders()->create(['email' => $email]);

        foreach (range(1, $ticketQuantity) as $_) {
            $order->tickets()->create([]);
        }

        return $order;
    }

    public function addTickets($quantity) {

    }

    public function ticketsRemaining() {
        return $this->tickets()->count();
    }
}
