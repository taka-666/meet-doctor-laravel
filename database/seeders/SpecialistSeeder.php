<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\MasterData\Specialist;
use Illuminate\Support\Facades\DB;

class SpecialistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create data here
        $specialist = [
            [
                'name' => 'Dermatology',
                'price' => '25000',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),

            ],
            [
                'name' => 'Neurology',
                'price' => '25000',
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),

            ],
            [
                'name' => 'Dentists',
                'price' => '25000',
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),

            ],
            [
                'name' => 'Allergist',
                'price' => '25000',

                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),

            ],
            [
                'name' => 'Cardiologist',
                'price' => '25000',
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),

            ],

        ];
        // array ini $specialist akan dimasukkan kedalam tabel specialist
        Specialist::insert($specialist);
    }
}
