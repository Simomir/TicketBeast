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

    }
}
