<?php

namespace Tests\Unit;

use App\Models\Concert;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class TicketTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_ticket_can_be_released()
    {
        $concert = Concert::factory()->create();
        $concert->addTickets(1);

        $order = $concert->orderTickets('jane@example.com', 1);
        $ticket = $order->tickets()->first();

        $this->assertEquals($order->id, $ticket->order_id);

        $ticket->release();
        $this->assertNull($ticket->fresh()->order_id);
    }
}
