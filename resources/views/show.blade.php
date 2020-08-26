@extends('layouts.main')


@section('content')
    <div class="new-info border-b border-gray-800">
        <div class="container mx-auto px-4 py-16 flex flex-col md:flex-row">
            <img src="{{$noticia['imagen']}}" alt="" class=" sm:h-full md:h-32 lg:h-64  object-cover" >
            <div class="md:ml-24 lg:ml-10">
                <h2 class="text-4xl font-semibold">{{ $noticia['titulo'] }}</h2>
                <p class="text-gray-500 mt-8 font-mono font-hairline text-xl">
                   
                    
                    {!! $noticia['texto'] !!}
                    
                </p>
            </div>
        </div>
    </div>
@endsection
