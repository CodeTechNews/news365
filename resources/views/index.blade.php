@extends('layouts.main')

@section('content')

    <!-- TradingView Widget BEGIN -->
    <script class="focus:outline-none " type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-ticker-tape.js" async>
    {
    "symbols": [
    {
        "proName": "FOREXCOM:SPXUSD",
        "title": "S&P 500"
    },
    {
        "proName": "FOREXCOM:NSXUSD",
        "title": "Nasdaq 100"
    },
    {
        "proName": "FX:USDMXN",
        "title": "USD/MXN"
    },
    {
        "proName": "BITSTAMP:BTCUSD",
        "title": "BTC/USD"
    },
    {
        "description": "IGV",
        "proName": "AMEX:IGV"
    },
    {
        "description": "VOO",
        "proName": "AMEX:VOO"
    },
    {
        "description": "BUI",
        "proName": "BUI"
    }
    ],
        "colorTheme": "dark",
        "isTransparent": true,
        "displayMode": "adaptive",
        "locale": "es"
    }
    </script>



    <!-- TradingView Widget END -->

    <div class="container mx-auto px-4 pt-2">
        <!-- <marquee scrollamount="10">Bienvendos al portal de noticias 365</marquee> -->    
        <div class="popular-news">
            <h2 class="uppercase tracking-wider text-orange-500 text-lg font-semibold">
                Populares
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


