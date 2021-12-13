
@extends('layouts.front')

@section('content')

<div class="row front">
        @foreach ($lojas as $key => $loja)
        <div class="col-md-4">
            <div class="card" style="width: 100%;">
                @if($loja->logo)
                <img src="{{asset('storage/' . $loja->logo)}}" alt="" class="card-img-top">
                @else <img src="{{asset('assets/img/no-photo.jpg')}}" alt="" class="card-img-top">

                @endif
    
                <div class="card-body">
                    <h2 class="card-title">{{$loja->name}}</h2>    
                    <p class="card-text">
                        {{$loja->description}}
                    </p>

                    <a href="{{route('products-store', ['ID_STORE' => $loja->id])}}" class="btn btn-success">
                        Ver Produtos
                    </a>
               
                </div> 
            </div>
        </div>
        @if(($key + 1) % 3 == 0) </div><div class="row front"> @endif
    @endforeach
    </div>

@endsection