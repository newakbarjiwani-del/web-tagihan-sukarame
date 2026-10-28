<?php

if (!function_exists('brand_color')) {
    function brand_color(string $key, string $fallback): string
    {
        $value = env($key);
        if (!is_string($value)) {
            return $fallback;
        }
        $value = trim($value, " \t\n\r\0\x0B\"'");
        if (!preg_match('/^#?([0-9a-fA-F]{6}|[0-9a-fA-F]{3})$/', $value, $m)) {
            return $fallback;
        }
        $hex = $m[1];
        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }

        return '#'.strtoupper($hex);
    }

    function brand_hex_mix(string $hex, string $with, float $amount): string
    {
        $parse = static function (string $color): array {
            $color = ltrim($color, '#');
            if (strlen($color) === 3) {
                $color = $color[0].$color[0].$color[1].$color[1].$color[2].$color[2];
            }

            return [hexdec(substr($color, 0, 2)), hexdec(substr($color, 2, 2)), hexdec(substr($color, 4, 2))];
        };
        $a = $parse($hex);
        $b = $parse($with);
        $t = max(0.0, min(1.0, $amount));
        $mix = static fn (int $from, int $to): int => (int) round($from + ($to - $from) * $t);

        return sprintf('#%02X%02X%02X', $mix($a[0], $b[0]), $mix($a[1], $b[1]), $mix($a[2], $b[2]));
    }
}

/**
 * Brand / white-label settings for this master tagihan project.
 *
 * Ubah via .env (disarankan) atau langsung di file ini.
 * Setelah ganti logo, regenerate icon PWA: php public/icons/generate-icons.php
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Identitas sistem
    |--------------------------------------------------------------------------
    */
    'name' => env('BRAND_NAME', 'DEMO_WS_TAGIHAN_CICCILAN'),
    'short_name' => env('BRAND_SHORT_NAME', 'Demo Cicilan'),
    'tagline' => env('BRAND_TAGLINE', 'Cek & bayar tagihan'),
    'description' => env('BRAND_DESCRIPTION', 'Demo cek dan bayar tagihan cicilan'),
    'footer' => env('BRAND_FOOTER', null), // null = "© {year} {name}"

    /*
    |--------------------------------------------------------------------------
    | Aset visual (relatif ke /public)
    |--------------------------------------------------------------------------
    */
    'logo' => env('BRAND_LOGO', 'logo.jpeg'),
    'favicon' => env('BRAND_FAVICON', 'icon-jannah.jpeg'),
    'icon_192' => env('BRAND_ICON_192', 'icons/icon-192.png'),
    'icon_512' => env('BRAND_ICON_512', 'icons/icon-512.png'),
    'guide_image' => env('BRAND_GUIDE_IMAGE', 'Gambar_Panduan_Bayar.jpeg'),
    'guide_pdf' => env('BRAND_GUIDE_PDF', 'Booklet_Panduan_Bayar.pdf'),
    'show_guide' => filter_var(env('BRAND_SHOW_GUIDE', true), FILTER_VALIDATE_BOOLEAN),

    /*
    |--------------------------------------------------------------------------
    | Model pembayaran
    |--------------------------------------------------------------------------
    | BRAND_PAYMENT_MODEL:
    |   1 / bills  → pilih tagihan + bayar lewat VA (nominal, centang)
    |   2 / saldo  → tagihan hanya informasi; VA tampil sebagai kartu isi saldo
    |                (mirip top up QRIS). Default: 2
    |
    | payment_qris = true → tombol Top up saldo via QRIS (boleh digabung model 1/2)
    */
    'payment_model' => (static function () {
        $raw = strtolower(trim((string) env('BRAND_PAYMENT_MODEL', '2')));
        if (in_array($raw, ['1', 'bills', 'bill', 'tagihan'], true)) {
            return 'bills';
        }

        return 'saldo'; // 2, saldo, info, info_only, …
    })(),
    'payment_can_pay_bills' => (static function () {
        $raw = strtolower(trim((string) env('BRAND_PAYMENT_MODEL', '2')));

        return in_array($raw, ['1', 'bills', 'bill', 'tagihan'], true);
    })(),
    'payment_qris' => filter_var(env('BRAND_PAYMENT_QRIS', false), FILTER_VALIDATE_BOOLEAN),
    'qris' => [
        'server_url' => env('QRIS_SERVER_URL', 'http://103.23.103.43/qris/lazizmu_diy/server.php'),
        'jwt_secret' => env('QRIS_JWT_SECRET', 'TokenJWT_BMI_ICT'),
        'account_no' => env('QRIS_ACCOUNT_NO', '5080010295'),
        'mitra_customer_id' => env('QRIS_MITRA_CUSTOMER_ID', 'ISLAMIC CENTER SMG451061'),
        'tipe_transaksi' => env('QRIS_TIPE_TRANSAKSI', 'MTR-GENERATE-QRIS-DYNAMIC'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Warna (hex). Dipakai sebagai CSS variables + PWA theme-color
    |--------------------------------------------------------------------------
    | Primary / highlight mengikuti logo. Kalau BRAND_COLOR_DARK_* tidak diisi,
    | mode gelap diturunkan dari primary supaya tidak tertinggal biru bawaan.
    */
    'colors' => (static function () {
        $primary = brand_color('BRAND_COLOR_PRIMARY', '#0B7EB8');
        $primaryHover = brand_color('BRAND_COLOR_PRIMARY_HOVER', brand_hex_mix($primary, '#000000', 0.18));
        $primarySoft = brand_color('BRAND_COLOR_PRIMARY_SOFT', brand_hex_mix('#FFFFFF', $primary, 0.14));
        $highlight = brand_color('BRAND_COLOR_HIGHLIGHT', '#E89B0C');
        $highlightSoft = brand_color('BRAND_COLOR_HIGHLIGHT_SOFT', brand_hex_mix('#FFFFFF', $highlight, 0.18));
        $bg = brand_color('BRAND_COLOR_BG', brand_hex_mix('#E8EDF2', $primary, 0.06));
        $darkBg = brand_color('BRAND_COLOR_DARK_BG', brand_hex_mix('#0C0E14', $primary, 0.22));

        return [
            'primary' => $primary,
            'primary_hover' => $primaryHover,
            'primary_soft' => $primarySoft,
            'theme' => brand_color('BRAND_COLOR_THEME', $primary),
            'highlight' => $highlight,
            'highlight_soft' => $highlightSoft,
            'bg' => $bg,
            'bg_deep' => brand_color('BRAND_COLOR_BG_DEEP', brand_hex_mix($bg, $primary, 0.1)),
            'surface' => brand_color('BRAND_COLOR_SURFACE', '#FFFFFF'),
            'surface2' => brand_color('BRAND_COLOR_SURFACE2', brand_hex_mix('#F4F7FA', $primary, 0.06)),
            'border' => brand_color('BRAND_COLOR_BORDER', brand_hex_mix('#C5D0DA', $primary, 0.16)),
            'border2' => brand_color('BRAND_COLOR_BORDER2', brand_hex_mix('#8FA3B5', $primary, 0.22)),
            'text' => brand_color('BRAND_COLOR_TEXT', '#0A1F2E'),
            'text2' => brand_color('BRAND_COLOR_TEXT2', brand_hex_mix('#334B5C', $primary, 0.2)),
            'text3' => brand_color('BRAND_COLOR_TEXT3', brand_hex_mix('#5A7386', $primary, 0.16)),
            'table_head' => brand_color('BRAND_COLOR_TABLE_HEAD', $primary),
            'table_head_text' => brand_color('BRAND_COLOR_TABLE_HEAD_TEXT', '#FFFFFF'),
            'on_accent' => brand_color('BRAND_COLOR_ON_PRIMARY', '#FFFFFF'),
            'dark_bg' => $darkBg,
            'dark_bg_deep' => brand_color('BRAND_COLOR_DARK_BG_DEEP', brand_hex_mix('#07080C', $primary, 0.28)),
            'dark_surface' => brand_color('BRAND_COLOR_DARK_SURFACE', brand_hex_mix($darkBg, $primary, 0.28)),
            'dark_surface2' => brand_color('BRAND_COLOR_DARK_SURFACE2', brand_hex_mix($darkBg, $primary, 0.4)),
            'dark_border' => brand_color('BRAND_COLOR_DARK_BORDER', brand_hex_mix($darkBg, $primary, 0.48)),
            'dark_border2' => brand_color('BRAND_COLOR_DARK_BORDER2', brand_hex_mix($darkBg, $primary, 0.62)),
            'dark_text' => brand_color('BRAND_COLOR_DARK_TEXT', '#F3F6FA'),
            'dark_text2' => brand_color('BRAND_COLOR_DARK_TEXT2', brand_hex_mix('#D5DEE8', $primary, 0.18)),
            'dark_text3' => brand_color('BRAND_COLOR_DARK_TEXT3', brand_hex_mix('#A8B6C4', $primary, 0.16)),
            'dark_primary' => brand_color('BRAND_COLOR_DARK_PRIMARY', brand_hex_mix($primary, '#FFFFFF', 0.42)),
            'dark_primary_hover' => brand_color('BRAND_COLOR_DARK_PRIMARY_HOVER', brand_hex_mix($primary, '#FFFFFF', 0.55)),
            'dark_primary_soft' => brand_color('BRAND_COLOR_DARK_PRIMARY_SOFT', brand_hex_mix($darkBg, $primary, 0.55)),
            'dark_highlight' => brand_color('BRAND_COLOR_DARK_HIGHLIGHT', brand_hex_mix($highlight, '#FFFFFF', 0.12)),
            'dark_highlight_soft' => brand_color('BRAND_COLOR_DARK_HIGHLIGHT_SOFT', brand_hex_mix($darkBg, $highlight, 0.35)),
            'dark_table_head' => brand_color('BRAND_COLOR_DARK_TABLE_HEAD', brand_hex_mix($darkBg, $primary, 0.72)),
            'dark_table_head_text' => brand_color('BRAND_COLOR_DARK_TABLE_HEAD_TEXT', '#FFFFFF'),
            'dark_on_accent' => brand_color('BRAND_COLOR_DARK_ON_PRIMARY', '#141018'),
        ];
    })(),

    /*
    |--------------------------------------------------------------------------
    | Bentuk UI
    |--------------------------------------------------------------------------
    */
    'radius' => (int) env('BRAND_RADIUS', 8), // px — app-like, tidak berlebihan
    'font_family' => env(
        'BRAND_FONT',
        "'Plus Jakarta Sans', system-ui, sans-serif"
    ),
    'font_display' => env(
        'BRAND_FONT_DISPLAY',
        "'Plus Jakarta Sans', system-ui, sans-serif"
    ),
    // Bunny Fonts biasanya lebih andal di jaringan lokal/ID
    'font_url' => env(
        'BRAND_FONT_URL',
        'https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap'
    ),

    /*
    |--------------------------------------------------------------------------
    | PWA
    |--------------------------------------------------------------------------
    */
    'pwa' => [
        'enabled' => filter_var(env('BRAND_PWA_ENABLED', true), FILTER_VALIDATE_BOOLEAN),
        'display' => env('BRAND_PWA_DISPLAY', 'standalone'),
        'orientation' => env('BRAND_PWA_ORIENTATION', 'portrait-primary'),
        'start_url' => env('BRAND_PWA_START_URL', '/?source=pwa'),
        'background_color' => env('BRAND_PWA_BG', '#0a1620'),
    ],

];
