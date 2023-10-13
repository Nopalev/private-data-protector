<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class FlushTemp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'temp:flush';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Flushing directory storage/temp';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Storage::deleteDirectory('public/temp');
        Storage::makeDirectory('public/temp');
    }
}
