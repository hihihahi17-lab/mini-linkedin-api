<?php

namespace Database\Factories;

use App\Models\Competence;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Competence>
 */
class CompetenceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
{
    $competences = ['PHP', 'Laravel', 'JavaScript', 'React', 'MySQL', 'Python', 'Docker'];
    $categories  = ['Backend', 'Frontend', 'DevOps', 'Base de données'];

    return [
        'nom'       => $this->faker->randomElement($competences),
        'categorie' => $this->faker->randomElement($categories),
    ];
}
}
