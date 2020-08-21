<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Goutte\Client;


class NewsController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $resultados = $this->Listar_noticias();

        return view('index' , compact('resultados'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        

        $noticia=[
            'imagen' => $request->query('image'),
            'titulo' => $request->query('text'),
            'texto' => $this->buscar_noticia($request->query('href'))
        ];

        //dd($noticia[0]['imagen']);die;
        //dd($noticia);die;
        return view('show', compact('noticia'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    private function Listar_noticias(){
        $i = 0;
        $crawler = $this->scraping_url('https://www.noticias24mundo.com/');
        $crawler->filter('.container-fluid > div > div > div > div > div > article > div')->each(function ($node) use (&$i) {
            
            $imagen = $node->filter('img')->attr('src');
            $texto = $node->text();
            $link =  $node->filter('a')->attr('href');
            $this->news[] = [
                'id'    => $i,
                'image' => $imagen,
                'text' => $texto,
                'href'=> $link
            ];
            //$image_content = file_get_contents($imagen);
            //file_put_contents('images/' . $i . '.jpg', $image_content);
            $i++;
        });
        return $this->news;
    }

    private function scraping_url($url){
        $client = new Client();
        return $client->request('GET', $url);
    }

    private function buscar_noticia($url_noticia){

        $texto = "";
        //$url_noticia = "https://www.eleconomista.com.mx/opinion/Monreal-obsesion-por-el-control-digital-20200703-0037.html";
        $crawler = $this->scraping_url($url_noticia);
        $crawler->filter('p')->each(function ($node) use (&$texto){
            
            //$imagen = $node->filter('img')->attr('src');
            $texto .= $node->html() . "<br><br>";
            //$link =  $node->filter('a')->attr('href');
            
            //$image_content = file_get_contents($imagen);
            //file_put_contents('images/' . $i . '.jpg', $image_content);
            
        });
        /* $texto = "<div class='containe mx-auto'><video controls>
        <source src='https://cxl.hlssrv.com/hls_serve_mp4/m7pePeG7SPc0Y51eXpCd.mp4?md5=dyTLUKi3zy3R0FjzErTkvQ&expires=1595027122' type='video/mp4'>
        <source src='movie.ogg' type='video/ogg'>
      Your browser does not support the video tag.
      </video></div>";
      */  
        
        return $texto;
    }
}
