<?php

namespace Database\Seeders;

use App\Models\MasterData\Consultation;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConsultationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create data here
        $consultation = [
            // mungkin ada update untuk consultation dengan cara inputan user
        ];
        // array ini $consultation akan dimasukkan kedalam tabel consultation
        Consultation::insert($consultation);
    }
}
