<?php

namespace Tests\Feature;

use App\Models\Concert;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PurchaseTicketsTest extends TestCase
{
    /** @test */
    function customer_can_purchase_concert_tickets() {
        $concert = Concert::factory()->create(['ticket_price' => 3250]);

        $this->json('POST', "/concerts/{$concert->id}/orders", [
            'email' => 'john@example.com',
            'ticket_quantity' => 3,
            'payment_token' => $paymentGateway->getValidTestToken(),
        ]);

        $this->assertEquals(9750, $paymentGateway->totalCharges());

        $order = $concert->orders()->where('email', 'john@example.com')->first();
        $this->assertNotNull($order);
        $this->assertEquals(3, $order->tickets->count());
    }
}
