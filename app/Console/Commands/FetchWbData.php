<?php

namespace App\Console\Commands;

use App\Services\WbApiService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FetchWbData extends Command
{
    protected $signature = 'wb:fetch
        {endpoint : stocks|sales|orders|incomes|all}
        {--from= : даа начала в формате Y-m-d}
        {--to= : дата конца в формате Y-m-d}';

    protected $description = 'Fetch data from WB API and save to database';

    public function handle(WbApiService $service): int
    {
        $endpoint = $this->argument('endpoint');
        $from = $this->option('from') ?? now()->toDateString();
        $to   = $this->option('to') ?? now()->toDateString();

        $endpoints = $endpoint === 'all'
            ? ['stocks', 'sales', 'orders', 'incomes']
            : [$endpoint];

        foreach ($endpoints as $ep) {
            $this->info("Fetching $ep from $from to $to ...");

            $page = 1;
            while (true) {
                try {
                    $json = $service->fetch($ep, $from, $to, $page);
                } catch (\Throwable $e) {
                    $this->error("Error: " . $e->getMessage());
                    break;
                }

                $items = $json['data'] ?? [];

                if (empty($items)) {
                    $this->info("No more data at page $page");
                    break;
                }

                foreach ($items as $row) {
                    DB::table('wb_raw')->insert([
                        'endpoint' => $ep,
                        'page' => $page,
                        'date_from' => $from,
                        'date_to' => $to,
                        'payload' => json_encode($row, JSON_UNESCAPED_UNICODE),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                $this->info("Page $page done, saved " . count($items) . " records");

                $lastPage = $json['meta']['last_page'] ?? null;
                if ($lastPage && $page >= $lastPage) {
                    $this->info("Reached last page ($lastPage)");
                    break;
                }

                $page++;
            }
        }

            return self::SUCCESS;
    }
}
