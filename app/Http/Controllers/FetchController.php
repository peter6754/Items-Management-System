<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Output\BufferedOutput;

class FetchController extends Controller
{
    public function fetch($count = null)
    {
        $output = new BufferedOutput();
        
        $command = 'sheets:fetch';
        if ($count && $count > 0) {
            $command .= ' --count=' . (int)$count;
        }
        
        try {
            Artisan::call($command, [], $output);
            $result = $output->fetch();
            
            return response($this->formatOutput($result), 200, [
                'Content-Type' => 'text/plain; charset=utf-8'
            ]);
            
        } catch (\Exception $e) {
            return response('Error executing command: ' . $e->getMessage(), 500, [
                'Content-Type' => 'text/plain; charset=utf-8'
            ]);
        }
    }

    private function formatOutput($output)
    {
        $lines = explode("\n", $output);
        $formatted = [];
        
        foreach ($lines as $line) {
            $line = trim($line);
            if (!empty($line)) {
                $line = preg_replace('/\033\[[0-9;]*m/', '', $line);
                $formatted[] = $line;
            }
        }
        
        return implode("\n", $formatted) . "\n";
    }
}
