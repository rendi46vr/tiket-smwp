<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="{{url('storage/font-awesome-4.7.0/css/font-awesome.min.css')}}">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{url('css/style.css')}}">
    @yield('scripts')
    <style>
        /* body {
            background: linear-gradient(135deg, rgba(57, 100, 229, 0.8), rgba(255, 186, 130, 0.8));
            background-repeat: no-repeat;
            background-size: cover;
            background-attachment: fixed;
        } */
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
        <a class="navbar-brand" href="#"><img class="smw-logo" src="{{url('smw-b.png')}}" alt="" srcset=""></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavId" aria-controls="collapsibleNavId" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="collapsibleNavId">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link text-dark" href="#">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="#">Event Details</a>
                </li>
                @if(auth()->user())
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-dark" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Tiket
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{url('ctiket')}}">Cetak Tiket</a>
                        <a class="dropdown-item" href="#">Tampil Penjualan</a>

                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="#">Setting</a>
                </li>

                @endif
                <li class="nav-item">
                    <a class="nav-link btn btn-primary text-light" href="{{url('/login')}}">Login</a>
                </li>
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

    <footer class="footer">
        <div class="container">
            <p>&copy; <?= date('Y') ?> Hak cipta, Sekolah Maitreyawira Palembang.</p>
        </div>
    </footer>


    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="{{url('js/app.js')}}"></script>
</body>

</html>