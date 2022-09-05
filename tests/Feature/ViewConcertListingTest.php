<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Concert;

class ViewConcertListingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function user_can_view_a_published_concert_listing() {

        $concert = Concert::factory()->create([
            'title' => 'The Red Chord',
            'subtitle' => 'with Animosity and Lethargy',
            'date' => Carbon::parse('December 13, 2016 8:00pm'),
            'ticket_price' => 3250,
            'venue' => 'The Mosh Pit',
            'venue_address' => '123 Example Lane',
            'city' => 'Laraville',
            'state' => 'ON',
            'zip' => '17916',
            'additional_information' => 'for tickets, call (555) 555-555.',
            'published_at' => Carbon::parse('-1 week')
        ]);

        $response = $this -> get('/concerts/'.$concert->id);

        $response->assertSee([
            'The Red Chord',
            'with Animosity and Lethargy',
            'December 13, 2016',
            '8:00pm',
            '32.50',
            'The Mosh Pit',
            '123 Example Lane',
            'Laraville, ON 17916',
            'for tickets, call (555) 555-555.'
        ]);

    }

    /** @test */
    function user_cannot_view_unpublished_concert_listings() {
        $concert = Concert::factory()->create([
            'published_at' => null
        ]);

        $this->get('/concerts/'.$concert->id)->assertNotFound();
    }
}
