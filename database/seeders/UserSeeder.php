<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $encryption_method = [
            'AES',
            'DES',
            'RC4'
        ];
        $encryption_mode = [
            'CBC',
            'CFB',
            'OFB',
            'CTR'
        ];

        for ($i = 0; $i < 3; $i++) {
            for ($j = 0; $j < 4; $j++) {
                User::factory()->count(1)->unverified()->state([
                    'encryption_method' => $encryption_method[$i],
                    'encryption_mode' => $encryption_mode[$j]
                ])->create();
            }
        }
    }
}
