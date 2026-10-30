@php
  $brand = config('brand');
  $c = $brand['colors'];
  $r = max(0, (int) $brand['radius']);
  $radiusSm = max(0, $r - 1);
  $brandJs = [
    'name' => $brand['name'],
    'short_name' => $brand['short_name'],
    'primary' => $c['primary'],
    'primaryDark' => $c['dark_primary'],
    'surface' => $c['surface'],
    'surfaceDark' => $c['dark_surface'],
    'text' => $c['text'],
    'textDark' => $c['dark_text'],
    'theme' => $c['theme'],
    'highlight' => $c['highlight'],
    'logo' => asset($brand['logo']),
    'icon192' => asset($brand['icon_192']),
    'pwa' => (bool) ($brand['pwa']['enabled'] ?? true),
    'showGuide' => (bool) ($brand['show_guide'] ?? true),
    'paymentQris' => (bool) ($brand['payment_qris'] ?? false),
    'paymentModel' => (string) ($brand['payment_model'] ?? 'saldo'),
    'canPayBills' => (bool) ($brand['payment_can_pay_bills'] ?? false),
  ];
@endphp
<title>{{ $brand['tagline'] }} | {{ $brand['name'] }}</title>
<meta name="description" content="{{ $brand['description'] }}">
<meta name="theme-color" content="{{ $c['theme'] }}" id="metaThemeColor">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="{{ $brand['short_name'] }}">
<link rel="manifest" href="{{ url('/manifest.webmanifest') }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset($brand['favicon']) }}">
<link rel="icon" type="image/png" sizes="192x192" href="{{ asset($brand['icon_192']) }}">
<link rel="apple-touch-icon" href="{{ asset($brand['icon_192']) }}">
@if(!empty($brand['font_url']))
<link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
<link href="{{ $brand['font_url'] }}" rel="stylesheet">
@endif
{{-- Fallback jika CDN config gagal --}}
<link rel="stylesheet" href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap">
<style>
:root{
  --brand-primary:{{ $c['primary'] }};
  --brand-primary-h:{{ $c['primary_hover'] }};
  --brand-primary-soft:{{ $c['primary_soft'] }};
  --brand-theme:{{ $c['theme'] }};
  --brand-highlight:{{ $c['highlight'] }};
  --brand-highlight-soft:{{ $c['highlight_soft'] }};
  --bg:{{ $c['bg'] }};
  --bg-deep:{{ $c['bg_deep'] }};
  --surface:{{ $c['surface'] }};
  --surface2:{{ $c['surface2'] }};
  --border:{{ $c['border'] }};
  --border2:{{ $c['border2'] }};
  --text:{{ $c['text'] }};
  --text2:{{ $c['text2'] }};
  --text3:{{ $c['text3'] }};
  --accent:{{ $c['primary'] }};
  --accent-h:{{ $c['primary_hover'] }};
  --accent-soft:{{ $c['primary_soft'] }};
  --highlight:{{ $c['highlight'] }};
  --highlight-soft:{{ $c['highlight_soft'] }};
  --on-accent:{{ $c['on_accent'] }};
  --danger:#b71c1c;
  --radius:{{ $r }}px;
  --radius-sm:{{ $radiusSm }}px;
  --font:'Plus Jakarta Sans', system-ui, sans-serif;
  --font-display:'Plus Jakarta Sans', system-ui, sans-serif;
  --shadow:0 1px 2px rgba(10,31,46,.05), 0 6px 16px rgba(10,31,46,.06);
  --shadow-sm:0 1px 2px rgba(10,31,46,.05);
  --table-head:{{ $c['table_head'] }};
  --table-head-text:{{ $c['table_head_text'] }};
  --btn-face:{{ $c['surface'] }};
  color-scheme:light;
}
html,body,button,input,select,textarea,table,th,td,.btn,.tbl-title,.brand-name,.brand-tagline,.aside-card,.sf,.modal,.bill-card{
  font-family:var(--font) !important;
}
html.dark{
  --bg:{{ $c['dark_bg'] }};
  --bg-deep:{{ $c['dark_bg_deep'] }};
  --surface:{{ $c['dark_surface'] }};
  --surface2:{{ $c['dark_surface2'] }};
  --border:{{ $c['dark_border'] }};
  --border2:{{ $c['dark_border2'] }};
  --text:{{ $c['dark_text'] }};
  --text2:{{ $c['dark_text2'] }};
  --text3:{{ $c['dark_text3'] }};
  --accent:{{ $c['dark_primary'] }};
  --accent-h:{{ $c['dark_primary_hover'] }};
  --accent-soft:{{ $c['dark_primary_soft'] }};
  --highlight:{{ $c['dark_highlight'] }};
  --highlight-soft:{{ $c['dark_highlight_soft'] }};
  --on-accent:{{ $c['dark_on_accent'] }};
  --danger:#ef5350;
  --shadow:0 1px 2px rgba(0,0,0,.2), 0 10px 28px rgba(0,0,0,.28);
  --shadow-sm:0 1px 2px rgba(0,0,0,.25);
  --table-head:{{ $c['dark_table_head'] }};
  --table-head-text:{{ $c['dark_table_head_text'] }};
  --btn-face:{{ $c['dark_surface2'] }};
  color-scheme:dark;
}
</style>
<script>
window.__BRAND__ = @json($brandJs);
</script>
