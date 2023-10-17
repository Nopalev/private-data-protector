<?php

namespace Database\Seeders;

use App\Http\Controllers\EncryptionController;
use App\Models\Biodata;
use App\Models\File;
use App\Models\PublicKey;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

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
                $encryptor = new EncryptionController;
                $user = User::factory()->count(1)->unverified()->state([
                    'encryption_method' => $encryption_method[$i],
                    'encryption_mode' => $encryption_mode[$j]
                ])->create();

                PublicKey::create([
                    'user_id' => User::count(),
                    'public_key' => Str::random(16),
                    'public_IV' => Str::random(16)
                ]);

                Biodata::create([
                    'user_id' => User::count(),
                    'name' => $encryptor->factory_encrypt($user[0], '1N1password', 'Name'),
                    'gender' => $encryptor->factory_encrypt($user[0], '1N1password', 'Male'),
                    'nationality' => $encryptor->factory_encrypt($user[0], '1N1password', 'Indonesia'),
                    'religion' => $encryptor->factory_encrypt($user[0], '1N1password', 'Islam'),
                    'marital_status' => $encryptor->factory_encrypt($user[0], '1N1password', 'Single'),
                ]);

                $filename = $encryption_method[$i] . '_' . $encryption_mode[$j] . '_' . 'dataset.pdf';

                File::create([
                    'user_id' => User::count(),
                    'filename' => $encryptor->factory_encrypt($user[0], '1N1password', $filename),
                    'filecode' => $filename,
                    'filetype' => 'document',
                    'mime' => 'application/pdf'
                ]);

                $file_src = fopen(public_path('storage/documents/dataset.pdf'), 'r');
                $raw = fread($file_src, filesize(public_path('storage/documents/dataset.pdf')));
                fclose($file_src);

                $file_dest = fopen(public_path('storage/documents/' . $filename), 'w+');
                fwrite($file_dest, $encryptor->factory_encrypt($user[0], '1N1password', $raw));
                fclose($file_dest);

                $filename = $encryption_method[$i] . '_' . $encryption_mode[$j] . '_' . 'dataset.png';

                File::create([
                    'user_id' => User::count(),
                    'filename' => $encryptor->factory_encrypt($user[0], '1N1password', $filename),
                    'filecode' => $filename,
                    'filetype' => 'image',
                    'mime' => 'image/png'
                ]);

                $file_src = fopen(public_path('storage/images/dataset.png'), 'r');
                $raw = fread($file_src, filesize(public_path('storage/images/dataset.png')));
                fclose($file_src);

                $file_dest = fopen(public_path('storage/images/' . $filename), 'w+');
                fwrite($file_dest, $encryptor->factory_encrypt($user[0], '1N1password', $raw));
                fclose($file_dest);

                $filename = $encryption_method[$i] . '_' . $encryption_mode[$j] . '_' . 'dataset.mp4';

                File::create([
                    'user_id' => User::count(),
                    'filename' => $encryptor->factory_encrypt($user[0], '1N1password', $filename),
                    'filecode' => $filename,
                    'filetype' => 'video',
                    'mime' => 'video/mp4'
                ]);

                $file_src = fopen(public_path('storage/videos/dataset.mp4'), 'r');
                $raw = fread($file_src, filesize(public_path('storage/videos/dataset.mp4')));
                fclose($file_src);

                $file_dest = fopen(public_path('storage/videos/' . $filename), 'w+');
                fwrite($file_dest, $encryptor->factory_encrypt($user[0], '1N1password', $raw));
                fclose($file_dest);
            }
        }
    }
}
