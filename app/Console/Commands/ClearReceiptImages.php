<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ClearReceiptImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'receipts:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all images in the receipts folder';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $directory = public_path('storage/images/receipts');

        // Check if the directory exists
        if (File::exists($directory)) {
            // Delete all files in the directory
            File::cleanDirectory($directory);
            
            // Optional: if you want to delete the directory itself, you can use:
            // File::deleteDirectory($directory);

            $this->info("All files in the receipts directory have been deleted.");
        } else {
            $this->info("Directory does not exist.");
        }
    }
}
