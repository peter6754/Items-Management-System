<?php

namespace App\Console\Commands;

use App\Services\GoogleSheetsService;
use Illuminate\Console\Command;

class FetchGoogleSheets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sheets:fetch {--count=0 : Limit the number of rows to fetch}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch data from Google Sheets and display ID/Comments with progress bar';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fetching data from Google Sheets...');

        $service = new GoogleSheetsService();
        $limit = $this->option('count') > 0 ? (int)$this->option('count') : null;
        
        $data = $service->fetchDataWithComments($limit);

        if (empty($data)) {
            $this->warn('No data found in Google Sheets.');
            return 0;
        }

        $this->info("Found " . count($data) . " rows to process.");
        $this->newLine();

        $progressBar = $this->output->createProgressBar(count($data));
        $progressBar->setFormat('Processing: %current%/%max% [%bar%] %percent:3s%%');
        $progressBar->start();

        foreach ($data as $row) {
            $progressBar->advance();
            
            $id = $row['id'] ?? 'N/A';
            $comment = !empty($row['comment']) ? $row['comment'] : 'No comment';
            
            $this->newLine();
            $this->line("ID: {$id} | Comment: {$comment}");
            
            usleep(50000);
        }

        $progressBar->finish();
        $this->newLine(2);
        $this->info('Fetch completed successfully.');

        return 0;
    }
}
