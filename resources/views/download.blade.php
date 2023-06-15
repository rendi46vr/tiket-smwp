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
        }

        div.tiket .img img {
            display: flex;
            align-items: center;
            margin-left: 1mm;
        }

        div.tiket .img p {
            text-align: center;
            margin: 1mm 0;
        }

        div.logo img {
            width: 15mm;
            margin: 2mm 2mm 2mm 0;
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
        }

        div.footer {
            display: block;
            position: absolute;
            margin: 0 -40px -20px -30px;
            bottom: 2mm;
        }

        div.contact {
            display: inline-flex;
            position: absolute;
            line-height: 1mm;
            font-size: 7px;
            width: 50%;
        }

        .right {
            margin-left: 30mm;
        }
    </style>
</head>

<body>
    <?php

    use SimpleSoftwareIO\QrCode\Facades\QrCode;

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
            <img src="smw-b.png" alt="" />
        </div>
        <div class="img">
            <img class="center" src="data:image/png;base64,' . {{ base64_encode(QrCode::generate($tiket->id)); }} . '" />
            <p>Tanggal berlaku : {{$tjual->tgl}}</p>
            <h3 class="type">Regular Day</h3>
        </div>
        <div class="footer">
            <div class="contact">
                <p style="color: forestgreen">Whatsapp</p>
                <p>+62810293821083</p>
            </div>
            <div class="contact right">
                <p style="line-height: 2mm">
                    <font style="color: deeppink; line-height: 2mm">Instagram</font>
                    @maitreyawira <br />
                    schoolpalembang
                </p>
            </div>
        </div>
    </div>
    @endforeach
</body>

</html>