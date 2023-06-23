<!DOCTYPE html>
<html>

<head>
    <title>Halaman Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{url('css/style.css')}}">
    <style>
        body {
            background: linear-gradient(135deg, rgba(57, 100, 229, 0.8), rgba(255, 186, 130, 0.8));
            background-repeat: no-repeat;
            background-size: cover;
            background-attachment: fixed;
        }

        .login-container {
            background-color: rgb(255, 255, 255);
            box-shadow: 0px 10px 16px rgba(57, 100, 229, 0.2);
            border-radius: 5px;
            color: black;
            padding: 20px;
            margin-top: 100px;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }

        .login-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .login-container .form-group {
            margin-bottom: 20px;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            font-size: 14px;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>

<body>
    <!-- <div class="background-radial">
        <div class=" pr-0">
            <div class="col-12" id="grad1"></div>
            <div class="col-12" id="grad2"></div>
            <div class="col-12" id="grad3"></div>
            <div class="col-12" id="grad4"></div>
        </div>
    </div> -->
    <div class="container">

        <div class="login-container">
            <h2>Login</h2>
            @if(session()->has('success'))
            <div class="error-message mt-1 mb-1">
                {{session('success')}}
            </div>
            @endif
            <form action="proses_login" method="post">
                @csrf
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" class="form-control" name="name" id="username" placeholder="Enter username">

                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Enter password">
                </div>
                <button type="submit" class="btn btn-orange btn-block">Login</button>
            </form>
        </div>
    </div>
</body>

</html>