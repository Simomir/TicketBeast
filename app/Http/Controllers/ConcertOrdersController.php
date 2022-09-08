<?php

namespace App\Http\Controllers;

use App\Billing\PaymentFailedException;
use App\Billing\PaymentGateway;
use App\Models\Concert;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ConcertOrdersController extends Controller
{
    private PaymentGateway $paymentGateway;

    public function __construct(PaymentGateway $paymentGateway) {
        $this->paymentGateway = $paymentGateway;
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request, $id): JsonResponse
    {

        $this->validate($request, [
            'email' => ['required', 'email'],
            'ticket_quantity' => ['required', 'integer', 'min:1'],
            'payment_token' => ['required'],
        ]);

        try {
            $concert = Concert::find($id);
            $this->paymentGateway->charge($request->input('ticket_quantity') * $concert->ticket_price, $request->input('payment_token'));
            $order = $concert->orderTickets($request->input('email'), $request->input('ticket_quantity'));

            return response()->json([], 201);
        } catch (PaymentFailedException $e) {
            return response()->json([], 422);

        }
    }
}
