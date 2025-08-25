@component('mail::message')
# ⚠️ Peringatan Stok Menipis

Halo {{ $user->name }},

Kami ingin memberitahu Anda bahwa beberapa produk memiliki stok yang menipis dan memerlukan perhatian segera.

@component('mail::table')
| Produk | Stok Saat Ini | Minimum Stok | Status |
|:-------|:--------------|:-------------|:-------|
@foreach($products as $product)
| {{ $product->name }} | {{ $product->stock }} {{ $product->unit }} | {{ $product->min_stock }} {{ $product->unit }} | @if($product->stock == 0) **HABIS** @else Menipis @endif |
@endforeach
@endcomponent

@component('mail::button', ['url' => $url])
Lihat Semua Produk
@endcomponent

**Tips:** Segera lakukan restocking untuk menghindari gangguan operasional.

Terima kasih,<br>
{{ config('app.name') }}

@component('mail::subcopy')
Anda menerima email ini karena Anda terdaftar sebagai {{ $user->role }} di sistem kami.
Jika Anda tidak ingin menerima notifikasi ini, silakan update preferensi di pengaturan akun Anda.
@endcomponent
@endcomponent