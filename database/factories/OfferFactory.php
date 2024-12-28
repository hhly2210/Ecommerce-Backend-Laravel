<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Offer>
 */
class OfferFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'offer_name' => $this->faker->word,
            'start_date' => $this->faker->dateTimeBetween('2024-01-01', '2024-12-31'),
            'end_date' => $this->faker->dateTimeBetween('2025-01-01', '2025-12-31'),
        ];
    }
}
