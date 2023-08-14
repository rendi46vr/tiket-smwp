<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')Tiket Sriwijaya Latern Festival</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="{{url('storage/font-awesome-4.7.0/css/font-awesome.min.css')}}">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{url('css/style.css')}}">
    @yield('scripts')
    <style>
        /* body {
            background: linear-gradient(135deg, rgba(57, 100, 229, 0.8), rgba(255, 186, 130, 0.8));
            background-repeat: no-repeat;
            background-size: cover;
            background-attachment: fixed;
        } */
        /* .discount-container {
            background-color: #f4f4f4;
            padding: 10px;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
        } */

        .discount-info {
            position: absolute;
            right: 10px;
            padding: 0 3vw;
        }

        .discount-percent {
            font-size: 1.3vw;
            /* Menggunakan unit vw untuk responsif dengan lebar layar */
            font-weight: bold;
            color: #FF6A6A;
        }

        .discount-text {
            font-size: 1vw;
            /* Menggunakan unit vw untuk responsif dengan lebar layar */
            margin-left: 8px;
        }

        .discount-details {
            margin-top: 10px;
            text-align: center;
        }

        .discount-minimum {
            font-size: 14px;
            color: #333;
        }

        .discount-quantity {
            font-size: 20px;
            font-weight: bold;
            color: #333;
        }

        /* Menerapkan media query untuk ukuran layar yang lebih kecil */
        @media (max-width: 768px) {
            .discount-info {
                margin-top: -10px;
            }

            .discount-percent {
                font-size: 3vw;
                /* Atur ukuran font sesuai dengan kebutuhan pada layar kecil */
            }

            .discount-text {
                font-size: 2vw;
                /* Atur ukuran font sesuai dengan kebutuhan pada layar kecil */
            }

            .discount-minimum {
                font-size: 4vw;
                /* Atur ukuran font sesuai dengan kebutuhan pada layar kecil */
            }

            .discount-quantity {
                font-size: 7vw;
                /* Atur ukuran font sesuai dengan kebutuhan pada layar kecil */
            }
        }
    </style>
</head>

<body>
    <div class="background-radial">
        <div class=" pr-0">
            <div class="col-12" id="grad1"></div>
            <div class="col-12" id="grad2"></div>
            <div class="col-12" id="grad3"></div>
            <div class="col-12" id="grad4"></div>
        </div>
    </div>
    <nav class="navbar navbar-expand-md navbar-light" style="background-color: #e3f2fd75;">
        <a class="navbar-brand" href="#"><img class="smw-logo" src="{{url('slf-b.png')}}" alt="" srcset=""></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavId" aria-controls="collapsibleNavId" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="collapsibleNavId">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link text-dark" href="{{url('/')}}">Home</a>
                </li>
                @if(auth()->user())
                <li class="nav-item">
                    <a class="nav-link text-dark" href="{{url('ctiket')}}">Cetak Tiket</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="{{url('kirim')}}">Kirim Tiket</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-dark" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Penjualan
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{url('penjualan')}}">Tampil Penjualan</a>
                        <a class="dropdown-item" href="{{url('penbem')}}">Penjualan Gagal/Belum bayar</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-dark" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Validasi
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{url('valtiket')}}">Validasi (Khusus Monitor)</a>
                        <a class="dropdown-item" href="{{url('valhp')}}">Validasi (Khusus HP)</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="{{url('tampilvalidasi')}}">Laporan Tiket Tervalidasi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="{{url('dashboard')}}">Setting</a>
                </li>
                @else
                <li class="nav-item">
                    <a class="nav-link text-dark" href="{{url('publiccek')}}">Cek Tiket</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-primary text-light" href="{{url('/login')}}">Login</a>
                </li>
                @endif
            </ul>
        </div>
    </nav>





    <!-- <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="dropdownId" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</a>
        <div class="dropdown-menu" aria-labelledby="dropdownId">
            <a class="dropdown-item" href="#">Action 1</a>
            <a class="dropdown-item" href="#">Action 2</a>
        </div>
    </li> -->
    <div class="content">

        @yield('content')
    </div>

    @if(!Request::is('publiccek'))
    <footer class="footer">
        <div class="container">
            <p>&copy; <?= date('Y') ?> Hak cipta, Sekolah Maitreyawira Palembang.</p>
        </div>
    </footer>
    @endif


    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="{{url('js/app.js')}}"></script>
    @yield('script')

</body>

</html>