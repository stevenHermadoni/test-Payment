<!DOCTYPE html>
<html>

<head>
    <title>AmbisQuest PTN</title>
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-dark text-white d-flex align-items-center justify-content-center" style="height: 100vh;">
    <div class="card bg-secondary text-center p-5 shadow-lg" style="width: 400px; border-radius: 20px;">
        <h2 class="fw-bold mb-4">🚀 Paket Intensif 2026</h2>
        <p>Akses 500+ Soal & Pembahasan</p>
        <h1 class="text-warning mb-4">Rp 5.000.000</h1>
        <button id="pay-button" class="btn btn-warning btn-lg w-100 fw-bold">BELI SEKARANG</button>
    </div>

    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function() {
            window.snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    // Ambil order_id dari hasil Midtrans atau dari variabel PHP
                let orderID = result.order_id; 
                window.location.href = "/ujian/mulai/" + orderID;
                },
                // Tambahkan ini untuk berjaga-jaga jika user menutup popup
                onClose: function() {
                    alert('Jangan ditutup dulu, selesaikan pembayarannya ya!');
                }
            });
        };
    </script>
</body>

</html>