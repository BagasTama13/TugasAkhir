<!DOCTYPE html>
<html>
<head>
    <title>Pembayaran</title>
</head>
<body>

<h2>Pesanan {{ $pesanan->nomor }}</h2>

<p>Total: Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</p>

<button id="pay-button">
    Bayar Sekarang
</button>

<script
src="https://app.sandbox.midtrans.com/snap/snap.js"
data-client-key="{{ config('midtrans.client_key') }}">
</script>

<script>
document.getElementById('pay-button').onclick = function () {
    snap.pay('{{ $snapToken }}');
};
</script>

</body>
</html>