<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Goutte\Client;
use PhpParser\Node\Stmt\TryCatch;
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
        
        $titulo = $request->query('text');
        $language = $request->query('lang');
        if ($language == "en") {
            $titulo = GoogleTranslate::trans($titulo, 'es');
        }

        $noticia=[
            'imagen' => $request->query('image'),
            'titulo' => $titulo,
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
        $domain = "http://35.215.215.65";

        switch ($news) {
            case "tech":
                $url = "https://www.theverge.com/spacex";
                $filter = ".c-compact-river__entry";
                $language = "en";
                $articulos1 = $this->LeerRSS('https://www.theverge.com/rss/index.xml', $language);
                $i = count($articulos1);
                $articulos2 = $this->LeerAPI($domain .'/news/tech/techxataka.json', "es", $i);
                return array_merge($articulos1, $articulos2);
                //dump($this->LeerRSS('https://www.theverge.com/rss/index.xml',$language));
                //die;
                break;
            case "inter":
                // $this->BuscarNegocios("hamburguesas playa del carmen");
                // die;
                $articulos = $this->LeerAPI($domain . '/news/world/bbcmundo.json', "es", $i);
                return $articulos;
                break;    
            case "pol":
                // $this->BuscarNegocios("hamburguesas playa del carmen");
                // die;
                return $news=[];
                break;
            case "fin":
                $url = "https://finviz.com/news.ashx";
                $filter = '.content > div > div > div > table > tr > td > table > tr > td > a';
                ///html/body/div[2]/div/div/div/table/tbody/tr[2]/td[1]/table/tbody/tr[1]
                $language = "en";
                return $this->buscar_noticias($url, $filter, $language );
                break;
            default:
                $url = "https://www.noticias24.com/";
                $filter = ".container-fluid > div > div > div > div > div > article";
        }

        try {
            $crawler = $this->scraping_url($url);
        } catch (\Throwable $th) {
            return $this->news[]=[];
        }
        
        //dump($crawler->filter($filter)->html());die;

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
            
            //echo $node->html();die;
            $this->imagen = "/img/news365.png";
            $this->texto = "NO HAY NOTICIA";
            $this->link = "/";
            try {
                // echo $node->filter('a > amp-img')->attr('alt') . '<br>';
                // echo $node->filter('a > amp-img')->attr('src') . '<br>';
                // echo $node->filter('a')->attr('href') . "<br><br>";//code...
                $this->texto = $node->filter('a > amp-img')->attr('alt');
                $this->link = $node->filter('a')->attr('href');
                $this->imagen =$node->filter('a > amp-img')->attr('src');

                $this->news[] = [
                    'id'    => $i,
                    'image' => $this->imagen,
                    'text' => $this->texto, //substr($texto,0,100) . '...',
                    'href'=> $this->link,
                    'lang' => $language
                ];
            

            } catch (\Throwable $th) {
                //throw $th;
            }
            
            // if ($i>0) {
                
            //     //$imagen = $node->filter('amp-img')->attr('src');
            //     $imagen = "/img/news385.png";
            //     $texto = $node->text();
            //     dump($texto);die;
            //     $link =  $node->filter('a')->attr('href');
            // }
            
            //$image_content = file_get_contents($imagen);
            //file_put_contents('images/' . $i . '.jpg', $image_content);
            $i++;
        });
        //die;
        //dump($this->news);die;
        
        return $this->news;
    }

    private function json_noticias(){

    }

    private function buscar_noticias($url, $filter, $language ){

        $crawler = $this->scraping_url($url);
        $i = 0;
        //echo $filter; 
        //dump($crawler->filter($filter)->html());die;

        $crawler->filter($filter)->each(function ($node) use (&$i, &$language) {
        
            
            $texto = $node->text();
            // if ($language == "en") {
            //     $texto = GoogleTranslate::trans($texto, 'es');
            // }
            $link = $node->filter('a')->attr('href');
            $imagen = "/img/news365.png";//"https://finviz.com/gfx/news/logo/5.png";
            //echo $texto . "<br>";
            //echo $link . "<br>";
            
            

            // if ($i == 3){
            //      die;
            // }
            // //$texto = $node->text();
            //$link =  $node->filter('a')->attr('href');
            $this->news[] = [
                'id'    => $i,
                'image' => $imagen,
                'text' => $texto, //substr($texto,0,100) . '...',
                'href'=> $link,
                'lang' => $language
            ];
        
            
            //$image_content = file_get_contents($imagen);
            //file_put_contents('images/' . $i . '.jpg', $image_content);
            $i++;
        });
        //dump($this->news);die;
        //die;
        return $this->news;

    }

    private function scraping_url($url){
        try {
            $client = new Client();
            return $client->request('GET', $url);
        } catch (\Throwable $th) {
            throw $th;
        }

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
                $texto .= $node->text() . "<br><br>";
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
            //dump($e->getMessage());
        }
        //echo $traducido;die;
        //dump($traducido);die;
        return $traducido;
    }

    private function LeerAPI($rute, $language, $i){
        //dump($url);die;
      
        $response = Http::get($rute)->json();

        foreach($response as $item){
            //$title = $item['title'];
            $title =  ($language == "en") ? GoogleTranslate::trans($item['title'], 'es') : $item['title'];
             
            //dump($item['img']); 
           
            $imagen = ($item['img'] == null) ? "img/news365.png" : $item['img'];
            
            $news[] = [
                'id'    => $i,
                'image' => $imagen,
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

    private function QueryCampo ($json, $campo, $defecto) {
        $resultado = $defecto;
        try {
            $resultado = $json['result'][$campo]; //code...
        } catch (\Throwable $th) {
            //throw $th;
        }
        return $resultado;
    }

    private function BuscarNegocios($query){
        $url = "https://maps.googleapis.com/maps/api/place/textsearch/json?key=AIzaSyBTy-SCuolM7qtHuQ39hiIHamckM6HXUQU&query=" . $query;
        
        $negocios = Http::get($url)->json();
        //dump($negocios['results']);die;
        foreach($negocios['results'] as $negocio){
            
            try {
                $website = "No tiene";
                $telefono = "xxx";
                $reclamar = False;
                $num_califica = 0;
                $name = $negocio['name'];
                $id = $negocio['place_id'];
                //dump($name);
                $url_details = "https://maps.googleapis.com/maps/api/place/details/json?place_id=" . $id . "&key=AIzaSyBTy-SCuolM7qtHuQ39hiIHamckM6HXUQU";
                $detalle = Http::get($url_details)->json();
                $url = $detalle['result']['url'];
                $direccion = $detalle['result']['formatted_address'];
                //$num_califica = $detalle['result']['user_ratings_total'];

                $num_califica = $this->QueryCampo($detalle, 'user_ratings_total', 0);
                $telefono = $this->QueryCampo($detalle,'international_phone_number', 'xxx-xxxxxxx');
                $num_califica = $this->QueryCampo($detalle,'user_ratings_total', 0);
                $website = $this->QueryCampo($detalle,'website', 'No tiene');
                
                
                //dump($detalle['result']);die;
                // $pagina = file_get_contents($url);
                // if (strpos($pagina, 'Reclamar esta empresa') != 0){
                //     $reclamar = True;
                // }

                //$crawler = $this->scraping_url($url);
                //echo "uno"; dump($crawler);
                // $url= "https://www.google.com/maps/place/DentalSPA,+Dentistas+en+Playa+del+Carmen,+10+avenida+nte+y+calle+1ra+Sur+Centro,+Calica,+77710+Playa+del+Carmen,+Q.R./@20.622406,-87.076704,17z/data=!4m2!3m1!1s0x8f4e433bfcbd3e31:0x8b20857403223375";
                // $crawler = $this->scraping_url($url);
                // echo "dos"; dump($crawler);die;
                //$extracto = $crawler->filter('html > body > jsl')->html();

                // dump($extracto);die;
                // // $texto = "Empieza aqui:";
                
                // $crawler->filter('.app > div')->each(function ($node) use (&$texto) {
                //     $texto .= $node->text();
                //     dump($node);
                // });
                // die;


            }
            catch(\Exception $e) {

                echo $e->getMessage(); 
                
            }

            $empresas[] = [
                'nombre'    => $name,
                'direccion' => $direccion,
                'telefono'=> $telefono,
                'website'=> $website,
                'id' => $id,
                'Por_reclamar' => $reclamar, //substr($texto,0,100) . '...',
                'url'=> $url,
                'revisiones'=> $num_califica
            ];          
        }
        dump($empresas);die;
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
