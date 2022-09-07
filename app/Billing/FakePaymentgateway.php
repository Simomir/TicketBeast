<?php
namespace App\Billing;

use Illuminate\Support\Collection;

class FakePaymentgateway implements PaymentGateway {

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
        if ($token !== $this->getValidTestToken()) {
            throw new PaymentFailedException;
        }

        $this->charges[] = $amount;
    }

    public function totalCharges() {
        return $this->charges->sum();
    }
}
