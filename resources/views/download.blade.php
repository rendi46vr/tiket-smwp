<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <!-- <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'> -->
    <link rel="stylesheet" href="{{
                public_path('font-awesome-4.7.0/css/font-awesome.min.css')
            }}" />
    <style>
        .page-break {
            page-break-after: always;
        }

        body {
            width: 100%;
            margin-top: -40px;
            font-size: 10px;
            font-family: "Roboto";
        }

        div.tiket {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        div.tiket .img {
            display: block;
            width: 100%;
        }

        div.tiket .img img {
            display: flex;
            align-items: center;
            margin-left: 1mm;
            margin-left: -2px;
            margin-bottom: 0px;
            padding-top: 0px;
        }

        div.tiket .img p {
            text-align: center;
            margin: 1mm 0;
        }

        div.tiket .img ul {
            text-align: left;
            margin-left: -31px
        }

        div.tiket .img p.title {
            margin-left: -10px;
            text-align: center;
        }

        div.logo p.title {
            margin-left: -10px;
            margin-top: 1px;
            margin-right: -10px;
            text-align: center;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 0px;
            padding-bottom: 0;
        }

        /* table {
            margin: 0 -35px;
        }

        table tr td {
            width: max-content;
        } */

        h3 {
            text-align: center;
            font-size: 9px;
            margin: 1px 0;
        }

        div.footer {
            display: block;
            position: absolute;
            margin: 0 -40px -20px -30px;
            bottom: 2mm;
        }

        div.contact {
            display: block;
            line-height: 1mm;
            font-size: 6px;
            font-weight: bold;
            padding-left: 4px;
            text-align: center;
        }

        .right {
            margin-left: 30mm;
        }

        .text-red {
            color: #FF0000;
        }

        .text-green {
            color: #008000;
        }

        .logoimg {
            width: 28px;
            margin-left: -9px;
            margin-top: -5px;
            height: auto;
            position: absolute;
            left: 0;
        }

        .nourut {
            width: 30px;
            margin-right: -9px;
            margin-top: -29px;
            height: auto;
            text-align: right;
            font-size: 8px;
            position: absolute;
            right: 0;
        }
    </style>
</head>

<body>
    <?php

    use SimpleSoftwareIO\QrCode\Facades\QrCode;
    use Illuminate\Support\Facades\Storage;

    $i = 0;
    $count = $tjual->tjual1->count();

    ?>
    @foreach($tikets as $tiket)
    <?php $i++; ?>
    <div class="tiket @if($i != $count)page-break @endif">
        <div class="logo" style="
                    margin-left: auto;
                    margin-right: auto;
                    text-align: center;
                    
                ">
            <img class="logoimg" src="{{   public_path('/slf.png')}}" alt="">
            <p class="title">
                Sriwijaya <br> Lantern Festival 2023
            </p>
            <p class="nourut">{{$tiket->nourut}}</p>
            <p style="margin-top:0px; margin-left: -10; margin-right: -10; font-size:9px; font-weight:bold;"><u> Lapangan Sekolah Maitreyawira </u></p>
        </div>
        <!-- format('png')->mergeString(Storage::get('/bb.png'), .3)-> -->
        <div class="img">
            <!-- errorCorrection('H')->format('png')->mergeString(Storage::get('/bb.png'), .3)-> -->
            <img class="center" src="data:image/png;base64,' . {{ base64_encode(QrCode::size(110)->generate($tiket->id)); }} . '" />
            @if($tjual->tiket_id == 1)
            <h3 class="type text-green"> Regular Day </h3>
            @else
            <h3 class="type text-red"> Premium Day </h3>
            @endif
            <div style="padding: 0.5px; border: 1px dashed #000; margin-top: 2px; margin-bottom: 0;margin-left:-10; margin-right:-10;">
                <!-- <p style=" font-size:7px; font-weight:700;">@if($tjual->tiket_id != 1) Berlaku pada Weekend Day, Libur Nasional, Opening Day dan Closing Day @else Tiket berlaku untuk hari Rabu-Jumat, kecuali hari libur nasional @endif</p> -->

                <ul style=" font-size:5px; font-weight:500; "> @if($tjual->tiket_id != 1)
                    <li>Tiket berlaku 1 orang (mulai pukul 16.00 WIB)</li>
                    <li> Berlaku setiap hari festival (termasuk hari libur nasional, opening day, dan closing day)</li>
                    <li>Senin & Selasa TUTUP</li>
                    <li>Tunjukan E-Ticket ini sebagai tanda bukti masuk festival. Harap simpan dengan baik barcode terlampir. Barcode akan discan saat memasuki lokasi festival.</li>
                    @else

                    <li>Tiket berlaku 1 orang untuk hari Rabu/Kamis/Jumat (16.00 s.d. 21.00) kecuali hari libur nasional</li>
                    <li> Senin & Selasa TUTUP
                    </li>
                    <li>Tunjukan E-Ticket ini sebagai tanda bukti masuk festival. Harap simpan dengan baik barcode terlampir. Barcode akan discan saat memasuki lokasi festival.
                    </li>
                </ul>
                @endif
                </p>

            </div>
        </div>
        <div class="footer">
            <div class="contact">
                <!-- <p style="color: forestgreen">Whatsapp</p> -->
                IG : maitreyawiraschoolpalembang / sriwijayalanternnfestival
            </div>
            <!-- <div class="contact right">
                <p style="line-height: 2mm">
                    <font style="color: deeppink; line-height: 2mm">Instagram</font>
                    @maitreyawira <br />
                    schoolpalembang
                </p>
            </div> -->
        </div>
    </div>
    @endforeach
</body>

</html>