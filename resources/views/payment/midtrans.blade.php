<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Midtrans Payment</title>
    <script type="text/javascript" src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
</head>
<body>
    <h2>Payment for Reservation #{{ $reservation->id }}</h2>
    <button id="pay-button">Pay Now</button>

    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function () {
            snap.pay("{{ $snapToken }}", {
                onSuccess: function(result) {
                    alert('Payment Success!');
                    // Kirim hasil pembayaran ke server dan simpan status pembayaran
                },
                onPending: function(result) {
                    alert('Waiting for payment confirmation!');
                },
                onError: function(result) {
                    alert('Payment failed!');
                }
            });
        };
    </script>
</body>
</html>
