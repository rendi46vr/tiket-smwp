<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Tiket</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        #reader {
            max-width: 100%;
            margin: 0 auto;
            position: relative;
        }

        @media (min-width: 798px) {
            #reader {
                width: 500px !important;
                height: 400px !important;
            }

            .smw-card {
                width: 500px;
                height: 400px;
            }
        }

        @media (min-width: 481px) {
            #reader {
                width: 300px;
                /* Set your desired width */
                height: 300px;
                /* Set your desired height */
            }
        }

        .smw-card {
            height: 100vh;
            width: 100vw;
            display: flex;
        }

        .smw-card-body {
            display: flex;
            align-items: center;
            justify-self: center;
            width: 100%;
        }

        #html5-qrcode-button-camera-start,
        #html5-qrcode-button-camera-stop {
            padding: 4px 6px;
            color: #fff;
            text-shadow: 2px 2px rgba(255, 251, 248, 0.131);
            background-color: rgb(253, 170, 102);
            border-color: rgba(255, 186, 130, 0.5);
            border-radius: 5px;
            float: auto;
        }
    </style>
</head>

<body>
    <div class="row justify-content-center  ">
        <div class=" col-lg-4 col-md-4 col-12">
            <div class="smw-card">
                <div class="smw-card-body d-flex justify-content-center">
                    <div id="reader" style="width:100% ; height:auto;"></div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"> </script>
    <script src="{{url('js/app.js')}}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $(".html5-qrcode-element").addClass("btn btn-orange");
            console.log("ok");
        })
        let = data = "yummy", count = 0;

        function onScanSuccess(decodedText, decodedResult) {

            let newcode = decodedText;

            console.log('ini bisa')

            if (newcode != data) {
                if (count == 0) {
                    count = 1

                    console.log('hmm curiga gw sumaph')

                    doReq('admincek/' + decodedText, null, function(res) {
                        if (res.status == 'success') {

                            Swal.fire({
                                icon: 'success',
                                title: 'Yeah! ... Tiket Valid',
                                html: (res.pesan),
                                showConfirmButton: true,
                            })
                        } else if (res.status == 'used') {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Tiket Sudah Digunakan',
                                html: (res.pesan),
                                showConfirmButton: true,
                            })
                        } else if (res.status == 'invalid') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Tiket Tidak Terdaftar',
                                showConfirmButton: true,
                            })
                        } else if (res.status == 'pending') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Tiket Tidak berlaku di hari ini',
                                html: (res.pesan),
                                showConfirmButton: true,
                            })
                        }
                        data = newcode
                        setTimeout(() => {
                            data = '0'
                            count = 0

                        }, 2000);

                    })
                }
            }


        }

        function onScanFailure(error) {
            // handle scan failure, usually better to ignore and keep scanning.
            // for example:
            // console.warn(`Code scan error = ${error}`);
        }

        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", {
                fps: 10,
                qrbox: {
                    width: 270,
                    height: 240,
                    responsive: false,
                }
            }
        );

        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    </script>
</body>

</html>