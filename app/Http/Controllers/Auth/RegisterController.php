<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\OtpVerification;
use App\Services\LocationService;
use App\Services\Referral\ReferralService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    protected $locationService;
    protected $referralService;

    public function __construct(LocationService $locationService, ReferralService $referralService)
    {
        $this->locationService = $locationService;
        $this->referralService = $referralService;
    }

    public function showRegistrationForm(Request $request)
    {
        $locationData = $this->locationService->getLocation($request->ip());
        
        // Simple country list instead of package
        $countries = $this->getCountryList();
        
        // Get referral code from session or query parameter
        $referralCode = session('referral_code', $request->get('ref'));

        return view('auth.register', [
            'locationData' => $locationData,
            'countries' => $countries,
            'referralCode' => $referralCode,
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone_number' => ['required', 'string', 'regex:/^[0-9]{10,15}$/'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'country_code' => ['required', 'string', 'size:2'],
            'country_name' => ['required', 'string'],
            'terms' => ['required', 'accepted'],
        ]);

        DB::transaction(function () use ($request) {
            // Get location details
            $locationData = $this->locationService->getLocation($request->ip());

            // Create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone_number' => $request->phone_number,
                'country_code' => $request->country_code,
                'country_name' => $request->country_name,
                'city' => $locationData['cityName'] ?? null,
                'timezone' => $locationData['timezone'] ?? null,
                'currency' => $this->getCurrencyByCountry($request->country_code),
            ]);
            
            // Process referral if code exists
            $referralCode = session('referral_code') ?? $request->get('referral_code');
            if ($referralCode) {
                $this->referralService->processReferralRegistration($user, $referralCode);
            }

            // Create OTP
            $otp = OtpVerification::createForEmail($user->email);
            
            // Send OTP notification
            $user->notify(new \App\Notifications\OtpNotification($otp));

            // Store in session for OTP verification
            session([
                'otp_session' => [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'expires_at' => now()->addMinutes(10),
                ]
            ]);
        });

        return redirect()->route('auth.verify.otp', ['email' => $request->email])
            ->with('success', 'Registration successful! Please verify your email.');
    }

    /**
     * Get list of countries
     */
    private function getCountryList()
    {
        return [
            // South Asia
            'BD' => 'Bangladesh',
            'IN' => 'India',
            'PK' => 'Pakistan',
            'LK' => 'Sri Lanka',
            'NP' => 'Nepal',
            'BT' => 'Bhutan',
            'MV' => 'Maldives',
            'AF' => 'Afghanistan',
            
            // Southeast Asia
            'MY' => 'Malaysia',
            'SG' => 'Singapore',
            'ID' => 'Indonesia',
            'TH' => 'Thailand',
            'PH' => 'Philippines',
            'VN' => 'Vietnam',
            'MM' => 'Myanmar',
            'KH' => 'Cambodia',
            'LA' => 'Laos',
            'BN' => 'Brunei',
            
            // East Asia
            'CN' => 'China',
            'JP' => 'Japan',
            'KR' => 'South Korea',
            'TW' => 'Taiwan',
            'HK' => 'Hong Kong',
            'MO' => 'Macau',
            'MN' => 'Mongolia',
            
            // Middle East
            'AE' => 'United Arab Emirates',
            'SA' => 'Saudi Arabia',
            'QA' => 'Qatar',
            'KW' => 'Kuwait',
            'BH' => 'Bahrain',
            'OM' => 'Oman',
            'JO' => 'Jordan',
            'LB' => 'Lebanon',
            'IQ' => 'Iraq',
            'IR' => 'Iran',
            'TR' => 'Turkey',
            'IL' => 'Israel',
            'PS' => 'Palestine',
            'YE' => 'Yemen',
            
            // Europe
            'GB' => 'United Kingdom',
            'DE' => 'Germany',
            'FR' => 'France',
            'IT' => 'Italy',
            'ES' => 'Spain',
            'NL' => 'Netherlands',
            'BE' => 'Belgium',
            'CH' => 'Switzerland',
            'AT' => 'Austria',
            'SE' => 'Sweden',
            'NO' => 'Norway',
            'DK' => 'Denmark',
            'FI' => 'Finland',
            'PL' => 'Poland',
            'PT' => 'Portugal',
            'GR' => 'Greece',
            'IE' => 'Ireland',
            'CZ' => 'Czech Republic',
            'HU' => 'Hungary',
            'RO' => 'Romania',
            'BG' => 'Bulgaria',
            'HR' => 'Croatia',
            'RU' => 'Russia',
            'UA' => 'Ukraine',
            
            // Americas
            'US' => 'United States',
            'CA' => 'Canada',
            'MX' => 'Mexico',
            'BR' => 'Brazil',
            'AR' => 'Argentina',
            'CL' => 'Chile',
            'CO' => 'Colombia',
            'PE' => 'Peru',
            'VE' => 'Venezuela',
            'EC' => 'Ecuador',
            'BO' => 'Bolivia',
            'PY' => 'Paraguay',
            'UY' => 'Uruguay',
            'CR' => 'Costa Rica',
            'PA' => 'Panama',
            'CU' => 'Cuba',
            'DO' => 'Dominican Republic',
            'GT' => 'Guatemala',
            
            // Africa
            'ZA' => 'South Africa',
            'EG' => 'Egypt',
            'NG' => 'Nigeria',
            'KE' => 'Kenya',
            'ET' => 'Ethiopia',
            'GH' => 'Ghana',
            'MA' => 'Morocco',
            'TN' => 'Tunisia',
            'DZ' => 'Algeria',
            'UG' => 'Uganda',
            'TZ' => 'Tanzania',
            'SN' => 'Senegal',
            'ZW' => 'Zimbabwe',
            'CM' => 'Cameroon',
            
            // Oceania
            'AU' => 'Australia',
            'NZ' => 'New Zealand',
            'FJ' => 'Fiji',
            'PG' => 'Papua New Guinea',
            'SB' => 'Solomon Islands',
            'VU' => 'Vanuatu',
            'NC' => 'New Caledonia',
            'PF' => 'French Polynesia',
            'GU' => 'Guam',
            'WS' => 'Samoa',
            'TO' => 'Tonga',
        ];
    }

    /**
     * Get currency by country code
     */
    private function getCurrencyByCountry($countryCode): string
    {
        $currencies = [
            // South Asia
            'BD' => 'BDT',
            'IN' => 'INR',
            'PK' => 'PKR',
            'LK' => 'LKR',
            'NP' => 'NPR',
            'BT' => 'BTN',
            'MV' => 'MVR',
            'AF' => 'AFN',
            
            // Southeast Asia
            'MY' => 'MYR',
            'SG' => 'SGD',
            'ID' => 'IDR',
            'TH' => 'THB',
            'PH' => 'PHP',
            'VN' => 'VND',
            'MM' => 'MMK',
            'KH' => 'KHR',
            'LA' => 'LAK',
            'BN' => 'BND',
            
            // East Asia
            'CN' => 'CNY',
            'JP' => 'JPY',
            'KR' => 'KRW',
            'TW' => 'TWD',
            'HK' => 'HKD',
            'MO' => 'MOP',
            'MN' => 'MNT',
            
            // Middle East
            'AE' => 'AED',
            'SA' => 'SAR',
            'QA' => 'QAR',
            'KW' => 'KWD',
            'BH' => 'BHD',
            'OM' => 'OMR',
            'JO' => 'JOD',
            'LB' => 'LBP',
            'IQ' => 'IQD',
            'IR' => 'IRR',
            'TR' => 'TRY',
            'IL' => 'ILS',
            'YE' => 'YER',
            
            // Europe
            'GB' => 'GBP',
            'DE' => 'EUR',
            'FR' => 'EUR',
            'IT' => 'EUR',
            'ES' => 'EUR',
            'NL' => 'EUR',
            'BE' => 'EUR',
            'CH' => 'CHF',
            'AT' => 'EUR',
            'SE' => 'SEK',
            'NO' => 'NOK',
            'DK' => 'DKK',
            'FI' => 'EUR',
            'PL' => 'PLN',
            'PT' => 'EUR',
            'GR' => 'EUR',
            'IE' => 'EUR',
            'CZ' => 'CZK',
            'HU' => 'HUF',
            'RO' => 'RON',
            'BG' => 'BGN',
            'HR' => 'HRK',
            'RU' => 'RUB',
            'UA' => 'UAH',
            
            // Americas
            'US' => 'USD',
            'CA' => 'CAD',
            'MX' => 'MXN',
            'BR' => 'BRL',
            'AR' => 'ARS',
            'CL' => 'CLP',
            'CO' => 'COP',
            'PE' => 'PEN',
            'VE' => 'VES',
            'EC' => 'USD',
            'BO' => 'BOB',
            'PY' => 'PYG',
            'UY' => 'UYU',
            'CR' => 'CRC',
            'PA' => 'USD',
            'CU' => 'CUP',
            'DO' => 'DOP',
            'GT' => 'GTQ',
            
            // Africa
            'ZA' => 'ZAR',
            'EG' => 'EGP',
            'NG' => 'NGN',
            'KE' => 'KES',
            'ET' => 'ETB',
            'GH' => 'GHS',
            'MA' => 'MAD',
            'TN' => 'TND',
            'DZ' => 'DZD',
            'UG' => 'UGX',
            'TZ' => 'TZS',
            'SN' => 'XOF',
            'ZW' => 'ZWL',
            'CM' => 'XAF',
            
            // Oceania
            'AU' => 'AUD',
            'NZ' => 'NZD',
            'FJ' => 'FJD',
            'PG' => 'PGK',
            'SB' => 'SBD',
            'VU' => 'VUV',
            'NC' => 'XPF',
            'PF' => 'XPF',
            'WS' => 'WST',
            'TO' => 'TOP',
        ];

        return $currencies[$countryCode] ?? 'USD';
    }
}