<?php

namespace App\Http\Controllers;

use App\Billing\PaymentGateway;
use App\Models\Concert;
use Illuminate\Http\Request;

class ConcertOrdersController extends Controller
{
    private $paymentGateway;

    public function __construct(PaymentGateway $paymentGateway) {
        $this->paymentGateway = $paymentGateway;
    }

    public function store(Request $request, $id) {
        $concert = Concert::find($id);
        $ticketQuantity = $request->input('ticket_quantity');
        $amount = $ticketQuantity * $concert->ticket_price;
        $token = $request->input('payment_token');
        $this->paymentGateway->charge($amount, $token);

        $order = $concert->orders()->create(['email' => $request->input('email')]);

        foreach (range(1, $ticketQuantity) as $_) {
            $order->tickets()->create([]);
        }

        return response()->json([], 201);
    }
}