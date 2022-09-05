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

    public function getFormattedDateAttribute() {
        return $this->date->format('F j, Y');
    }

    public function getFormattedStartTimeAttribute() {
        return $this->date->format('g:ia');
    }

    public function getTicketPriceInDollarsAttribute(): string {
        return number_format($this->ticket_price / 100, 2);
    }
}
