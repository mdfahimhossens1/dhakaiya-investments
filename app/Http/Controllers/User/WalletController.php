<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class WalletController extends Controller
{
    /**
     * Country to Currency Configuration
     *
     * Local currency is selected according to user's registration country.
     */
    private function getCountryCurrency(string $country): array
    {
        $currencies = [

            'BD' => ['code' => 'BDT', 'name' => 'Bangladeshi Taka', 'symbol' => '৳'],
            'IN' => ['code' => 'INR', 'name' => 'Indian Rupee', 'symbol' => '₹'],
            'US' => ['code' => 'USD', 'name' => 'United States Dollar', 'symbol' => '$'],
            'GB' => ['code' => 'GBP', 'name' => 'British Pound', 'symbol' => '£'],
            'CA' => ['code' => 'CAD', 'name' => 'Canadian Dollar', 'symbol' => 'C$'],
            'AU' => ['code' => 'AUD', 'name' => 'Australian Dollar', 'symbol' => 'A$'],
            'NZ' => ['code' => 'NZD', 'name' => 'New Zealand Dollar', 'symbol' => 'NZ$'],
            'SG' => ['code' => 'SGD', 'name' => 'Singapore Dollar', 'symbol' => 'S$'],
            'MY' => ['code' => 'MYR', 'name' => 'Malaysian Ringgit', 'symbol' => 'RM'],
            'PK' => ['code' => 'PKR', 'name' => 'Pakistani Rupee', 'symbol' => '₨'],
            'LK' => ['code' => 'LKR', 'name' => 'Sri Lankan Rupee', 'symbol' => 'Rs'],
            'NP' => ['code' => 'NPR', 'name' => 'Nepalese Rupee', 'symbol' => 'रू'],

            'SA' => ['code' => 'SAR', 'name' => 'Saudi Riyal', 'symbol' => '﷼'],
            'AE' => ['code' => 'AED', 'name' => 'UAE Dirham', 'symbol' => 'د.إ'],
            'QA' => ['code' => 'QAR', 'name' => 'Qatari Riyal', 'symbol' => 'ر.ق'],
            'KW' => ['code' => 'KWD', 'name' => 'Kuwaiti Dinar', 'symbol' => 'د.ك'],
            'BH' => ['code' => 'BHD', 'name' => 'Bahraini Dinar', 'symbol' => '.د.ب'],
            'OM' => ['code' => 'OMR', 'name' => 'Omani Rial', 'symbol' => 'ر.ع.'],
            'JO' => ['code' => 'JOD', 'name' => 'Jordanian Dinar', 'symbol' => 'د.ا'],
            'EG' => ['code' => 'EGP', 'name' => 'Egyptian Pound', 'symbol' => 'E£'],

            'DE' => ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€'],
            'FR' => ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€'],
            'IT' => ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€'],
            'ES' => ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€'],
            'NL' => ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€'],
            'BE' => ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€'],
            'PT' => ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€'],
            'IE' => ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€'],
            'AT' => ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€'],
            'GR' => ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€'],

            'CH' => ['code' => 'CHF', 'name' => 'Swiss Franc', 'symbol' => 'CHF'],
            'SE' => ['code' => 'SEK', 'name' => 'Swedish Krona', 'symbol' => 'kr'],
            'NO' => ['code' => 'NOK', 'name' => 'Norwegian Krone', 'symbol' => 'kr'],
            'DK' => ['code' => 'DKK', 'name' => 'Danish Krone', 'symbol' => 'kr'],
            'PL' => ['code' => 'PLN', 'name' => 'Polish Zloty', 'symbol' => 'zł'],
            'CZ' => ['code' => 'CZK', 'name' => 'Czech Koruna', 'symbol' => 'Kč'],
            'TR' => ['code' => 'TRY', 'name' => 'Turkish Lira', 'symbol' => '₺'],
            'RU' => ['code' => 'RUB', 'name' => 'Russian Ruble', 'symbol' => '₽'],
            'UA' => ['code' => 'UAH', 'name' => 'Ukrainian Hryvnia', 'symbol' => '₴'],

            'JP' => ['code' => 'JPY', 'name' => 'Japanese Yen', 'symbol' => '¥'],
            'CN' => ['code' => 'CNY', 'name' => 'Chinese Yuan', 'symbol' => '¥'],
            'HK' => ['code' => 'HKD', 'name' => 'Hong Kong Dollar', 'symbol' => 'HK$'],
            'KR' => ['code' => 'KRW', 'name' => 'South Korean Won', 'symbol' => '₩'],
            'TH' => ['code' => 'THB', 'name' => 'Thai Baht', 'symbol' => '฿'],
            'ID' => ['code' => 'IDR', 'name' => 'Indonesian Rupiah', 'symbol' => 'Rp'],
            'PH' => ['code' => 'PHP', 'name' => 'Philippine Peso', 'symbol' => '₱'],
            'VN' => ['code' => 'VND', 'name' => 'Vietnamese Dong', 'symbol' => '₫'],

            'BR' => ['code' => 'BRL', 'name' => 'Brazilian Real', 'symbol' => 'R$'],
            'MX' => ['code' => 'MXN', 'name' => 'Mexican Peso', 'symbol' => 'Mex$'],
            'ZA' => ['code' => 'ZAR', 'name' => 'South African Rand', 'symbol' => 'R'],
            'NG' => ['code' => 'NGN', 'name' => 'Nigerian Naira', 'symbol' => '₦'],
            'KE' => ['code' => 'KES', 'name' => 'Kenyan Shilling', 'symbol' => 'KSh'],
            'GH' => ['code' => 'GHS', 'name' => 'Ghanaian Cedi', 'symbol' => '₵'],
        ];

        return $currencies[$country] ?? [
            'code' => 'USD',
            'name' => 'United States Dollar',
            'symbol' => '$',
        ];
    }

    /**
     * Generate country flag emoji from ISO country code.
     */
    private function getCountryFlag(string $country): string
    {
        $country = strtoupper($country);

        if (!preg_match('/^[A-Z]{2}$/', $country)) {
            return '🌍';
        }

        $flag = '';

        foreach (str_split($country) as $character) {
            $flag .= mb_chr(
                127397 + ord($character),
                'UTF-8'
            );
        }

        return $flag;
    }

    /**
     * Wallet Overview
     */
    public function index()
    {
        $user = auth()->user();

        $country = strtoupper($user->country ?? 'BD');

        $localCurrency = $this->getCountryCurrency($country);

        $localWallet = [
            'name' => $localCurrency['code'] . ' Wallet',
            'currency' => $localCurrency['code'],
            'symbol' => $localCurrency['symbol'],
            'description' => $localCurrency['name'] . ' Wallet',
            'status' => 'Not Activated',
            'icon' => $this->getCountryFlag($country),
        ];

        $wallets = [$localWallet];

        // Add USD only when the local currency is not already USD.
        if ($localCurrency['code'] !== 'USD') {
            $wallets[] = [
                'name' => 'USD Wallet',
                'currency' => 'USD',
                'symbol' => '$',
                'description' => 'United States Dollar Wallet',
                'status' => 'Not Activated',
                'icon' => '🇺🇸',
            ];
        }

        $summary = [
            'total_wallets' => count($wallets),
            'active_wallets' => 0,
            'total_deposits' => null,
            'total_withdrawals' => null,
            'total_transactions' => null,
        ];

        return view('user.wallet.index', compact(
            'user',
            'wallets',
            'summary'
        ));
    }

    /**
     * Withdraw Page
     */
    public function withdraw()
    {
        $user = auth()->user();

        $withdrawalSummary = [
            'available_balance' => 0,
            'minimum_withdrawal' => 0,
            'pending_withdrawals' => 0,
            'completed_withdrawals' => 0,
        ];

        $withdrawalMethods = collect([]);

        return view('user.withdraw.index', compact(
            'user',
            'withdrawalSummary',
            'withdrawalMethods'
        ));
    }

    /**
     * Withdrawal Methods
     */
    public function withdrawalMethods()
    {
        $user = auth()->user();

        $withdrawalMethods = [
            [
                'name' => 'Bank Transfer',
                'description' => 'Bank account withdrawal method',
                'icon' => 'fa-solid fa-building-columns',
                'status' => 'Not Activated',
            ],
            [
                'name' => 'Digital Payment',
                'description' => 'Digital payment method',
                'icon' => 'fa-solid fa-mobile-screen-button',
                'status' => 'Not Activated',
            ],
            [
                'name' => 'E-Wallet',
                'description' => 'Electronic wallet method',
                'icon' => 'fa-solid fa-wallet',
                'status' => 'Not Activated',
            ],
        ];

        return view('user.withdrawal-methods.index', compact(
            'user',
            'withdrawalMethods'
        ));
    }
}