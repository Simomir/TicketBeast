<?php

namespace Tests\Feature;

use App\Billing\FakePaymentgateway;
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
    function charges_with_invalid_payment_token_fail() {

        try {
            $paymentGateway = new FakePaymentgateway;

            $paymentGateway->charge(2500, 'invalid-payment-token');
        } catch (PaymentFailedException $exception) {
            return;
        }

        $this->fail();

    }
}
