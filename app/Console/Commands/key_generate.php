<?php

namespace App\Console\Commands;

use App\Models\PublicKey;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class key_generate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:key_generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generating Application\'s public key';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if(PublicKey::all()->isNotEmpty()){
            $this->error('Application\'s public key has been generated already');
        }
        else{
            $this->info($this->description);
            PublicKey::create([
                'public_key' => Str::random(16),
                'public_IV' => Str::random(16)
            ]);
        }
    }
}
