<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class GoldPriceController extends Controller
{
    public function show(): JsonResponse
    {
        $goldPrice = Cache::remember('pegadaian_gold_price', now()->addHours(3), function () {
            $response = Http::acceptJson()
                ->withHeaders([
                    'content-type' => 'application/json',
                    'referer' => 'https://sahabat.pegadaian.co.id/harga-emas',
                    'user-agent' => 'Mozilla/5.0',
                ])
                ->timeout(15)
                ->get('https://sahabat.pegadaian.co.id/gold/prices/savings');

            if (!$response->successful()) {
                return null;
            }

            $price = $response->json('data.hargaBeli');

            if (!is_numeric($price)) {
                return null;
            }

            return (int) round((float) $price);
        });

        if ($goldPrice === null) {
            return response()->json([
                'message' => 'Harga emas gagal diambil.',
            ], 502);
        }

        return response()->json([
            'price' => $goldPrice,
            'formatted_price' => 'Rp'.number_format($goldPrice, 0, ',', '.'),
            'unit' => '0.01 gram',
            'source' => 'Pegadaian',
        ]);
    }
}
