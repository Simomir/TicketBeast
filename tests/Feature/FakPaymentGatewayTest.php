<?php

namespace Tests\Feature;

use App\Billing\FakePaymentgateway;
use App\Billing\PaymentFailedException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FakPaymentGatewayTest extends TestCase
{
    /** @test */
    function charges_with_a_valid_payment_token_are_successful() {
        $paymentGateway = new FakePaymentgateway;

        $paymentGateway->charge(2500, $paymentGateway->getValidTestToken());

        $this->assertEquals(2500, $paymentGateway->totalCharges());
    }

    /** @test */
    function charges_with_an_invalid_payment_token_fail() {

        try {
            $paymentGateway = new FakePaymentgateway;

            $paymentGateway->charge(2500, 'dkgasdasdjlas');
        } catch (PaymentFailedException $e) {
            $this->assertEquals('', $e->getMessage());
            return;
        }

        $this->fail();
    }
}
