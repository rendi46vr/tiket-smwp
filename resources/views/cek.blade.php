@extends('layouts.app')

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"> </script>
<script type="text/javascript">
    let = data = "yummy", count = 0;

    function onScanSuccess(decodedText, decodedResult) {

        let newcode = decodedText;
        if (newcode != data) {
            if (count == 0) {
                count = 1

                doReq('publiccek/' + decodedText, null, function(res) {
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
                width: 200,
                height: 200
            }
        },
        /* verbose= */
        false);
    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
</script>
@endsection
@section('title')
Cek Tiket
@endsection

@section('content')
<div class="row justify-content-center  ">
    <div class=" col-lg-4 col-md-4 col-12">
        <div class="smw-card ">
            <div class="smw-card-header"> <i class="fa fa-wpforms mr-1 i-orange" aria-hidden="true"></i>
                Cek Tiket
            </div>
            <div class="smw-card-body">
                <div id="reader" style="width:100% ; height:auto;"></div>
            </div>
        </div>
    </div>

</div>


@endsection