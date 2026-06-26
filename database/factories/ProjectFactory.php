<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        $imageUrls = [
            "example2.jpg",
            "example3.jpg",
            "example4.jpg",
            "example4.jpg",
            "example4.jpg",
            "example4.jpg",
            "example4.jpg",
            "example4.jpg",
        ];

        $technologies = [
            fake()->randomElement(['Laravel', 'Vue', 'React', 'Tailwind CSS', 'MySQL', 'Alpine.js']),
            fake()->randomElement(['PHP', 'TypeScript', 'Vite', 'Livewire', 'Inertia', 'JavaScript']),
        ];

        return [
            'title' => fake()->catchPhrase(),
            'subtitle' => fake()->words(4, true),
            'description' => fake()->paragraph(),
            'images' => json_encode($imageUrls),
            'thumbnail' => "example1.jpg",
            'github_url' => fake()->url(),
            'is_featured' => fake()->boolean(25),
            'technologies' => json_encode($technologies),
        ];
    }
}