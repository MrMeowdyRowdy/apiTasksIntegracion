<?php

namespace Database\Seeders;

use App\Models\Tarea;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TareaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tarea::create([
            'title' => 'Ejemplo 1',
            'description' => 'Ejemplo 1',
            'status' => 'Ejemplo 1',
        ]);
        Tarea::create([
            'title' => 'Ejemplo 2',
            'description' => 'Ejemplo 2',
            'status' => 'Ejemplo 2',
        ]);
        Tarea::create([
            'title' => 'Ejemplo 3',
            'description' => 'Ejemplo 3',
            'status' => 'Ejemplo 3',
        ]);
    }
}
