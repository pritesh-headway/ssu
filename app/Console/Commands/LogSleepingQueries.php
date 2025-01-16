<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class LogSleepingQueries extends Command
{
    protected $signature = 'log:sleeping-queries';
    protected $description = 'Log all sleeping queries in the database';

    public function handle()
    {
        $results = DB::select("SELECT * FROM information_schema.processlist WHERE COMMAND = 'Sleep'");
        print_r($results); // Inspect the structure of the results
        foreach ($results as $result) {
            $this->info('Sleeping Query Detected: ID - ' . $result->id . ', Time - ' . $result->time . ' seconds');
            \Log::warning('Sleeping Query Detected:', (array) $result);
        }
        return Command::SUCCESS;
    }
}

