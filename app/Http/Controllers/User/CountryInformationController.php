<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class CountryInformationController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $countryData = [
            'BD' => [
                'name' => 'Bangladesh',
                'code' => 'BD',
                'calling_code' => '+880',
                'currency' => 'Bangladeshi Taka',
                'currency_code' => 'BDT',
                'currency_symbol' => '৳',
                'timezone' => 'Asia/Dhaka',
                'timezone_label' => 'Bangladesh Standard Time (UTC+06:00)',
                'date_format' => 'DD-MM-YYYY',
            ],

            'IN' => [
                'name' => 'India',
                'code' => 'IN',
                'calling_code' => '+91',
                'currency' => 'Indian Rupee',
                'currency_code' => 'INR',
                'currency_symbol' => '₹',
                'timezone' => 'Asia/Kolkata',
                'timezone_label' => 'India Standard Time (UTC+05:30)',
                'date_format' => 'DD-MM-YYYY',
            ],

            'US' => [
                'name' => 'United States',
                'code' => 'US',
                'calling_code' => '+1',
                'currency' => 'US Dollar',
                'currency_code' => 'USD',
                'currency_symbol' => '$',
                'timezone' => 'America/New_York',
                'timezone_label' => 'Eastern Time (UTC−05:00 / UTC−04:00)',
                'date_format' => 'MM-DD-YYYY',
            ],

            'GB' => [
                'name' => 'United Kingdom',
                'code' => 'GB',
                'calling_code' => '+44',
                'currency' => 'British Pound',
                'currency_code' => 'GBP',
                'currency_symbol' => '£',
                'timezone' => 'Europe/London',
                'timezone_label' => 'UK Time (UTC+00:00 / UTC+01:00)',
                'date_format' => 'DD-MM-YYYY',
            ],

            'SA' => [
                'name' => 'Saudi Arabia',
                'code' => 'SA',
                'calling_code' => '+966',
                'currency' => 'Saudi Riyal',
                'currency_code' => 'SAR',
                'currency_symbol' => '﷼',
                'timezone' => 'Asia/Riyadh',
                'timezone_label' => 'Arabia Standard Time (UTC+03:00)',
                'date_format' => 'DD-MM-YYYY',
            ],

            'AE' => [
                'name' => 'United Arab Emirates',
                'code' => 'AE',
                'calling_code' => '+971',
                'currency' => 'UAE Dirham',
                'currency_code' => 'AED',
                'currency_symbol' => 'د.إ',
                'timezone' => 'Asia/Dubai',
                'timezone_label' => 'Gulf Standard Time (UTC+04:00)',
                'date_format' => 'DD-MM-YYYY',
            ],
        ];

        $country = $countryData[$user->country] ?? null;

        return view('user.profile.country-information', compact(
            'user',
            'country'
        ));
    }
}