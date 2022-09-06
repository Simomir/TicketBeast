<?php
namespace App\Billing;

use Illuminate\Support\Collection;

class FakePaymentgateway {

    /**
     * @var Collection
     */
    private $charges;

    public function __construct() {
        $this->charges = collect();
    }

    public function getValidTestToken(): string
    {
        return 'valid-token';
    }

    public function charge($amount, $token) {
        $this->charges[] = $amount;
    }

    public function totalCharges() {
        return $this->charges->sum();
    }
}
