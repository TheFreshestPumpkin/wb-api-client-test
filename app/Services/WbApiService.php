<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WbApiService
{
    public function fetch(string $endpoint, string $dateFrom, ?string $dateTo = null, int $page = 1): array
    {
        $query = [
            'dateFrom' => $dateFrom,
            'page'     => $page,
            'limit'    => config('wbapi.limit', 500),
            'key'      => config('wbapi.key'),
        ];

        if ($dateTo) {
            $query['dateTo'] = $dateTo;
        }

        $url = rtrim(config('wbapi.base_url'), '/') . "/$endpoint";

        $response = Http::get($url, $query);

        if ($response->failed()) {
            throw new \RuntimeException("API request falled: " . $response->status() . " " . $response->body());
        }

        return $response->json() ?? [];
    }
}
