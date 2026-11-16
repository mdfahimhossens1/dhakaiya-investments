<?php

namespace App\Support;

class Currency
{
    public static function map(): array
    {
        return [
            // এশিয়া
            'BD' => ['code' => 'BDT', 'name' => 'Bangladeshi Taka', 'symbol' => '৳'],
            'IN' => ['code' => 'INR', 'name' => 'Indian Rupee', 'symbol' => '₹'],
            'PK' => ['code' => 'PKR', 'name' => 'Pakistani Rupee', 'symbol' => '₨'],
            'LK' => ['code' => 'LKR', 'name' => 'Sri Lankan Rupee', 'symbol' => 'Rs'],
            'NP' => ['code' => 'NPR', 'name' => 'Nepalese Rupee', 'symbol' => '₨'],
            'BT' => ['code' => 'BTN', 'name' => 'Bhutanese Ngultrum', 'symbol' => 'Nu.'],
            'MV' => ['code' => 'MVR', 'name' => 'Maldivian Rufiyaa', 'symbol' => 'Rf'],
            'AF' => ['code' => 'AFN', 'name' => 'Afghan Afghani', 'symbol' => '؋'],
            'CN' => ['code' => 'CNY', 'name' => 'Chinese Yuan', 'symbol' => '¥'],
            'JP' => ['code' => 'JPY', 'name' => 'Japanese Yen', 'symbol' => '¥'],
            'KR' => ['code' => 'KRW', 'name' => 'South Korean Won', 'symbol' => '₩'],
            'KP' => ['code' => 'KPW', 'name' => 'North Korean Won', 'symbol' => '₩'],
            'SG' => ['code' => 'SGD', 'name' => 'Singapore Dollar', 'symbol' => 'S$'],
            'MY' => ['code' => 'MYR', 'name' => 'Malaysian Ringgit', 'symbol' => 'RM'],
            'TH' => ['code' => 'THB', 'name' => 'Thai Baht', 'symbol' => '฿'],
            'ID' => ['code' => 'IDR', 'name' => 'Indonesian Rupiah', 'symbol' => 'Rp'],
            'PH' => ['code' => 'PHP', 'name' => 'Philippine Peso', 'symbol' => '₱'],
            'VN' => ['code' => 'VND', 'name' => 'Vietnamese Dong', 'symbol' => '₫'],
            'MM' => ['code' => 'MMK', 'name' => 'Myanmar Kyat', 'symbol' => 'K'],
            'KH' => ['code' => 'KHR', 'name' => 'Cambodian Riel', 'symbol' => '៛'],
            'LA' => ['code' => 'LAK', 'name' => 'Lao Kip', 'symbol' => '₭'],
            'BN' => ['code' => 'BND', 'name' => 'Brunei Dollar', 'symbol' => 'B$'],
            'TL' => ['code' => 'USD', 'name' => 'United States Dollar', 'symbol' => '$'],
            'MN' => ['code' => 'MNT', 'name' => 'Mongolian Tugrik', 'symbol' => '₮'],
            'KZ' => ['code' => 'KZT', 'name' => 'Kazakhstani Tenge', 'symbol' => '₸'],
            'UZ' => ['code' => 'UZS', 'name' => 'Uzbekistani Som', 'symbol' => 'лв'],
            'KG' => ['code' => 'KGS', 'name' => 'Kyrgyzstani Som', 'symbol' => 'лв'],
            'TJ' => ['code' => 'TJS', 'name' => 'Tajikistani Somoni', 'symbol' => 'SM'],
            'TM' => ['code' => 'TMT', 'name' => 'Turkmenistani Manat', 'symbol' => 'm'],
            'AZ' => ['code' => 'AZN', 'name' => 'Azerbaijani Manat', 'symbol' => '₼'],
            'GE' => ['code' => 'GEL', 'name' => 'Georgian Lari', 'symbol' => '₾'],
            'AM' => ['code' => 'AMD', 'name' => 'Armenian Dram', 'symbol' => '֏'],

            // মধ্যপ্রাচ্য
            'AE' => ['code' => 'AED', 'name' => 'UAE Dirham', 'symbol' => 'د.إ'],
            'SA' => ['code' => 'SAR', 'name' => 'Saudi Riyal', 'symbol' => 'ر.س'],
            'QA' => ['code' => 'QAR', 'name' => 'Qatari Riyal', 'symbol' => 'ر.ق'],
            'KW' => ['code' => 'KWD', 'name' => 'Kuwaiti Dinar', 'symbol' => 'د.ك'],
            'BH' => ['code' => 'BHD', 'name' => 'Bahraini Dinar', 'symbol' => '.د.ب'],
            'OM' => ['code' => 'OMR', 'name' => 'Omani Rial', 'symbol' => 'ر.ع.'],
            'JO' => ['code' => 'JOD', 'name' => 'Jordanian Dinar', 'symbol' => 'د.ا'],
            'LB' => ['code' => 'LBP', 'name' => 'Lebanese Pound', 'symbol' => 'ل.ل'],
            'SY' => ['code' => 'SYP', 'name' => 'Syrian Pound', 'symbol' => '£S'],
            'IQ' => ['code' => 'IQD', 'name' => 'Iraqi Dinar', 'symbol' => 'ع.د'],
            'IR' => ['code' => 'IRR', 'name' => 'Iranian Rial', 'symbol' => '﷼'],
            'IL' => ['code' => 'ILS', 'name' => 'Israeli New Shekel', 'symbol' => '₪'],
            'TR' => ['code' => 'TRY', 'name' => 'Turkish Lira', 'symbol' => '₺'],
            'YE' => ['code' => 'YER', 'name' => 'Yemeni Rial', 'symbol' => '﷼'],

            // ইউরোপ
            'GB' => ['code' => 'GBP', 'name' => 'British Pound', 'symbol' => '£'],
            'EU' => ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€'],
            'CH' => ['code' => 'CHF', 'name' => 'Swiss Franc', 'symbol' => 'CHF'],
            'NO' => ['code' => 'NOK', 'name' => 'Norwegian Krone', 'symbol' => 'kr'],
            'SE' => ['code' => 'SEK', 'name' => 'Swedish Krona', 'symbol' => 'kr'],
            'DK' => ['code' => 'DKK', 'name' => 'Danish Krone', 'symbol' => 'kr'],
            'IS' => ['code' => 'ISK', 'name' => 'Icelandic Króna', 'symbol' => 'kr'],
            'PL' => ['code' => 'PLN', 'name' => 'Polish Zloty', 'symbol' => 'zł'],
            'CZ' => ['code' => 'CZK', 'name' => 'Czech Koruna', 'symbol' => 'Kč'],
            'HU' => ['code' => 'HUF', 'name' => 'Hungarian Forint', 'symbol' => 'Ft'],
            'RO' => ['code' => 'RON', 'name' => 'Romanian Leu', 'symbol' => 'lei'],
            'BG' => ['code' => 'BGN', 'name' => 'Bulgarian Lev', 'symbol' => 'лв'],
            'RS' => ['code' => 'RSD', 'name' => 'Serbian Dinar', 'symbol' => 'дин'],
            'HR' => ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€'],
            'RU' => ['code' => 'RUB', 'name' => 'Russian Ruble', 'symbol' => '₽'],
            'UA' => ['code' => 'UAH', 'name' => 'Ukrainian Hryvnia', 'symbol' => '₴'],
            'BY' => ['code' => 'BYN', 'name' => 'Belarusian Ruble', 'symbol' => 'Br'],
            'MD' => ['code' => 'MDL', 'name' => 'Moldovan Leu', 'symbol' => 'L'],
            'AL' => ['code' => 'ALL', 'name' => 'Albanian Lek', 'symbol' => 'L'],
            'MK' => ['code' => 'MKD', 'name' => 'Macedonian Denar', 'symbol' => 'ден'],
            'BA' => ['code' => 'BAM', 'name' => 'Bosnia Convertible Mark', 'symbol' => 'KM'],
            'ME' => ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€'],

            // আমেরিকা
            'US' => ['code' => 'USD', 'name' => 'United States Dollar', 'symbol' => '$'],
            'CA' => ['code' => 'CAD', 'name' => 'Canadian Dollar', 'symbol' => 'C$'],
            'MX' => ['code' => 'MXN', 'name' => 'Mexican Peso', 'symbol' => '$'],
            'BR' => ['code' => 'BRL', 'name' => 'Brazilian Real', 'symbol' => 'R$'],
            'AR' => ['code' => 'ARS', 'name' => 'Argentine Peso', 'symbol' => '$'],
            'CL' => ['code' => 'CLP', 'name' => 'Chilean Peso', 'symbol' => '$'],
            'CO' => ['code' => 'COP', 'name' => 'Colombian Peso', 'symbol' => '$'],
            'PE' => ['code' => 'PEN', 'name' => 'Peruvian Sol', 'symbol' => 'S/'],
            'VE' => ['code' => 'VES', 'name' => 'Venezuelan Bolívar', 'symbol' => 'Bs.'],
            'EC' => ['code' => 'USD', 'name' => 'United States Dollar', 'symbol' => '$'],
            'BO' => ['code' => 'BOB', 'name' => 'Bolivian Boliviano', 'symbol' => 'Bs.'],
            'PY' => ['code' => 'PYG', 'name' => 'Paraguayan Guarani', 'symbol' => '₲'],
            'UY' => ['code' => 'UYU', 'name' => 'Uruguayan Peso', 'symbol' => '$U'],
            'GY' => ['code' => 'GYD', 'name' => 'Guyanese Dollar', 'symbol' => '$'],
            'SR' => ['code' => 'SRD', 'name' => 'Surinamese Dollar', 'symbol' => '$'],
            'CU' => ['code' => 'CUP', 'name' => 'Cuban Peso', 'symbol' => '₱'],
            'DO' => ['code' => 'DOP', 'name' => 'Dominican Peso', 'symbol' => 'RD$'],
            'HT' => ['code' => 'HTG', 'name' => 'Haitian Gourde', 'symbol' => 'G'],
            'JM' => ['code' => 'JMD', 'name' => 'Jamaican Dollar', 'symbol' => 'J$'],
            'TT' => ['code' => 'TTD', 'name' => 'Trinidad and Tobago Dollar', 'symbol' => 'TT$'],
            'BB' => ['code' => 'BBD', 'name' => 'Barbadian Dollar', 'symbol' => '$'],
            'BS' => ['code' => 'BSD', 'name' => 'Bahamian Dollar', 'symbol' => '$'],
            'BZ' => ['code' => 'BZD', 'name' => 'Belize Dollar', 'symbol' => 'BZ$'],
            'GT' => ['code' => 'GTQ', 'name' => 'Guatemalan Quetzal', 'symbol' => 'Q'],
            'HN' => ['code' => 'HNL', 'name' => 'Honduran Lempira', 'symbol' => 'L'],
            'SV' => ['code' => 'USD', 'name' => 'United States Dollar', 'symbol' => '$'],
            'NI' => ['code' => 'NIO', 'name' => 'Nicaraguan Córdoba', 'symbol' => 'C$'],
            'CR' => ['code' => 'CRC', 'name' => 'Costa Rican Colón', 'symbol' => '₡'],
            'PA' => ['code' => 'PAB', 'name' => 'Panamanian Balboa', 'symbol' => 'B/.'],

            // আফ্রিকা
            'NG' => ['code' => 'NGN', 'name' => 'Nigerian Naira', 'symbol' => '₦'],
            'ZA' => ['code' => 'ZAR', 'name' => 'South African Rand', 'symbol' => 'R'],
            'EG' => ['code' => 'EGP', 'name' => 'Egyptian Pound', 'symbol' => '£'],
            'KE' => ['code' => 'KES', 'name' => 'Kenyan Shilling', 'symbol' => 'KSh'],
            'GH' => ['code' => 'GHS', 'name' => 'Ghanaian Cedi', 'symbol' => '₵'],
            'ET' => ['code' => 'ETB', 'name' => 'Ethiopian Birr', 'symbol' => 'Br'],
            'TZ' => ['code' => 'TZS', 'name' => 'Tanzanian Shilling', 'symbol' => 'TSh'],
            'UG' => ['code' => 'UGX', 'name' => 'Ugandan Shilling', 'symbol' => 'USh'],
            'MA' => ['code' => 'MAD', 'name' => 'Moroccan Dirham', 'symbol' => 'د.م.'],
            'DZ' => ['code' => 'DZD', 'name' => 'Algerian Dinar', 'symbol' => 'د.ج'],
            'TN' => ['code' => 'TND', 'name' => 'Tunisian Dinar', 'symbol' => 'د.ت'],
            'LY' => ['code' => 'LYD', 'name' => 'Libyan Dinar', 'symbol' => 'ل.د'],
            'SD' => ['code' => 'SDG', 'name' => 'Sudanese Pound', 'symbol' => 'ج.س.'],
            'SN' => ['code' => 'XOF', 'name' => 'West African CFA Franc', 'symbol' => 'CFA'],
            'CI' => ['code' => 'XOF', 'name' => 'West African CFA Franc', 'symbol' => 'CFA'],
            'CM' => ['code' => 'XAF', 'name' => 'Central African CFA Franc', 'symbol' => 'FCFA'],
            'CD' => ['code' => 'CDF', 'name' => 'Congolese Franc', 'symbol' => 'FC'],
            'AO' => ['code' => 'AOA', 'name' => 'Angolan Kwanza', 'symbol' => 'Kz'],
            'MZ' => ['code' => 'MZN', 'name' => 'Mozambican Metical', 'symbol' => 'MT'],
            'ZM' => ['code' => 'ZMW', 'name' => 'Zambian Kwacha', 'symbol' => 'ZK'],
            'ZW' => ['code' => 'ZWL', 'name' => 'Zimbabwean Dollar', 'symbol' => '$'],
            'BW' => ['code' => 'BWP', 'name' => 'Botswana Pula', 'symbol' => 'P'],
            'NA' => ['code' => 'NAD', 'name' => 'Namibian Dollar', 'symbol' => '$'],
            'MU' => ['code' => 'MUR', 'name' => 'Mauritian Rupee', 'symbol' => '₨'],
            'MG' => ['code' => 'MGA', 'name' => 'Malagasy Ariary', 'symbol' => 'Ar'],
            'RW' => ['code' => 'RWF', 'name' => 'Rwandan Franc', 'symbol' => 'FRw'],
            'BI' => ['code' => 'BIF', 'name' => 'Burundian Franc', 'symbol' => 'FBu'],
            'SO' => ['code' => 'SOS', 'name' => 'Somali Shilling', 'symbol' => 'Sh'],
            'SS' => ['code' => 'SSP', 'name' => 'South Sudanese Pound', 'symbol' => '£'],
            'MR' => ['code' => 'MRU', 'name' => 'Mauritanian Ouguiya', 'symbol' => 'UM'],
            'GM' => ['code' => 'GMD', 'name' => 'Gambian Dalasi', 'symbol' => 'D'],
            'SL' => ['code' => 'SLE', 'name' => 'Sierra Leonean Leone', 'symbol' => 'Le'],
            'LR' => ['code' => 'LRD', 'name' => 'Liberian Dollar', 'symbol' => '$'],

            // ওশেনিয়া
            'AU' => ['code' => 'AUD', 'name' => 'Australian Dollar', 'symbol' => 'A$'],
            'NZ' => ['code' => 'NZD', 'name' => 'New Zealand Dollar', 'symbol' => 'NZ$'],
            'FJ' => ['code' => 'FJD', 'name' => 'Fijian Dollar', 'symbol' => 'FJ$'],
            'PG' => ['code' => 'PGK', 'name' => 'Papua New Guinean Kina', 'symbol' => 'K'],
            'SB' => ['code' => 'SBD', 'name' => 'Solomon Islands Dollar', 'symbol' => '$'],
            'VU' => ['code' => 'VUV', 'name' => 'Vanuatu Vatu', 'symbol' => 'VT'],
            'WS' => ['code' => 'WST', 'name' => 'Samoan Tala', 'symbol' => 'T'],
            'TO' => ['code' => 'TOP', 'name' => 'Tongan Paʻanga', 'symbol' => 'T$'],
            'PF' => ['code' => 'XPF', 'name' => 'CFP Franc', 'symbol' => '₣'],
            'NC' => ['code' => 'XPF', 'name' => 'CFP Franc', 'symbol' => '₣'],
        ];
    }

    public static function forCountry(?string $country): array
    {
        $country = strtoupper(trim((string) $country));

        if ($country === 'BANGLADESH') {
            $country = 'BD';
        }

        // কিছু সাধারণ পূর্ণ নামকে ISO কোডে রূপান্তর করা হচ্ছে (ঐচ্ছিক)
        $nameToCode = [
            'INDIA' => 'IN',
            'PAKISTAN' => 'PK',
            'UNITED STATES' => 'US',
            'USA' => 'US',
            'UNITED KINGDOM' => 'GB',
            'UK' => 'GB',
            'CANADA' => 'CA',
            'AUSTRALIA' => 'AU',
            'JAPAN' => 'JP',
            'CHINA' => 'CN',
            'GERMANY' => 'EU', // সাধারণত EUR ব্যবহৃত হয়
            'FRANCE' => 'EU',
            'ITALY' => 'EU',
            'SPAIN' => 'EU',
            'NETHERLANDS' => 'EU',
            'SAUDI ARABIA' => 'SA',
            'UNITED ARAB EMIRATES' => 'AE',
            'UAE' => 'AE',
            'MALAYSIA' => 'MY',
            'SINGAPORE' => 'SG',
            'THAILAND' => 'TH',
            'INDONESIA' => 'ID',
            'PHILIPPINES' => 'PH',
            'VIETNAM' => 'VN',
            'NEPAL' => 'NP',
            'SRI LANKA' => 'LK',
            'SOUTH KOREA' => 'KR',
            'KOREA' => 'KR',
            'TURKEY' => 'TR',
            'RUSSIA' => 'RU',
            'BRAZIL' => 'BR',
            'MEXICO' => 'MX',
            'SOUTH AFRICA' => 'ZA',
            'NIGERIA' => 'NG',
            'EGYPT' => 'EG',
            'KENYA' => 'KE',
        ];

        if (isset($nameToCode[$country])) {
            $country = $nameToCode[$country];
        }

        return self::map()[$country] ?? [
            'code' => 'USD',
            'name' => 'United States Dollar',
            'symbol' => '$',
        ];
    }

    public static function normalize(?string $country): string
{
    $c = strtoupper(trim((string) $country));

    return $c === 'BANGLADESH' ? 'BD' : $c;
}

public static function symbol(?string $code): string
{
    $code = strtoupper((string) $code);

    foreach (self::map() as $c) {
        if ($c['code'] === $code) {
            return $c['symbol'];
        }
    }

    return $code;
}

}