<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Ticket</title>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background-color: #fef6eb;
            /* Warna latar belakang untuk anak-anak dan remaja */
            font-family: Arial, sans-serif;
        }

        .ticket-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 30px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            border-radius: 20px;
            text-align: center;
            max-width: 600px;
            opacity: 0;
            animation: fadeIn 1s forwards;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
            }
        }

        .ticket-status {
            font-size: 36px;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 20px;
            color: #28a745;
            background-color: #d4edda;
            /* Warna hijau muda untuk status berhasil */
        }

        .time {
            font-size: 40px;
            margin-top: 20px;
            color: #333;
        }

        .hint {
            font-size: 16px;
            margin-top: 10px;
            color: #777;
        }

        .ticket-code {
            font-size: 24px;
            margin-top: 20px;
            color: #333;
        }

        .ticket-type {
            font-size: 24px;
            margin-top: 10px;
            color: #333;
        }

        .icon {
            font-size: 60px;
            margin-top: 20px;
        }

        body.success {
            background-color: #04b21e;
        }

        body.used {
            background-color: #fff8e5;
        }

        body.invalid {
            background-color: #fcd0d0;
        }

        .ticket-code {
            width: calc(35ch + 30px);
        }

        .ticket-code {
            font-size: 24px;
        }

        .balloon-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            pointer-events: none;
        }

        .balloon:nth-child(1) {
            background-color: rgba(240, 192, 192, 0.8);
        }

        .balloon:nth-child(2) {
            background-color: rgba(240, 224, 240, 0.8);
        }

        .balloon:nth-child(3) {
            background-color: rgba(255, 240, 224, 0.8);
        }

        @keyframes balloonAnimation {
            0% {
                transform: translate(0, 0);
                opacity: 1;
            }

            25% {
                transform: translate(50px, 100px);
                opacity: 0.8;
            }

            50% {
                transform: translate(100px, -50px);
                opacity: 0.6;
            }

            75% {
                transform: translate(-50px, 150px);
                opacity: 0.4;
            }

            100% {
                transform: translate(0, 0);
                opacity: 0;
            }
        }

        .balloon {
            /* Kode styling untuk balon */
            /* Ubah ukuran dan tampilan balon sesuai keinginan Anda */
            width: 50px;
            height: 50px;
            background-color: #f99;
            border-radius: 50%;
            position: absolute;
            animation: balloonAnimation 5s infinite linear;
            opacity: 0;
            /* Balon akan tersembunyi saat halaman pertama kali dimuat */
            transition: opacity 1s ease;
        }

        .balloon.show {
            opacity: 1;
            /* Balon akan muncul secara halus dengan durasi 1 detik saat class "show" ditambahkan */
            /* Menghapus animasi balon saat sudah terlihat */
            transition: opacity 1s ease;
            /* Transisi saat balon muncul */
        }
    </style>
</head>

<body class="success">
    <!-- Elemen balon -->
    <div class="balloon-container">
        <div class="balloon"></div>
        <div class="balloon"></div>
        <div class="balloon"></div>
        <!-- Tambahkan lebih banyak balon jika diinginkan -->
    </div>
    <div class="ticket-container">
        <h1>Check Ticket Status</h1>
        <div class="ticket-status used">
            Scan Tiket Anda
        </div>
        <div class="time">00:00 AM</div>
        <div class="hint">Scan QR Code untuk verifikasi</div>
        <!-- Ubah input menjadi readonly untuk kode tiket -->
        <input type="text" class="form-control ticket-code" value id="ticketCode">
        <div class="ticket-type">Jenis Tiket: -</div>
        <div class="icon">
            <!-- Ganti kelas "bi-check-circle-fill", "bi-exclamation-triangle", atau "bi-x-circle" sesuai dengan status tiket -->
            <i class="bi bi-qr-code"></i>
        </div>
    </div>


    <!-- Ganti kelas "success", "used", atau "invalid" sesuai dengan status tiket -->

    <script type="text/javascript">
        $('#ticketCode').on("keypress", function(e) {
            if (e.which == 13) {
                doReq('cektiket/' + $(this).val(), null, function(res) {
                    if (res.status == 'success') {
                        updateTicketStatus('success', res)
                    } else if (res.status == 'used') {
                        updateTicketStatus('used', res)
                    } else if (res.status == 'invalid') {
                        updateTicketStatus('invalid', res)
                    } else if (res.status == 'pending') {
                        updateTicketStatus('pending', res)
                    }
                    setTimeout(kembalikan, 2000);
                })
            }

        })

        function updateTicketStatus(status, data) {
            // Mengubah warna background sesuai dengan status tiket
            $('body').removeClass('success used invalid').addClass(status);

            // Mengubah teks status tiket

            // Mengubah icon berdasarkan status tiket
            var iconClass;
            if (status === 'success') {
                $('.ticket-status').text('Tiket Valid');
                $('.ticket-type').text(data.jentiket);
                $('.time').text(data.time);
                iconClass = 'bi-check-circle-fill text-success';
            } else if (status === 'used') {
                iconClass = 'bi-exclamation-triangle text-warning';
                $('.ticket-status').text('Tiket Sudah digunakan');
                $('.ticket-type').text("Jenis Tiket: " + data.jentiket);

                $('.time').text(data.time);

            } else if (status === 'invalid') {
                $('.ticket-status').text('Tiket Tidak Valid');
                $('.ticket-type').text("Ukknown");
                $('.time').text("00:00 AM");
                iconClass = 'bi-x-circle text-danger';
            } else if (status === 'pending') {
                iconClass = 'bi-exclamation-triangle text-warning';
                $('.ticket-status').text('Tiket Tidak Berlaku Hari ini!');
                $('.time').text("Hanya Berlaku di Hari " + data.berlaku);
                $('.ticket-type').text("Jenis Tiket: " + data.jentiket);
            }
            $('.icon i').removeClass().addClass('bi ' + iconClass);
            updateTicketColor(data)
        }

        function kembalikan() {
            var iconClass;
            $('.ticket-status').text(' Scan Tiket Anda');
            $('.ticket-type').text("Jenis Tiket: -");
            $('.time').text("00:00 AM ");
            iconClass = 'bi bi-qr-code';
            $('#ticketCode').val("");
            $('.icon i').removeClass().addClass('bi ' + iconClass);
            $('body').removeClass(' used invalid pending').addClass("success").removeAttr("style");
            $('.ticket-status').css('background-color', "#e5f7e5");
            $('.ticket-status').css('color', '#000');

        }

        function updateTicketColor(status) {
            // Mengubah warna background sesuai dengan status tiket

            $('body').removeClass('success used invalid pending').addClass(status.status);

            // Mengubah warna status tiket
            var statusColor;
            if (status.status === 'success') {
                if (status.jentiket == "Premium Day") {
                    $('body').css('background-color', '#484af5');
                } else {
                    statusColor = '#04b21e'; // Hijau untuk status berhasil
                }
            } else if (status.status === 'used') {
                if (status.today) {
                    statusColor = '#fff8e5';
                } else {
                    $('body').css('background-color', '#f23939');
                    statusColor = '#fff8e5';
                }
                console.log(status.today)
            } else if (status.status === 'invalid') {
                statusColor = '#fcd0d0';
            } else if (status.status === 'pending') {
                statusColor = '#fff8e5';
            }
            $('body').addClass(status.status);
            $('.ticket-status').css('background-color', statusColor);
            $('.ticket-status').css('color', '#000');
        }

        function updateBackgroundColor(status) {
            const body = document.body;
            body.classList.remove('success', 'used', 'invalid');
            body.classList.add(status);
        }

        const balloons = document.querySelectorAll('.balloon');

        function animateBalloons() {
            setInterval(() => {
                balloons.forEach((balloon) => {
                    const randomX = Math.floor(Math.random() * 80) + 'vw';
                    const randomY = Math.floor(Math.random() * 80) + 'vh';
                    const randomRotate = Math.floor(Math.random() * 360) + 'deg';

                    balloon.style.left = randomX;
                    balloon.style.top = randomY;
                    balloon.style.transform = `rotate(${randomRotate})`;
                });
            }, 5000); // Ubah nilai interval (dalam milidetik) sesuai dengan keinginan Anda
        }
        setTimeout(function() {
            const balloon = document.querySelector('.balloon');
            balloon.classList.add('show');
        }, 2000);

        animateBalloons();

        function doReq(act, data = {
            _token: tkn()
        }, callback, load = false, ) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
            });
            $.ajax({
                type: 'post',
                url: baseUri(act),
                data: data,
                beforeSend: function() {
                    if (load) {
                        callback(loading())
                    }
                },
                success: function(result) {
                    callback(result)
                },
                error: function(xhr) {
                    console.log(xhr);
                }
            });
        }

        function baseUri(uri = '') {
            let url = window.location.origin + "/";
            const secondURI = "http://vittindo.my.id:8083/"
            url == secondURI ? url = secondURI + "vittindo-web/public/" : '';
            uri != '' ? url = url + uri : '';
            return url;
        }
    </script>
</body>

</html>