<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DocumentationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $type = [
            [
                "id" => 1,
                "name" => "maleta",
            ],
            [
                "id" => 2,
                "name" => "Caja",
            ],
            [
                "id" => 3,
                "name" => "Bolsa",
            ],
            [
                "id" => 4,
                "name" => "Material",
            ],
        ];
    }
}
