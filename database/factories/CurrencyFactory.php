<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Currency>
 */
class CurrencyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'country_name' => $this->faker->country,
            'currency_symbol' => $this->faker->currencyCode,
            'par_dollar_rate' => $this->faker->randomFloat(2, 0, 100),
        ];
    }
}
