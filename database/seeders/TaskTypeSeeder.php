<?php

namespace Database\Seeders;

use App\Models\TaskType;
use Illuminate\Database\Seeder;

class TaskTypeSeeder extends Seeder
{
    public function run()
    {
        $types = [
            ['name' => 'Kurulum'],
            ['name' => 'Bakım'],
            ['name' => 'Arıza'],
        ];

        foreach ($types as $type) {
            TaskType::create($type);
        }
    }
} 