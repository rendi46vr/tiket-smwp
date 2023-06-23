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
            margin-left: -10px;
            margin-bottom: 0px;
            padding-top: 0px;
        }

        div.tiket .img p {
            text-align: center;
            margin: 1mm 0;
        }

        div.tiket .img p.title {
            margin-left: -10px;
            text-align: center;
        }

        div.logo p.title {
            margin-left: -10px;
            margin-top: 0px;
            margin-right: -10px;
            text-align: center;
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 0px;
            padding-bottom: 0;
        }

        table {
            margin: 0 -35px;
        }

        table tr td {
            width: max-content;
        }

        h3 {
            text-align: center;
            font-size: 12px;
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
    </style>
</head>

<body>
    <?php

    use SimpleSoftwareIO\QrCode\Facades\QrCode;
    use Illuminate\Support\Facades\Storage;

    $i = 0;
    $count = $tjual->tjual1->count();
    ?>
    @foreach($tjual->tjual1 as $tiket)
    <?php $i++; ?>
    <div class="tiket @if($i != $count)page-break @endif">
        <div class="logo" style="
                    margin-left: auto;
                    margin-right: auto;
                    text-align: center;
                    
                ">
            <p class="title">
                Sriwijaya <br> Latern Festival 2023
            </p>
            <p style="margin-top:0px; margin-left: -10; margin-right: -10; font-size:9px; font-weight:bold;"><u> Lapangan Sekolah Maitreyawira </u></p>
        </div>
        <!-- format('png')->mergeString(Storage::get('/bb.png'), .3)-> -->
        <div class="img">
            <img class="center" src="data:image/png;base64,' . {{ base64_encode(QrCode::errorCorrection('H')->format('png')->mergeString(Storage::get('/bb.png'), .3)->size(130)->generate($tiket->id)); }} . '" />
            <h3 class="type">@if($tjual->tiket_id == 1)Regular Day @else Premium Day @endif</h3>
            <div style="padding: 1px; border: 1px dashed #000; margin-top: 2px; margin-bottom: 0;margin-left:-10; margin-right:-10;">
                <p style=" font-size:7px; font-weight:700;">@if($tjual->tiket_id != 1) Tiket berlaku pad Tanggal {{$tjual->tgl}} @else Tiket berlaku untuk hari Rabu-Jumat, kecuali hari libur nasional @endif</p>
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