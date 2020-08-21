<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;


class NewsProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        
        //dump($lista_noticias[2]);
        //View::share('todos', implode(",", $lista_noticias[2]));
        View()->composer('index', function(View $view){
            $usuarios = "Esto es un usuario";
            $view->with('usuario', $usuarios);
            
        });
    }

    
}
