<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mbuku - ERP for Schools</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href=" {{ asset('assets/css/outer.css') }}" rel="stylesheet" type="text/css">    
</head>

<body>
    <nav class="navbar navbar-expand-lg text-white" >
        <a class="navbar-brand" href="#">
          <img src="{{ asset('assets/pics/mbukulogo.png') }}" alt="Logo" width="50" height="50" >          
        </a>
        <a class="navbar-brand" href="#" ><h1 class=" text-white" id="logotxt">Mbuku ERP</h1></a>
        
        <button class="navbar-toggler custom-toggler text-white" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span><br>
            <span class="navbar-toggler-icon" style="margin-top: -30px;"></span>           
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link  text-white" href="{{ route('landing') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  text-white" href="{{ route('pricing') }}">Pricing</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('contact') }}">Contact</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  text-white" href="{{ route('login') }}">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  text-white" href="{{ route('signup') }}">Get Demo</a>
                </li>
            </ul>
        </div>
    </nav>
    @yield('content')       
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://kit.fontawesome.com/a076d05399.js"></script>

</body>

</html>
