<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class JneService
{
    private $apiUrl;
    private $username;
    private $apiKey;
    private $isProduction;

    public function __construct()
    {
        $this->apiUrl = config('services.jne.api_url');
        $this->username = config('services.jne.username');
        $this->apiKey = config('services.jne.api_key');
        $this->isProduction = config('services.jne.is_production', false);
    }

    public function getShippingCost($origin, $destination, $weight, $service = 'REG')
    {
        // Mode development - return mock data
        if (!$this->isProduction) {
            return $this->getMockShippingCost($service, $weight);
        }

        // Production API call
        return $this->callJneApi($origin, $destination, $weight, $service);
    }

    private function getMockShippingCost($service, $weight)
    {
        $baseRates = [
            'REG' => 9000,
            'YES' => 14000,
            'OKE' => 7000
        ];

        $estimations = [
            'REG' => '2-3 hari',
            'YES' => '1-2 hari', 
            'OKE' => '3-4 hari'
        ];

        $baseRate = $baseRates[$service] ?? $baseRates['REG'];
        $weightMultiplier = ceil($weight / 1000); // per kg
        $cost = $baseRate * max(1, $weightMultiplier);

        return [
            'service' => $service,
            'cost' => $cost,
            'estimation' => $estimations[$service] ?? $estimations['REG'],
            'weight' => $weight
        ];
    }

    private function callJneApi($origin, $destination, $weight, $service)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->post($this->apiUrl . '/tariff', [
                'username' => $this->username,
                'api_key' => $this->apiKey,
                'from' => $origin,
                'thru' => $destination,
                'weight' => $weight,
                'service' => $service
            ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('JNE API Error: ' . $e->getMessage());
            return $this->getMockShippingCost($service, $weight);
        }
    }

    public function trackPackage($awb)
    {
        if (!$this->isProduction) {
            return $this->getMockTracking($awb);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get($this->apiUrl . '/trace', [
                'awb' => $awb
            ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('JNE Tracking Error: ' . $e->getMessage());
            return $this->getMockTracking($awb);
        }
    }

    private function getMockTracking($awb)
    {
        return [
            'awb' => $awb,
            'status' => 'ON PROCESS',
            'history' => [
                ['date' => now()->format('Y-m-d H:i:s'), 'desc' => 'SHIPMENT RECEIVED BY JNE COUNTER OFFICER'],
                ['date' => now()->subHours(2)->format('Y-m-d H:i:s'), 'desc' => 'SHIPMENT FORWARDED TO DESTINATION'],
            ]
        ];
    }

    public function getCities($province = null)
    {
        if (!$this->isProduction) {
            return $this->getMockCities();
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get($this->apiUrl . '/city', [
                'province' => $province
            ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('JNE Cities Error: ' . $e->getMessage());
            return $this->getMockCities();
        }
    }

    private function getMockCities()
    {
        return [
            ['code' => 'CGK', 'name' => 'Jakarta'],
            ['code' => 'BDO', 'name' => 'Bandung'],
            ['code' => 'SBY', 'name' => 'Surabaya'],
            ['code' => 'MDN', 'name' => 'Medan'],
            ['code' => 'DPS', 'name' => 'Denpasar'],
        ];
    }
}