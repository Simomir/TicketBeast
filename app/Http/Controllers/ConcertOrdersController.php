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
        $this->paymentGateway->charge($request->input('ticket_quantity') * $concert->ticket_price, $request->input('payment_token'));

        return response()->json([], 201);
    }
}
