<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ShipperService
{
    private $apiUrl;
    private $apiKey;
    private $isProduction;

    public function __construct()
    {
        $this->apiUrl = config('services.shipper.api_url');
        $this->apiKey = config('services.shipper.api_key');
        $this->isProduction = config('services.shipper.is_production', false);
    }

    public function getShippingRates($origin, $destination, $weight, $length = 10, $width = 10, $height = 10)
    {
        if (!$this->isProduction) {
            return $this->getMockShippingRates($weight);
        }

        try {
            $response = Http::withHeaders([
                'X-API-Key' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl . '/v3/pricing/domestic', [
                'origin' => $origin,
                'destination' => $destination,
                'length' => $length,
                'width' => $width,
                'height' => $height,
                'weight' => $weight,
                'value' => 100000
            ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Shipper API Error: ' . $e->getMessage());
            return $this->getMockShippingRates($weight);
        }
    }

    private function getMockShippingRates($weight)
    {
        $weightMultiplier = ceil($weight / 1000);
        
        return [
            'data' => [
                'pricing' => [
                    [
                        'logistic' => ['name' => 'JNE', 'code' => 'jne'],
                        'rate' => ['name' => 'REG', 'code' => 'reg'],
                        'final_price' => 9000 * max(1, $weightMultiplier),
                        'insurance_fee' => 0,
                        'min_day' => 2,
                        'max_day' => 3
                    ],
                    [
                        'logistic' => ['name' => 'JNE', 'code' => 'jne'],
                        'rate' => ['name' => 'YES', 'code' => 'yes'],
                        'final_price' => 14000 * max(1, $weightMultiplier),
                        'insurance_fee' => 0,
                        'min_day' => 1,
                        'max_day' => 2
                    ],
                    [
                        'logistic' => ['name' => 'J&T', 'code' => 'jnt'],
                        'rate' => ['name' => 'REG', 'code' => 'reg'],
                        'final_price' => 8500 * max(1, $weightMultiplier),
                        'insurance_fee' => 0,
                        'min_day' => 2,
                        'max_day' => 4
                    ],
                    [
                        'logistic' => ['name' => 'SiCepat', 'code' => 'sicepat'],
                        'rate' => ['name' => 'REG', 'code' => 'reg'],
                        'final_price' => 8000 * max(1, $weightMultiplier),
                        'insurance_fee' => 0,
                        'min_day' => 2,
                        'max_day' => 3
                    ]
                ]
            ]
        ];
    }

    public function trackShipment($waybill)
    {
        if (!$this->isProduction) {
            return $this->getMockTracking($waybill);
        }

        try {
            $response = Http::withHeaders([
                'X-API-Key' => $this->apiKey,
            ])->get($this->apiUrl . '/v3/track', [
                'waybill' => $waybill
            ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Shipper Tracking Error: ' . $e->getMessage());
            return $this->getMockTracking($waybill);
        }
    }

    private function getMockTracking($waybill)
    {
        return [
            'data' => [
                'order_id' => $waybill,
                'status' => 'confirmed',
                'logistic' => ['name' => 'JNE'],
                'tracking' => [
                    [
                        'shipper_status' => ['name' => 'Confirmed', 'description' => 'Shipment confirmed'],
                        'logistic_status' => ['name' => 'SHIPMENT_RECEIVED', 'description' => 'Shipment received by courier'],
                        'updated_date' => now()->format('Y-m-d H:i:s')
                    ]
                ]
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
                'X-API-Key' => $this->apiKey,
            ])->get($this->apiUrl . '/v3/location/cities', [
                'province' => $province
            ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Shipper Cities Error: ' . $e->getMessage());
            return $this->getMockCities();
        }
    }

    private function getMockCities()
    {
        return [
            'data' => [
                ['id' => 6, 'name' => 'Jakarta Pusat'],
                ['id' => 23, 'name' => 'Bandung'],
                ['id' => 444, 'name' => 'Surabaya'],
                ['id' => 151, 'name' => 'Medan'],
                ['id' => 17, 'name' => 'Denpasar']
            ]
        ];
    }
}