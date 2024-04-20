<?php

namespace Database\Seeders;

use App\Models\MasterData\ConfigPayment;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfigPaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create data here
        $config_payment = [
            [
                'fee' => '150000',
                'vat' => '200',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'), // vat is percentage
            ]
        ];
        // array ini $config_payment akan dimasukkan kedalam tabel config_payment
        ConfigPayment::insert($config_payment);

    }
}
