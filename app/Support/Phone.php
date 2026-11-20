<?php

namespace App\Support;

class Phone
{
    /** দেশ => ডায়াল কোড (Currency::map() এর দেশগুলো) */
    public const DIAL = [
        'BD' => '880', 'IN' => '91', 'US' => '1', 'CA' => '1', 'GB' => '44', 'AU' => '61', 'NZ' => '64',
        'SG' => '65', 'MY' => '60', 'PK' => '92', 'LK' => '94', 'NP' => '977',
        'SA' => '966', 'AE' => '971', 'QA' => '974', 'KW' => '965', 'BH' => '973', 'OM' => '968', 'JO' => '962', 'EG' => '20',
        'DE' => '49', 'FR' => '33', 'IT' => '39', 'ES' => '34', 'NL' => '31', 'BE' => '32', 'PT' => '351', 'IE' => '353',
        'AT' => '43', 'GR' => '30', 'CH' => '41', 'SE' => '46', 'NO' => '47', 'DK' => '45', 'PL' => '48', 'CZ' => '420',
        'TR' => '90', 'RU' => '7', 'UA' => '380', 'JP' => '81', 'CN' => '86', 'HK' => '852', 'KR' => '82', 'TH' => '66',
        'ID' => '62', 'PH' => '63', 'VN' => '84', 'BR' => '55', 'MX' => '52', 'ZA' => '27', 'NG' => '234', 'KE' => '254',
        'GH' => '233',
    ];

    private const ISO3 = [
        'BGD' => 'BD', 'IND' => 'IN', 'USA' => 'US', 'GBR' => 'GB', 'CAN' => 'CA', 'AUS' => 'AU', 'PAK' => 'PK',
        'ARE' => 'AE', 'SAU' => 'SA', 'QAT' => 'QA', 'KWT' => 'KW', 'OMN' => 'OM', 'MYS' => 'MY', 'SGP' => 'SG',
        'ITA' => 'IT', 'DEU' => 'DE', 'FRA' => 'FR', 'ESP' => 'ES', 'JPN' => 'JP', 'MEX' => 'MX', 'BRA' => 'BR',
    ];

    /** BD, BGD, Bangladesh সব থেকে ২ অক্ষরের কোড */
    public static function iso2(?string $country): string
    {
        $c = strtoupper(trim((string) $country));

        if ($c === 'BANGLADESH') {
            return 'BD';
        }

        if (strlen($c) === 3) {
            return self::ISO3[$c] ?? $c;
        }

        return $c;
    }

    /** সংরক্ষণের জন্য: সবসময় +ডায়ালকোড+নম্বর */
    public static function normalize(?string $input, ?string $country = null): string
    {
        $input = trim((string) $input);
        $digits = preg_replace('/\D/', '', $input);

        if ($digits === '') {
            return '';
        }

        if (str_starts_with($input, '+')) {
            return '+' . $digits;
        }

        if (str_starts_with($digits, '00')) {
            return '+' . substr($digits, 2);
        }

        $dial = self::DIAL[self::iso2($country)] ?? null;

        if (! $dial) {
            return '+' . ltrim($digits, '0');
        }

        // 0 দিয়ে শুরু: স্থানীয় নম্বর (01700... -> +880 1700...)
        if (str_starts_with($digits, '0')) {
            return '+' . $dial . ltrim($digits, '0');
        }

        // ডায়াল কোডসহ দেওয়া (8801700...)
        if (str_starts_with($digits, $dial) && strlen($digits) >= 11) {
            return '+' . $digits;
        }

        return '+' . $dial . $digits;
    }

    /** লগইনের জন্য: একই নম্বরের সম্ভাব্য সব রূপ (পুরোনো ডেটাও মিলবে) */
    public static function variants(?string $input): array
    {
        $digits = preg_replace('/\D/', '', (string) $input);

        if ($digits === '') {
            return [];
        }

        $out = ['+' . $digits, $digits, '00' . $digits];
        $dials = array_values(array_unique(self::DIAL));
        usort($dials, fn ($a, $b) => strlen($b) <=> strlen($a));

        foreach ($dials as $dial) {
            if (str_starts_with($digits, $dial)) {
                $rest = substr($digits, strlen($dial));
                $out[] = '0' . $rest;
                $out[] = $rest;
                $out[] = '+' . $dial . $rest;
            }
        }

        if (str_starts_with($digits, '0')) {
            $national = ltrim($digits, '0');

            foreach ($dials as $dial) {
                $out[] = '+' . $dial . $national;
                $out[] = $dial . $national;
            }
        }

        return array_values(array_unique(array_filter($out)));
    }
}