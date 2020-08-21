@extends('layouts.main')

@section('content')

    <div class="container mx-auto px-24 pt-16">
        <div class="popular-news">
            <h2 class="uppercase tracking-wider text-orange-500 text-lg font-semibold">
                Popular News
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3   lg:grid-cols-5 gap-8">
                @foreach($resultados as $resultado)
                    <div class="mt-8">
                        <a href="{{ route('news.show', $resultado) }}" target="_blank">
                            <img src="{{$resultado['image']}}" alt="parasite" class="hover:opacity-75 transition ease-in-out duration-150 h-32 sm:h-48 w-full object-cover" >
                            
                        </a>
                        <div class="div mt-2">
                            
                            <a href="{{ $resultado['href'] }}" class="text-lg mt-2 hover:text-orange-500" target="_blank">
                                    {{ 
                                    (strpos( $resultado['text'] ,' - ') == 0) ?
                                    $resultado['text'] :
                                    substr($resultado['text'], 0, strpos( $resultado['text'] ,' - ') ) 
                                    }}
                            </a>
                            
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
@endsection

