<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Loja</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <style>
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light" >

    <a class="navbar-brand" href="{{route('home')}}">Loja</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">

        <ul class="navbar-nav mr-auto">


            <span style="margin-left:30px;margin-top: 8px">
                Pesquise por categorias:
</span>

            @foreach($categories as $category)
            <li class="nav-item @if(request()->is('/category/' . $category->slug)) active @endif">
                <a class="nav-link" href="{{route('category.single', ['slug' => $category->slug])}}">{{$category->name}}</a>
            </li>
            @endforeach
        </ul>

    
                <div class="my-2 my-lg-0">
                    <ul class="navbar-nav mr-auto">
                    @auth
    <li class="nav-item">
              <span class="nav-link" style="color: #000">{{auth()->user()->name }}</span>
            </li>
                @endauth
                        <li class="nav-item">
                            <a class="nav-link" href="#" onclick="event.preventDefault();
                                                                  document.querySelector('form.logout').submit(); ">Sair</a>

                            <form action="{{route('logout')}}" class="logout" method="POST" style="display:none;">
                                @csrf
                            </form>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" href="{{route('admin.stores.index')}}">Área de artistas</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('cart.index')}}" class="nav-link">
                                @if (session()->has('cart'))
                                    <span class="badge badge-danger">{{array_sum(array_column(session()->get('cart'), 'amount'))}}</span>
                                @endif
                                <i class="fa fa-shopping-cart fa-2x"></i>
                            </a>
                        </li>
                        

                    </ul>
                </div>
        

    </div>
</nav>

@yield('carousel')
<div class="container">
    @include('flash::message')
    <br>
    @yield('content')
</div>
</body>
</html>