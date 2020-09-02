<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Goutte\Client;
use Stichoza\GoogleTranslate\GoogleTranslate;

class NewsController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $resultados = $this->Listar_noticias($request->query('news'));

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
            'texto' => $this->buscar_noticia($request->query('href'), $request->query('lang'))
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


    private function Listar_noticias($news){
        $i = 0;
        $url = "";
        $filter = "";
        $language = "es";


        switch ($news) {
            case "tech":
                $url = "https://www.theverge.com/spacex";
                $filter = ".c-compact-river__entry";
                $language = "en";
                $articulos1 = $this->LeerRSS('https://www.theverge.com/rss/index.xml', $language);
                $i = count($articulos1);
                $articulos2 = $this->LeerAPI('https://app.scrapinghub.com/api/v2/datasets/JuCzQQh2IGh/download?format=json', "es", $i);
                return array_merge($articulos1, $articulos2);
                //dump($this->LeerRSS('https://www.theverge.com/rss/index.xml',$language));
                die;
                break;
            case "pol":
                return $news=[];
                break;
            case "fin":
                return $news=[];
                break;
            default:
                $url = "https://www.noticias24mundo.com/";
                $filter = ".container-fluid > div > div > div > div > div > article > div";
        }

        $crawler = $this->scraping_url($url);
        //dump($crawler->filter($filter));

        //$this->LeerRSS('https://www.theverge.com/rss/index.xml');
        //die;
       
        $crawler->filter($filter)->each(function ($node) use (&$i, &$language) {
            
            //echo $node->html();
            // try {
            //     echo $node->filter('a > div > img')->attr('src'); 
            //     echo '<br>';
            //     echo $node->filter('a')->attr('href');
            //     echo '<br>';
            //     echo $node->text();
            //     echo '<br>';
            // } catch(\Exception $e) {
            //     //$message = $e->getMessage(); 
            //     echo '****** hubo errores *******';
            //     echo '<br>';
            // } 
            
            
            if ($i>0) {
                $imagen = $node->filter('amp-img')->attr('src');
                $texto = $node->text();
                $link =  $node->filter('a')->attr('href');
                $this->news[] = [
                    'id'    => $i,
                    'image' => $imagen,
                    'text' => $texto, //substr($texto,0,100) . '...',
                    'href'=> $link,
                    'lang' => $language
                ];
            }
            
            //$image_content = file_get_contents($imagen);
            //file_put_contents('images/' . $i . '.jpg', $image_content);
            $i++;
        });
        //dump($this->news);die;
        
        return $this->news;
    }

    private function scraping_url($url){
        $client = new Client();
        return $client->request('GET', $url);
    }

    private function buscar_noticia($url_noticia, $language){

        $texto = "";
        
        try {
            //$url_noticia = "https://www.eleconomista.com.mx/opinion/Monreal-obsesion-por-el-control-digital-20200703-0037.html";
            $crawler = $this->scraping_url($url_noticia);

            //$texto = $crawler->filter('article')->html();
            
            $crawler->filter('p')->each(function ($node) use (&$texto){
                
                //$imagen = $node->filter('image-decoding')->attr('src');
                //$imagen .= "<img src='". $imagen. "' alt=''>";
                $texto .= $node->html() . "<br><br>";
                //dump($imagen);die;

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
            //dump($language);
            $traducido = $texto;
            if ($language == "en") {
                $traducido = GoogleTranslate::trans($texto, 'es');
            }
        }
        catch  (\Exception $e){
            $traducido = $texto;
        }
        //echo $traducido;die;
        //dump($traducido);die;
        return $traducido;
    }

    private function LeerAPI($url, $language, $i){
        //dump($url);die;
        $response = Http::get($url)->json();
        foreach($response as $item){
            $title = $item['title'];
            if ($language == "en") {
                $title = GoogleTranslate::trans($title, 'es');
            }
            $news[] = [
                'id'    => $i,
                'image' => $item['img'],
                'text' => $title, //substr($texto,0,100) . '...',
                'href'=> $item['url'],
                'lang' => $language
            ];
            $i++;
        }
        //dump($news);die;
        return $news;
        //dump($response);
    }

    private function LeerRSS($feedURL, $language){

        $i = 0; 
        $url = $feedURL; 
        $rss = simplexml_load_file($url); 
        $articles = $rss->entry;
        // foreach($articles as $article){
        //     echo $article->id . '<br>';
        // }
        // die;
        //dump($language);
        foreach($articles as $item) { 
            try{
                $link = strip_tags($item->id);  //extrae el link
                $title = explode('"',$item->title)[0];  //extrae el titulo

                if ($language == "en") {
                    $title = GoogleTranslate::trans($title, 'es');
                }
                //dump($title);die;
    
                //Hago un split del string $datos empleando "explode" para encontrar url de imagen de la noticia
                $datos = explode('<img alt="', $item->content);
                
                //dump(explode('"',$datos[1]));
                //dump($datos);
                
                //Cargo una imagen por defecto sino se encuentra la de la noticia.
                $imagen = "/img/news365.png";
                
                // Si encuentro en la posición 1 del array el string " src=" cargo la url de la imagen de la posición siguiente la 2
                if (explode('"',$datos[1])[1] == " src=") {
                    $imagen = explode('"',$datos[1])[2];
                }
                // foreach ($datos as $dato){
                    
                //     if (strpos($dato,'src') == 1) {  
                //         $imagen = explode('"',$dato)[2];
                //         //dump($imagen);
                //     }
                // }
                // $autor = $item->author->name;
                // $date = $item->pubDate;  //extrae la fecha
                // $guid = $item->guid;  //extrae el link de la imagen
                // $description = strip_tags($item->description);  //extrae la descripcion
                // if (strlen($description) > 400) { //limita la descripcion a 400 caracteres
                // $stringCut = substr($description, 0, 200);                   
                // $description = substr($stringCut, 0, strrpos($stringCut, ' ')).'...';}
                
                
            }
            catch(\Exception $e) {
                //echo $e->getMessage(); 
                
            }
            $news[] = [
                'id'    => $i,
                'image' => $imagen,
                'text' => $title, //substr($texto,0,100) . '...',
                'href'=> $link,
                'lang' => $language
            ];
            $i++;
        }
        //die;
        return $news;    
    }

    
}
