<?php

namespace Tests\Unit;

use App\Models\Concert;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
//use PHPUnit\Framework\TestCase;
use Tests\TestCase;


class ConcertTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function can_get_formatted_date() {
        $concert = Concert::factory()->make([
            'date' => Carbon::parse('2016-12-01 8:00pm'),
        ]);

        $this->assertEquals('December 1, 2016', $concert->formatted_date);
    }

    /** @test */
    function can_get_formatted_start_time() {
        $concert = Concert::factory()->make([
            'date' => Carbon::parse('2016-12-01 17:00:00')
        ]);

        $this->assertEquals('5:00pm', $concert->formatted_start_time);
    }

    /** @test */
    function can_get_ticket_price_in_dollars() {
        $concert = Concert::factory()->make([
            'ticket_price' => 6750
        ]);

        $this->assertEquals('67.50', $concert->ticket_price_in_dollars);
    }

    /** @test */
    function concerts_with_a_published_at_date_are_published() {
        $publishedConcertA = Concert::factory()->create(['published_at' => Carbon::parse('-1 week')]);
        $publishedConcertB = Concert::factory()->create(['published_at' => Carbon::parse('-1 week')]);
        $unPublishedConcert = Concert::factory()->create(['published_at' => null]);

        $publishedConcerts = Concert::published()->get();

        $this->assertTrue($publishedConcerts->contains($publishedConcertA));
        $this->assertTrue($publishedConcerts->contains($publishedConcertB));
        $this->assertFalse($publishedConcerts->contains($unPublishedConcert));
    }

    /** @test */
    function can_order_concert_tickets() {
        $concert = Concert::factory()->create();
        $order = $concert->orderTickets('jane@example.com', 3);

        $this->assertEquals('jane@example.com', $order->email);
        $this->assertEquals(3, $order->tickets()->count());
    }

    /** @test */
    function can_add_tickets() {
        $concert = Concert::factory()->create();
        $concert->addTickets(50);
        $this->assertEquals(50, $concert->ticketsRemaining());
    }

    /** @test */
    function tickets_remaining_does_not_include_tickets_associated_with_an_order() {

    }
}
