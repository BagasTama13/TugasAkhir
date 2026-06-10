<!DOCTYPE html>
<html>
<head>
    <title>Midtrans Test</title>
</head>
<body>

    <h2>Midtrans Test Payment</h2>

    <button id="pay-button">
        Bayar Sekarang
    </button>

    <script
        src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}">
    </script>

    <script>
document.getElementById('pay-button').onclick = function () {

    console.log('Token:', '{{ $snapToken }}');

    this.disabled = true;

    snap.pay('{{ $snapToken }}', {

        onSuccess: function(result) {
            console.log(result);
            alert('Pembayaran Berhasil');
        },

        onPending: function(result) {
            console.log(result);
            alert('Menunggu Pembayaran');
        },

        onError: function(result) {
            console.log(result);
            alert('Pembayaran Gagal');
        },

        onClose: function() {
            console.log('Popup ditutup');
            document.getElementById('pay-button').disabled = false;
        }
    });

};
</script>

</body>
</html>