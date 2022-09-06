<?php
namespace App\Billing;

class FakePaymentgateway {
    public function getValidTestToken(): string
    {
        return 'valid-token';
    }

    public function charge() {

    }

    public function totalCharges() {

    }
}
