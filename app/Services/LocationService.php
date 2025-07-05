<?php

namespace App\Services;

use Stevebauman\Location\Facades\Location;
use Illuminate\Support\Facades\Cache;

class LocationService
{
    public function getLocation($ip = null)
{
    $ip = $ip ?: request()->ip();
    
    // Cache location data for 1 hour
    return Cache::remember("location_{$ip}", 3600, function() use ($ip) {
        try {
            $position = Location::get($ip);
            
            if ($position) {
                return [
                    'ip' => $position->ip,
                    'countryName' => $position->countryName,
                    'countryCode' => $position->countryCode,
                    'regionName' => $position->regionName,
                    'cityName' => $position->cityName,
                    'latitude' => $position->latitude,
                    'longitude' => $position->longitude,
                    'timezone' => $position->timezone,
                    'currency' => $this->getCurrencyByCountry($position->countryCode),
                ];
            }
            
            return $this->getDefaultLocation();
            
        } catch (\Exception $e) {
            return $this->getDefaultLocation();
        }
    });
}

    private function getDefaultLocation(): array
    {
        return [
            'ip' => request()->ip(),
            'countryName' => 'Bangladesh',
            'countryCode' => 'BD',
            'regionName' => 'Dhaka',
            'cityName' => 'Dhaka',
            'latitude' => 23.8103,
            'longitude' => 90.4125,
            'timezone' => 'Asia/Dhaka',
            'currency' => 'BDT',
        ];
    }

    private function getCurrencyByCountry($countryCode): string
    {
        $currencies = [
            'BD' => 'BDT',
            'IN' => 'INR',
            'US' => 'USD',
            'GB' => 'GBP',
            'AU' => 'AUD',
            'CA' => 'CAD',
            'AE' => 'AED',
            'SA' => 'SAR',
            'MY' => 'MYR',
            'SG' => 'SGD',
        ];

        return $currencies[$countryCode] ?? 'USD';
    }
}