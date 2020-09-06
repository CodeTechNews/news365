@extends('layouts.main')


@section('content')
    <style> 
        #footer { 
            position: fixed; 
            padding: 10px 10px 0px 10px; 
            bottom: 0; 
            width: 100%; 
            /* Height of the footer*/  
            opacity: 0.9;
            height: 80px; 
            background: #797979; 
        } 
        a { color: #1E8BE6; }
    </style> 
    <div class="new-info border-b border-gray-800">
        <div class="container mx-auto px-4 py-16 flex flex-col md:flex-row">
            <img src="{{$noticia['imagen']}}" alt="" class=" sm:h-full md:h-32 lg:h-64  object-cover" >
            <div class="md:ml-24 lg:ml-10">
                <h2 class="text-3xl font-semibold">{{ $noticia['titulo'] }}</h2>
                <p class="text-gray-500 mt-8 font-mono font-hairline text-xl">
                   
                    
                    {!! $noticia['texto'] !!}
                    
                </p>
            </div>
        </div>
        <div id="footer">
            <marquee scrollamount="10">Estamos en período de evaluación para el lanzamiento oficial de su canal web preferido "noticias 365"</marquee> 
        </div> 
    </div>
@endsection
