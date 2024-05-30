<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = [
            [
                'name'           => 'Super Admin',
                'email'          => 'admin@mail.com',
                'password'       => Hash::make('Admin@12345'),
                'remember_token' => null,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [                    //Doctor 1
                'name'           => 'Taka Pratama',
                'email'          => 'taka@gmail.com',
                'password'       => Hash::make('Taka12345'),
                'remember_token' => null,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [                    //Doctor 2
                'name'           => 'Galih Pratama',
                'email'          => 'Galih@gmail.com',
                'password'       => Hash::make('Taka12345'),
                'remember_token' => null,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [                    //Doctor 3
                'name'           => 'Shayna Putri',
                'email'          => 'shayna@gmail.com',
                'password'       => Hash::make('Shayna12345'),
                'remember_token' => null,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [                    //Doctor 4
                'name'           => 'Nabila Anisa',
                'email'          => 'nabila@gmail.com',
                'password'       => Hash::make('Nabila12345'),
                'remember_token' => null,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [                    //Patient 1
                'name'           => 'Aditya Pratama',
                'email'          => 'Adit@gmail.com',
                'password'       => Hash::make('Adit12345'),
                'remember_token' => null,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
        ];

        User::insert($user);
    }
}
