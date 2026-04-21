<?php

namespace Database\Factories;

use App\Models\Offre;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Offre>
 */
class OffreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
{
    return [
        'titre'        => $this->faker->jobTitle(),
        'description'  => $this->faker->paragraphs(2, true),
        'localisation' => $this->faker->city(),
        'type'         => $this->faker->randomElement(['CDI', 'CDD', 'stage']),
        'actif'        => true,
    ];
}
}
