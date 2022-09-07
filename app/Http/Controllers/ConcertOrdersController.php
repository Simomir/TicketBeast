<?php

namespace App\Http\Controllers;

use App\Billing\PaymentGateway;
use App\Models\Concert;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ConcertOrdersController extends Controller
{
    private $paymentGateway;

    public function __construct(PaymentGateway $paymentGateway) {
        $this->paymentGateway = $paymentGateway;
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request, $id) {

        $this->validate($request, [
            'email' => 'required',
        ]);

        $concert = Concert::find($id);
        $order = $concert->orders()->create(['email' => $request->input('email')]);

        foreach (range(1, $request->input('ticket_quantity')) as $i) {
            $order->tickets()->create([]);
        }

        $this->paymentGateway->charge($request->input('ticket_quantity') * $concert->ticket_price, $request->input('payment_token'));

        return response()->json([], 201);
    }
}
