<?php

namespace Database\Factories;

use App\Models\Concert;
use Carbon\Carbon;
use Faker\Provider\en_US\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConcertFactory extends Factory
{
    protected $model = Concert::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'title' => 'Example Band',
            'subtitle' => 'with The Fake Openers',
            'date' => Carbon::parse('+2 weeks'),
            'ticket_price' => $this->faker->numberBetween(1000, 6000),
            'venue' => 'The Example Theatre',
            'venue_address' => $this->faker->streetAddress,
            'city' => $this->faker->city,
            'state' => Address::stateAbbr(),
            'zip' => Address::postcode(),
            'additional_information' => $this->faker->text(150)
        ];
    }

    public function publish() {
        return $this->state(function ($attributes) {
            return [
                'published_at' => Carbon::parse('-1 week')
            ];
        });
    }

    public function unpublished() {
        return $this->state(function ($attribute) {
            return [
                'published_at' => null
            ];
        });
    }
}
