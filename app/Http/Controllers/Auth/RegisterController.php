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

        // Get phone codes with flags
        $phoneCodes = $this->getPhoneCodeList();

        // Get referral code from session or query parameter
        $referralCode = session('referral_code', $request->get('ref'));

        return view('auth.register', [
            'locationData' => $locationData,
            'countries' => $countries,
            'phoneCodes' => $phoneCodes,
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
     * Get complete list of all countries (alphabetically sorted)
     */
    private function getCountryList()
    {
        $countries = [
            'AF' => 'Afghanistan',
            'AL' => 'Albania',
            'DZ' => 'Algeria',
            'AD' => 'Andorra',
            'AO' => 'Angola',
            'AG' => 'Antigua and Barbuda',
            'AR' => 'Argentina',
            'AM' => 'Armenia',
            'AU' => 'Australia',
            'AT' => 'Austria',
            'AZ' => 'Azerbaijan',
            'BS' => 'Bahamas',
            'BH' => 'Bahrain',
            'BD' => 'Bangladesh',
            'BB' => 'Barbados',
            'BY' => 'Belarus',
            'BE' => 'Belgium',
            'BZ' => 'Belize',
            'BJ' => 'Benin',
            'BT' => 'Bhutan',
            'BO' => 'Bolivia',
            'BA' => 'Bosnia and Herzegovina',
            'BW' => 'Botswana',
            'BR' => 'Brazil',
            'BN' => 'Brunei',
            'BG' => 'Bulgaria',
            'BF' => 'Burkina Faso',
            'BI' => 'Burundi',
            'KH' => 'Cambodia',
            'CM' => 'Cameroon',
            'CA' => 'Canada',
            'CV' => 'Cape Verde',
            'CF' => 'Central African Republic',
            'TD' => 'Chad',
            'CL' => 'Chile',
            'CN' => 'China',
            'CO' => 'Colombia',
            'KM' => 'Comoros',
            'CG' => 'Congo',
            'CD' => 'Congo (DRC)',
            'CR' => 'Costa Rica',
            'HR' => 'Croatia',
            'CU' => 'Cuba',
            'CY' => 'Cyprus',
            'CZ' => 'Czech Republic',
            'DK' => 'Denmark',
            'DJ' => 'Djibouti',
            'DM' => 'Dominica',
            'DO' => 'Dominican Republic',
            'EC' => 'Ecuador',
            'EG' => 'Egypt',
            'SV' => 'El Salvador',
            'GQ' => 'Equatorial Guinea',
            'ER' => 'Eritrea',
            'EE' => 'Estonia',
            'ET' => 'Ethiopia',
            'FJ' => 'Fiji',
            'FI' => 'Finland',
            'FR' => 'France',
            'GA' => 'Gabon',
            'GM' => 'Gambia',
            'GE' => 'Georgia',
            'DE' => 'Germany',
            'GH' => 'Ghana',
            'GR' => 'Greece',
            'GD' => 'Grenada',
            'GT' => 'Guatemala',
            'GN' => 'Guinea',
            'GW' => 'Guinea-Bissau',
            'GY' => 'Guyana',
            'HT' => 'Haiti',
            'HN' => 'Honduras',
            'HK' => 'Hong Kong',
            'HU' => 'Hungary',
            'IS' => 'Iceland',
            'IN' => 'India',
            'ID' => 'Indonesia',
            'IR' => 'Iran',
            'IQ' => 'Iraq',
            'IE' => 'Ireland',
            'IL' => 'Israel',
            'IT' => 'Italy',
            'CI' => 'Ivory Coast',
            'JM' => 'Jamaica',
            'JP' => 'Japan',
            'JO' => 'Jordan',
            'KZ' => 'Kazakhstan',
            'KE' => 'Kenya',
            'KI' => 'Kiribati',
            'KP' => 'Korea (North)',
            'KR' => 'Korea (South)',
            'KW' => 'Kuwait',
            'KG' => 'Kyrgyzstan',
            'LA' => 'Laos',
            'LV' => 'Latvia',
            'LB' => 'Lebanon',
            'LS' => 'Lesotho',
            'LR' => 'Liberia',
            'LY' => 'Libya',
            'LI' => 'Liechtenstein',
            'LT' => 'Lithuania',
            'LU' => 'Luxembourg',
            'MO' => 'Macau',
            'MK' => 'Macedonia',
            'MG' => 'Madagascar',
            'MW' => 'Malawi',
            'MY' => 'Malaysia',
            'MV' => 'Maldives',
            'ML' => 'Mali',
            'MT' => 'Malta',
            'MH' => 'Marshall Islands',
            'MR' => 'Mauritania',
            'MU' => 'Mauritius',
            'MX' => 'Mexico',
            'FM' => 'Micronesia',
            'MD' => 'Moldova',
            'MC' => 'Monaco',
            'MN' => 'Mongolia',
            'ME' => 'Montenegro',
            'MA' => 'Morocco',
            'MZ' => 'Mozambique',
            'MM' => 'Myanmar',
            'NA' => 'Namibia',
            'NR' => 'Nauru',
            'NP' => 'Nepal',
            'NL' => 'Netherlands',
            'NZ' => 'New Zealand',
            'NI' => 'Nicaragua',
            'NE' => 'Niger',
            'NG' => 'Nigeria',
            'NO' => 'Norway',
            'OM' => 'Oman',
            'PK' => 'Pakistan',
            'PW' => 'Palau',
            'PS' => 'Palestine',
            'PA' => 'Panama',
            'PG' => 'Papua New Guinea',
            'PY' => 'Paraguay',
            'PE' => 'Peru',
            'PH' => 'Philippines',
            'PL' => 'Poland',
            'PT' => 'Portugal',
            'QA' => 'Qatar',
            'RO' => 'Romania',
            'RU' => 'Russia',
            'RW' => 'Rwanda',
            'KN' => 'Saint Kitts and Nevis',
            'LC' => 'Saint Lucia',
            'VC' => 'Saint Vincent and the Grenadines',
            'WS' => 'Samoa',
            'SM' => 'San Marino',
            'ST' => 'Sao Tome and Principe',
            'SA' => 'Saudi Arabia',
            'SN' => 'Senegal',
            'RS' => 'Serbia',
            'SC' => 'Seychelles',
            'SL' => 'Sierra Leone',
            'SG' => 'Singapore',
            'SK' => 'Slovakia',
            'SI' => 'Slovenia',
            'SB' => 'Solomon Islands',
            'SO' => 'Somalia',
            'ZA' => 'South Africa',
            'SS' => 'South Sudan',
            'ES' => 'Spain',
            'LK' => 'Sri Lanka',
            'SD' => 'Sudan',
            'SR' => 'Suriname',
            'SZ' => 'Swaziland',
            'SE' => 'Sweden',
            'CH' => 'Switzerland',
            'SY' => 'Syria',
            'TW' => 'Taiwan',
            'TJ' => 'Tajikistan',
            'TZ' => 'Tanzania',
            'TH' => 'Thailand',
            'TL' => 'Timor-Leste',
            'TG' => 'Togo',
            'TO' => 'Tonga',
            'TT' => 'Trinidad and Tobago',
            'TN' => 'Tunisia',
            'TR' => 'Turkey',
            'TM' => 'Turkmenistan',
            'TV' => 'Tuvalu',
            'UG' => 'Uganda',
            'UA' => 'Ukraine',
            'AE' => 'United Arab Emirates',
            'GB' => 'United Kingdom',
            'US' => 'United States',
            'UY' => 'Uruguay',
            'UZ' => 'Uzbekistan',
            'VU' => 'Vanuatu',
            'VA' => 'Vatican City',
            'VE' => 'Venezuela',
            'VN' => 'Vietnam',
            'YE' => 'Yemen',
            'ZM' => 'Zambia',
            'ZW' => 'Zimbabwe',
        ];

        // Sort alphabetically by country name
        asort($countries);

        return $countries;
    }

    /**
     * Get complete list of all country phone codes with flags and country names
     */
    private function getPhoneCodeList()
    {
        $phoneCodes = [
            'AF' => ['code' => '+93', 'flag' => '🇦🇫', 'name' => 'Afghanistan'],
            'AL' => ['code' => '+355', 'flag' => '🇦🇱', 'name' => 'Albania'],
            'DZ' => ['code' => '+213', 'flag' => '🇩🇿', 'name' => 'Algeria'],
            'AD' => ['code' => '+376', 'flag' => '🇦🇩', 'name' => 'Andorra'],
            'AO' => ['code' => '+244', 'flag' => '🇦🇴', 'name' => 'Angola'],
            'AG' => ['code' => '+1-268', 'flag' => '🇦🇬', 'name' => 'Antigua and Barbuda'],
            'AR' => ['code' => '+54', 'flag' => '🇦🇷', 'name' => 'Argentina'],
            'AM' => ['code' => '+374', 'flag' => '🇦🇲', 'name' => 'Armenia'],
            'AU' => ['code' => '+61', 'flag' => '🇦🇺', 'name' => 'Australia'],
            'AT' => ['code' => '+43', 'flag' => '🇦🇹', 'name' => 'Austria'],
            'AZ' => ['code' => '+994', 'flag' => '🇦🇿', 'name' => 'Azerbaijan'],
            'BS' => ['code' => '+1-242', 'flag' => '🇧🇸', 'name' => 'Bahamas'],
            'BH' => ['code' => '+973', 'flag' => '🇧🇭', 'name' => 'Bahrain'],
            'BD' => ['code' => '+880', 'flag' => '🇧🇩', 'name' => 'Bangladesh'],
            'BB' => ['code' => '+1-246', 'flag' => '🇧🇧', 'name' => 'Barbados'],
            'BY' => ['code' => '+375', 'flag' => '🇧🇾', 'name' => 'Belarus'],
            'BE' => ['code' => '+32', 'flag' => '🇧🇪', 'name' => 'Belgium'],
            'BZ' => ['code' => '+501', 'flag' => '🇧🇿', 'name' => 'Belize'],
            'BJ' => ['code' => '+229', 'flag' => '🇧🇯', 'name' => 'Benin'],
            'BT' => ['code' => '+975', 'flag' => '🇧🇹', 'name' => 'Bhutan'],
            'BO' => ['code' => '+591', 'flag' => '🇧🇴', 'name' => 'Bolivia'],
            'BA' => ['code' => '+387', 'flag' => '🇧🇦', 'name' => 'Bosnia and Herzegovina'],
            'BW' => ['code' => '+267', 'flag' => '🇧🇼', 'name' => 'Botswana'],
            'BR' => ['code' => '+55', 'flag' => '🇧🇷', 'name' => 'Brazil'],
            'BN' => ['code' => '+673', 'flag' => '🇧🇳', 'name' => 'Brunei'],
            'BG' => ['code' => '+359', 'flag' => '🇧🇬', 'name' => 'Bulgaria'],
            'BF' => ['code' => '+226', 'flag' => '🇧🇫', 'name' => 'Burkina Faso'],
            'BI' => ['code' => '+257', 'flag' => '🇧🇮', 'name' => 'Burundi'],
            'KH' => ['code' => '+855', 'flag' => '🇰🇭', 'name' => 'Cambodia'],
            'CM' => ['code' => '+237', 'flag' => '🇨🇲', 'name' => 'Cameroon'],
            'CA' => ['code' => '+1', 'flag' => '🇨🇦', 'name' => 'Canada'],
            'CV' => ['code' => '+238', 'flag' => '🇨🇻', 'name' => 'Cape Verde'],
            'CF' => ['code' => '+236', 'flag' => '🇨🇫', 'name' => 'Central African Republic'],
            'TD' => ['code' => '+235', 'flag' => '🇹🇩', 'name' => 'Chad'],
            'CL' => ['code' => '+56', 'flag' => '🇨🇱', 'name' => 'Chile'],
            'CN' => ['code' => '+86', 'flag' => '🇨🇳', 'name' => 'China'],
            'CO' => ['code' => '+57', 'flag' => '🇨🇴', 'name' => 'Colombia'],
            'KM' => ['code' => '+269', 'flag' => '🇰🇲', 'name' => 'Comoros'],
            'CG' => ['code' => '+242', 'flag' => '🇨🇬', 'name' => 'Congo'],
            'CD' => ['code' => '+243', 'flag' => '🇨🇩', 'name' => 'Congo (DRC)'],
            'CR' => ['code' => '+506', 'flag' => '🇨🇷', 'name' => 'Costa Rica'],
            'HR' => ['code' => '+385', 'flag' => '🇭🇷', 'name' => 'Croatia'],
            'CU' => ['code' => '+53', 'flag' => '🇨🇺', 'name' => 'Cuba'],
            'CY' => ['code' => '+357', 'flag' => '🇨🇾', 'name' => 'Cyprus'],
            'CZ' => ['code' => '+420', 'flag' => '🇨🇿', 'name' => 'Czech Republic'],
            'DK' => ['code' => '+45', 'flag' => '🇩🇰', 'name' => 'Denmark'],
            'DJ' => ['code' => '+253', 'flag' => '🇩🇯', 'name' => 'Djibouti'],
            'DM' => ['code' => '+1-767', 'flag' => '🇩🇲', 'name' => 'Dominica'],
            'DO' => ['code' => '+1-809', 'flag' => '🇩🇴', 'name' => 'Dominican Republic'],
            'EC' => ['code' => '+593', 'flag' => '🇪🇨', 'name' => 'Ecuador'],
            'EG' => ['code' => '+20', 'flag' => '🇪🇬', 'name' => 'Egypt'],
            'SV' => ['code' => '+503', 'flag' => '🇸🇻', 'name' => 'El Salvador'],
            'GQ' => ['code' => '+240', 'flag' => '🇬🇶', 'name' => 'Equatorial Guinea'],
            'ER' => ['code' => '+291', 'flag' => '🇪🇷', 'name' => 'Eritrea'],
            'EE' => ['code' => '+372', 'flag' => '🇪🇪', 'name' => 'Estonia'],
            'ET' => ['code' => '+251', 'flag' => '🇪🇹', 'name' => 'Ethiopia'],
            'FJ' => ['code' => '+679', 'flag' => '🇫🇯', 'name' => 'Fiji'],
            'FI' => ['code' => '+358', 'flag' => '🇫🇮', 'name' => 'Finland'],
            'FR' => ['code' => '+33', 'flag' => '🇫🇷', 'name' => 'France'],
            'GA' => ['code' => '+241', 'flag' => '🇬🇦', 'name' => 'Gabon'],
            'GM' => ['code' => '+220', 'flag' => '🇬🇲', 'name' => 'Gambia'],
            'GE' => ['code' => '+995', 'flag' => '🇬🇪', 'name' => 'Georgia'],
            'DE' => ['code' => '+49', 'flag' => '🇩🇪', 'name' => 'Germany'],
            'GH' => ['code' => '+233', 'flag' => '🇬🇭', 'name' => 'Ghana'],
            'GR' => ['code' => '+30', 'flag' => '🇬🇷', 'name' => 'Greece'],
            'GD' => ['code' => '+1-473', 'flag' => '🇬🇩', 'name' => 'Grenada'],
            'GT' => ['code' => '+502', 'flag' => '🇬🇹', 'name' => 'Guatemala'],
            'GN' => ['code' => '+224', 'flag' => '🇬🇳', 'name' => 'Guinea'],
            'GW' => ['code' => '+245', 'flag' => '🇬🇼', 'name' => 'Guinea-Bissau'],
            'GY' => ['code' => '+592', 'flag' => '🇬🇾', 'name' => 'Guyana'],
            'HT' => ['code' => '+509', 'flag' => '🇭🇹', 'name' => 'Haiti'],
            'HN' => ['code' => '+504', 'flag' => '🇭🇳', 'name' => 'Honduras'],
            'HU' => ['code' => '+36', 'flag' => '🇭🇺', 'name' => 'Hungary'],
            'IS' => ['code' => '+354', 'flag' => '🇮🇸', 'name' => 'Iceland'],
            'IN' => ['code' => '+91', 'flag' => '🇮🇳', 'name' => 'India'],
            'ID' => ['code' => '+62', 'flag' => '🇮🇩', 'name' => 'Indonesia'],
            'IR' => ['code' => '+98', 'flag' => '🇮🇷', 'name' => 'Iran'],
            'IQ' => ['code' => '+964', 'flag' => '🇮🇶', 'name' => 'Iraq'],
            'IE' => ['code' => '+353', 'flag' => '🇮🇪', 'name' => 'Ireland'],
            'IL' => ['code' => '+972', 'flag' => '🇮🇱', 'name' => 'Israel'],
            'IT' => ['code' => '+39', 'flag' => '🇮🇹', 'name' => 'Italy'],
            'CI' => ['code' => '+225', 'flag' => '🇨🇮', 'name' => 'Ivory Coast'],
            'JM' => ['code' => '+1-876', 'flag' => '🇯🇲', 'name' => 'Jamaica'],
            'JP' => ['code' => '+81', 'flag' => '🇯🇵', 'name' => 'Japan'],
            'JO' => ['code' => '+962', 'flag' => '🇯🇴', 'name' => 'Jordan'],
            'KZ' => ['code' => '+7', 'flag' => '🇰🇿', 'name' => 'Kazakhstan'],
            'KE' => ['code' => '+254', 'flag' => '🇰🇪', 'name' => 'Kenya'],
            'KI' => ['code' => '+686', 'flag' => '🇰🇮', 'name' => 'Kiribati'],
            'KP' => ['code' => '+850', 'flag' => '🇰🇵', 'name' => 'North Korea'],
            'KR' => ['code' => '+82', 'flag' => '🇰🇷', 'name' => 'South Korea'],
            'KW' => ['code' => '+965', 'flag' => '🇰🇼', 'name' => 'Kuwait'],
            'KG' => ['code' => '+996', 'flag' => '🇰🇬', 'name' => 'Kyrgyzstan'],
            'LA' => ['code' => '+856', 'flag' => '🇱🇦', 'name' => 'Laos'],
            'LV' => ['code' => '+371', 'flag' => '🇱🇻', 'name' => 'Latvia'],
            'LB' => ['code' => '+961', 'flag' => '🇱🇧', 'name' => 'Lebanon'],
            'LS' => ['code' => '+266', 'flag' => '🇱🇸', 'name' => 'Lesotho'],
            'LR' => ['code' => '+231', 'flag' => '🇱🇷', 'name' => 'Liberia'],
            'LY' => ['code' => '+218', 'flag' => '🇱🇾', 'name' => 'Libya'],
            'LI' => ['code' => '+423', 'flag' => '🇱🇮', 'name' => 'Liechtenstein'],
            'LT' => ['code' => '+370', 'flag' => '🇱🇹', 'name' => 'Lithuania'],
            'LU' => ['code' => '+352', 'flag' => '🇱🇺', 'name' => 'Luxembourg'],
            'MK' => ['code' => '+389', 'flag' => '🇲🇰', 'name' => 'North Macedonia'],
            'MG' => ['code' => '+261', 'flag' => '🇲🇬', 'name' => 'Madagascar'],
            'MW' => ['code' => '+265', 'flag' => '🇲🇼', 'name' => 'Malawi'],
            'MY' => ['code' => '+60', 'flag' => '🇲🇾', 'name' => 'Malaysia'],
            'MV' => ['code' => '+960', 'flag' => '🇲🇻', 'name' => 'Maldives'],
            'ML' => ['code' => '+223', 'flag' => '🇲🇱', 'name' => 'Mali'],
            'MT' => ['code' => '+356', 'flag' => '🇲🇹', 'name' => 'Malta'],
            'MH' => ['code' => '+692', 'flag' => '🇲🇭', 'name' => 'Marshall Islands'],
            'MR' => ['code' => '+222', 'flag' => '🇲🇷', 'name' => 'Mauritania'],
            'MU' => ['code' => '+230', 'flag' => '🇲🇺', 'name' => 'Mauritius'],
            'MX' => ['code' => '+52', 'flag' => '🇲🇽', 'name' => 'Mexico'],
            'FM' => ['code' => '+691', 'flag' => '🇫🇲', 'name' => 'Micronesia'],
            'MD' => ['code' => '+373', 'flag' => '🇲🇩', 'name' => 'Moldova'],
            'MC' => ['code' => '+377', 'flag' => '🇲🇨', 'name' => 'Monaco'],
            'MN' => ['code' => '+976', 'flag' => '🇲🇳', 'name' => 'Mongolia'],
            'ME' => ['code' => '+382', 'flag' => '🇲🇪', 'name' => 'Montenegro'],
            'MA' => ['code' => '+212', 'flag' => '🇲🇦', 'name' => 'Morocco'],
            'MZ' => ['code' => '+258', 'flag' => '🇲🇿', 'name' => 'Mozambique'],
            'MM' => ['code' => '+95', 'flag' => '🇲🇲', 'name' => 'Myanmar'],
            'NA' => ['code' => '+264', 'flag' => '🇳🇦', 'name' => 'Namibia'],
            'NR' => ['code' => '+674', 'flag' => '🇳🇷', 'name' => 'Nauru'],
            'NP' => ['code' => '+977', 'flag' => '🇳🇵', 'name' => 'Nepal'],
            'NL' => ['code' => '+31', 'flag' => '🇳🇱', 'name' => 'Netherlands'],
            'NZ' => ['code' => '+64', 'flag' => '🇳🇿', 'name' => 'New Zealand'],
            'NI' => ['code' => '+505', 'flag' => '🇳🇮', 'name' => 'Nicaragua'],
            'NE' => ['code' => '+227', 'flag' => '🇳🇪', 'name' => 'Niger'],
            'NG' => ['code' => '+234', 'flag' => '🇳🇬', 'name' => 'Nigeria'],
            'NO' => ['code' => '+47', 'flag' => '🇳🇴', 'name' => 'Norway'],
            'OM' => ['code' => '+968', 'flag' => '🇴🇲', 'name' => 'Oman'],
            'PK' => ['code' => '+92', 'flag' => '🇵🇰', 'name' => 'Pakistan'],
            'PW' => ['code' => '+680', 'flag' => '🇵🇼', 'name' => 'Palau'],
            'PS' => ['code' => '+970', 'flag' => '🇵🇸', 'name' => 'Palestine'],
            'PA' => ['code' => '+507', 'flag' => '🇵🇦', 'name' => 'Panama'],
            'PG' => ['code' => '+675', 'flag' => '🇵🇬', 'name' => 'Papua New Guinea'],
            'PY' => ['code' => '+595', 'flag' => '🇵🇾', 'name' => 'Paraguay'],
            'PE' => ['code' => '+51', 'flag' => '🇵🇪', 'name' => 'Peru'],
            'PH' => ['code' => '+63', 'flag' => '🇵🇭', 'name' => 'Philippines'],
            'PL' => ['code' => '+48', 'flag' => '🇵🇱', 'name' => 'Poland'],
            'PT' => ['code' => '+351', 'flag' => '🇵🇹', 'name' => 'Portugal'],
            'QA' => ['code' => '+974', 'flag' => '🇶🇦', 'name' => 'Qatar'],
            'RO' => ['code' => '+40', 'flag' => '🇷🇴', 'name' => 'Romania'],
            'RU' => ['code' => '+7', 'flag' => '🇷🇺', 'name' => 'Russia'],
            'RW' => ['code' => '+250', 'flag' => '🇷🇼', 'name' => 'Rwanda'],
            'KN' => ['code' => '+1-869', 'flag' => '🇰🇳', 'name' => 'Saint Kitts and Nevis'],
            'LC' => ['code' => '+1-758', 'flag' => '🇱🇨', 'name' => 'Saint Lucia'],
            'VC' => ['code' => '+1-784', 'flag' => '🇻🇨', 'name' => 'Saint Vincent and the Grenadines'],
            'WS' => ['code' => '+685', 'flag' => '🇼🇸', 'name' => 'Samoa'],
            'SM' => ['code' => '+378', 'flag' => '🇸🇲', 'name' => 'San Marino'],
            'ST' => ['code' => '+239', 'flag' => '🇸🇹', 'name' => 'Sao Tome and Principe'],
            'SA' => ['code' => '+966', 'flag' => '🇸🇦', 'name' => 'Saudi Arabia'],
            'SN' => ['code' => '+221', 'flag' => '🇸🇳', 'name' => 'Senegal'],
            'RS' => ['code' => '+381', 'flag' => '🇷🇸', 'name' => 'Serbia'],
            'SC' => ['code' => '+248', 'flag' => '🇸🇨', 'name' => 'Seychelles'],
            'SL' => ['code' => '+232', 'flag' => '🇸🇱', 'name' => 'Sierra Leone'],
            'SG' => ['code' => '+65', 'flag' => '🇸🇬', 'name' => 'Singapore'],
            'SK' => ['code' => '+421', 'flag' => '🇸🇰', 'name' => 'Slovakia'],
            'SI' => ['code' => '+386', 'flag' => '🇸🇮', 'name' => 'Slovenia'],
            'SB' => ['code' => '+677', 'flag' => '🇸🇧', 'name' => 'Solomon Islands'],
            'SO' => ['code' => '+252', 'flag' => '🇸🇴', 'name' => 'Somalia'],
            'ZA' => ['code' => '+27', 'flag' => '🇿🇦', 'name' => 'South Africa'],
            'SS' => ['code' => '+211', 'flag' => '🇸🇸', 'name' => 'South Sudan'],
            'ES' => ['code' => '+34', 'flag' => '🇪🇸', 'name' => 'Spain'],
            'LK' => ['code' => '+94', 'flag' => '🇱🇰', 'name' => 'Sri Lanka'],
            'SD' => ['code' => '+249', 'flag' => '🇸🇩', 'name' => 'Sudan'],
            'SR' => ['code' => '+597', 'flag' => '🇸🇷', 'name' => 'Suriname'],
            'SE' => ['code' => '+46', 'flag' => '🇸🇪', 'name' => 'Sweden'],
            'CH' => ['code' => '+41', 'flag' => '🇨🇭', 'name' => 'Switzerland'],
            'SY' => ['code' => '+963', 'flag' => '🇸🇾', 'name' => 'Syria'],
            'TW' => ['code' => '+886', 'flag' => '🇹🇼', 'name' => 'Taiwan'],
            'TJ' => ['code' => '+992', 'flag' => '🇹🇯', 'name' => 'Tajikistan'],
            'TZ' => ['code' => '+255', 'flag' => '🇹🇿', 'name' => 'Tanzania'],
            'TH' => ['code' => '+66', 'flag' => '🇹🇭', 'name' => 'Thailand'],
            'TL' => ['code' => '+670', 'flag' => '🇹🇱', 'name' => 'Timor-Leste'],
            'TG' => ['code' => '+228', 'flag' => '🇹🇬', 'name' => 'Togo'],
            'TO' => ['code' => '+676', 'flag' => '🇹🇴', 'name' => 'Tonga'],
            'TT' => ['code' => '+1-868', 'flag' => '🇹🇹', 'name' => 'Trinidad and Tobago'],
            'TN' => ['code' => '+216', 'flag' => '🇹🇳', 'name' => 'Tunisia'],
            'TR' => ['code' => '+90', 'flag' => '🇹🇷', 'name' => 'Turkey'],
            'TM' => ['code' => '+993', 'flag' => '🇹🇲', 'name' => 'Turkmenistan'],
            'TV' => ['code' => '+688', 'flag' => '🇹🇻', 'name' => 'Tuvalu'],
            'UG' => ['code' => '+256', 'flag' => '🇺🇬', 'name' => 'Uganda'],
            'UA' => ['code' => '+380', 'flag' => '🇺🇦', 'name' => 'Ukraine'],
            'AE' => ['code' => '+971', 'flag' => '🇦🇪', 'name' => 'United Arab Emirates'],
            'GB' => ['code' => '+44', 'flag' => '🇬🇧', 'name' => 'United Kingdom'],
            'US' => ['code' => '+1', 'flag' => '🇺🇸', 'name' => 'United States'],
            'UY' => ['code' => '+598', 'flag' => '🇺🇾', 'name' => 'Uruguay'],
            'UZ' => ['code' => '+998', 'flag' => '🇺🇿', 'name' => 'Uzbekistan'],
            'VU' => ['code' => '+678', 'flag' => '🇻🇺', 'name' => 'Vanuatu'],
            'VA' => ['code' => '+379', 'flag' => '🇻🇦', 'name' => 'Vatican City'],
            'VE' => ['code' => '+58', 'flag' => '🇻🇪', 'name' => 'Venezuela'],
            'VN' => ['code' => '+84', 'flag' => '🇻🇳', 'name' => 'Vietnam'],
            'YE' => ['code' => '+967', 'flag' => '🇾🇪', 'name' => 'Yemen'],
            'ZM' => ['code' => '+260', 'flag' => '🇿🇲', 'name' => 'Zambia'],
            'ZW' => ['code' => '+263', 'flag' => '🇿🇼', 'name' => 'Zimbabwe'],
        ];

        return $phoneCodes;
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