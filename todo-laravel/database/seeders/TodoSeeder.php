<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Todo;

class TodoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $todos = [
            'Learn Laravel Basics',
            'Create Todo App',
            'Understand MVC Pattern',
            'Learn Eloquent ORM',
            'Practice Dependency Injection',
        ];
        
        foreach ($todos as $todo) {
            Todo::create(['title' => $todo]);
        }
    }
}
