<?php

namespace App\Http\Controllers;

use App\Billing\PaymentGateway;
use Illuminate\Http\Request;

class ConcertOrdersController extends Controller
{
    private $paymentGateway;

    public function __construct(PaymentGateway $paymentGateway) {
        this->paymentGateway = $paymentGateway;
    }

    public function store() {
        $this->paymentGateway->charge($amount, $token);
        return response()->json([], 201);
    }
}
