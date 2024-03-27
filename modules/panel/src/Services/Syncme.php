<?php

namespace Fpaipl\Panel\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Syncme
{
    protected $debug;
    protected $token;
    protected $baseUrl;

    /**
     * Constructor to initialize the Syncme service with configuration data.
     */
    public function __construct($debug = false, $config = null)
    {
        $this->debug = $debug;
        if ($config) {
            $this->token = $config['token'];
            $this->baseUrl = $config['base_url'];
        } else {
            $this->token = config('monaal.token');
            $this->baseUrl = config('monaal.base_url');
        }
    }

    public function post($url, $body = null)
    {
        $fullUrl = $this->baseUrl . $url;
        $headers = ['Authorization' => 'Bearer ' . $this->token];
        $body = $body ?? ['token' => $this->token];

        if ($this->debug) {
            Log::info("Making POST request", [
                'url' => $fullUrl,
                'method' => 'POST',
                'headers' => $headers,
                'body' => $body
            ]);
        }

        try {
            $response = Http::withHeaders($headers)->post($fullUrl, $body);

            if ($this->debug) {
                Log::info("Received response from: $fullUrl", [
                    'status' => $response->status(),
                    'headers' => $response->headers(),
                    'body' => $response->json()
                ]);
            }

            if (!$response->successful()) {
                throw new \Exception("HTTP request failed with status {$response->status()}");
            }

            return $response->json();
        } catch (\Throwable $exception) {
            if ($this->debug) {
                Log::error("Error during POST request to: $fullUrl", [
                    'error' => $exception->getMessage(),
                ]);
            }

            return [
                'status' => 'error',
                'message' => $exception->getMessage(),
            ];
        }
    }
}
