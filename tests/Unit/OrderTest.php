<?php

namespace Tests\Unit;

use App\Models\Concert;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class OrderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function tickets_are_released_when_order_is_cancelled()
    {
        $concert = Concert::factory()->create()->addTickets(10);
        $order = $concert->orderTickets('jane@example.com', 5);

        // pre-condition
        $this->assertEquals(5, $concert->ticketsRemaining());

        $order->cancel();

        $this->assertEquals(10, $concert->ticketsRemaining());
        $this->assertNull(Order::find($order->id));
    }

    /** @test */
    function converting_to_an_array()
    {
        $concert = Concert::factory()->create(['ticket_price' => 1200])->addTickets(5);
        $order = $concert->orderTickets('jane@example.com', 5);

        $result = $order->toArray();

        $this->assertEquals([
            'email' => 'jane@example.com',
            'ticket_quantity' => 5,
            'amount' => 6000,
        ], $result);
    }

    /** @test */
    function creating_an_order_from_tickets_and_email()
    {
        $concert = Concert::factory()->create(['ticket_price' => 1200])->addTickets(5);
        $this->assertEquals(5, $concert->ticketsRemaining());
        $order = Order::forTickets($concert->findTickets(3), 'john@example.com');

        $this->assertEquals('john@example.com', $order->email);
        $this->assertEquals(3, $order->ticketQuantity());
        $this->assertEquals(3600, $order->amount);
        $this->assertEquals(2, $concert->ticketsRemaining());
    }
}
