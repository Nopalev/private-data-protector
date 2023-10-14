<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class SeedDataset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dataset:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seeding the application with dataset files for testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Storage::copy('dataset/document/dataset.pdf', 'public/documents/dataset.pdf');
        Storage::copy('dataset/image/dataset.png', 'public/images/dataset.png');
        Storage::copy('dataset/video/dataset.mp4', 'public/videos/dataset.mp4');
    }
}
