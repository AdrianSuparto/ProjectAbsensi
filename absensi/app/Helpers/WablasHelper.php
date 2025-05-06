<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WablasHelper
{
    public static function kirimPesan($nomor, $pesan)
    {
        $url = env('WABLAS_URL');
        $secret = env('WABLAS_SECRET');
        $token = env('WABLAS_API_KEY');

        $authorization = "{$token}.{$secret}";

        Log::info('WABLAS URL:', ['url' => $url]);
        Log::info('WABLAS Token:', ['token' => $token]);
        Log::info('WABLAS Secret:', ['secret' => $secret]);
        Log::info('WABLAS Secret:', ['Nomor' => $nomor]);


        $response = Http::withHeaders([
            'Authorization' => $authorization,
        ])->post($url, [
            'phone' => $nomor,
            'message' => $pesan,
            'secret' => false,
            'priority' => true,
        ]);

        // Tambahkan log untuk debug
        Log::info('WA Response:', [
            'status' => $response->status(),
            'body' => $response->body()
        ]);

        return $response->successful();
    }
}
