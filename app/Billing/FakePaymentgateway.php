<?php
namespace App\Billing;

class FakePaymentgateway {

    public function __construct() {
        $this->charges = collect();
    }

    public function getValidTestToken(): string
    {
        return 'valid-token';
    }

    public function charge() {

    }

    public function totalCharges() {
        return $this->charges->sum();
    }
}
