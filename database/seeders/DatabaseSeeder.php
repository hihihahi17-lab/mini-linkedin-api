<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
{
    $competences = \App\Models\Competence::factory()->count(10)->create();


    \App\Models\User::factory()->count(2)->create(['role' => 'admin']);


    \App\Models\User::factory()->count(5)->create(['role' => 'recruteur']);

   
    \App\Models\User::factory()
        ->count(10)
        ->create(['role' => 'candidat'])
        ->each(function ($user) use ($competences) {
            $profil = \App\Models\Profil::factory()->create([
                'user_id' => $user->id
            ]);

     
            $selected = $competences->random(rand(2, 4));
            foreach ($selected as $comp) {
                $profil->competences()->attach($comp->id, [
                    'niveau' => collect(['débutant', 'intermédiaire', 'expert'])->random()
                ]);
            }
        });
}
}
