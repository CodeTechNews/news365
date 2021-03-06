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
        /* a { color: #1E8BE6; } */
    </style> 
    
    <script src='https://code.responsivevoice.org/responsivevoice.js'></script>
    <div class="new-info border-b border-gray-800">
        <div class="container mx-auto px-4 py-16 flex flex-col md:flex-row">
            <img src="{{$noticia['imagen']}}" alt="" class=" sm:h-full md:h-32 lg:h-64  object-cover" >
            <div class="md:ml-24 lg:ml-10">            
            <div class="mt-4 addthis_inline_share_toolbox_hesj" data-url="{{ $noticia['href'] }}" data-title="{{ $noticia['titulo'] }}" data-media="{{ $noticia['imagen'] }}"></div>

                <h2 class="text-3xl font-semibold">{{ $noticia['titulo'] }}</h2>

                <button class="mt-8 mr-5 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg focus:outline-none focus:shadow-outline" onclick="responsiveVoice.speak(document.getElementById('articulo-extraido').textContent ,'Spanish',{rate: 1.05});">🔊 Léelo </button>

                <!-- Go to www.addthis.com/dashboard to customize your tools -->
                
            

                <div class="text-gray-500 mt-8 font-mono font-hairline text-xl">

                    <p id="articulo-extraido">
                    {!! $noticia['texto'] !!}
                    

                    <br><br><br><br>
                    </p>
                </div>
            </div>
        </div>
        <div id="footer">
            <marquee scrollamount="10">Estamos en período de evaluación para el lanzamiento oficial de su portal de noticias minimalista. "365"</marquee> 
        </div> 
    </div>
    
    <!-- Go to www.addthis.com/dashboard to customize your tools -->
    <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5f65012f34c4ca0e"></script>


@endsection
