<?php

namespace App\Console\Commands;

use App\Services\GoogleSheetsService;
use Illuminate\Console\Command;

class SyncGoogleSheets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sheets:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync allowed items to Google Sheets';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Google Sheets synchronization...');

        $service = new GoogleSheetsService();
        $result = $service->syncItems();

        if ($result) {
            $this->info('Google Sheets synchronization completed successfully.');
        } else {
            $this->error('Google Sheets synchronization failed.');
            return 1;
        }

        return 0;
    }
}
