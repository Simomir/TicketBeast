<?php

namespace App\Http\Controllers;

use App\Billing\PaymentFailedException;
use App\Billing\PaymentGateway;
use App\Exceptions\NotEnoughTicketsException;
use App\Models\Concert;
use App\Models\Order;
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
        $concert = Concert::published()->findOrFail($id);

        $this->validate($request, [
            'email' => ['required', 'email'],
            'ticket_quantity' => ['required', 'integer', 'min:1'],
            'payment_token' => ['required'],
        ]);

        try {
            $tickets = $concert->findTickets($request->get('ticket_quantity'));
            $this->paymentGateway->charge($request->input('ticket_quantity') * $concert->ticket_price, $request->input('payment_token'));
            $order = $concert->createOrder($request->get('email'), $tickets);

//            $order = Order::forTickets($tickets, $request->get('email'));
            return response()->json($order, 201);
        } catch (PaymentFailedException $e) {
            return response()->json([], 422);
        } catch (NotEnoughTicketsException $e) {
            return response()->json([], 422);
        }
    }
}
