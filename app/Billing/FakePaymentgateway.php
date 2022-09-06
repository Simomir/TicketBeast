<?php
namespace App\Billing;

class FakePaymentgateway {
    public function getValidTestToken(): string
    {
        return 'valid-token';
    }
}
