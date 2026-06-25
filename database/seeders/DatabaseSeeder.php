<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;


class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;
    public function run(): void
    {
        Project::factory()->count(10)->create();
    }
}
