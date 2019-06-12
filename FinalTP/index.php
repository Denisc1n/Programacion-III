<?php

    require_once 'vendor\autoload.php';
    require_once './classes/authApi.php';

    use \Psr\Http\Message\ServerRequestInterface as Request;
    use \Psr\Http\Message\ResponseInterface as Response;   

    $config["displayErrorDetails"]    = true;
    $config["addContentLengthHeader"] = false;

    $app = new \Slim\App(["settings" => $config]);

    $app->group('/basicFunctions', function(){

        $this->get('/FuncionMuestraNombre/[{nombre}]', function ( Request $req, Response $res, $args) {

            $value = $args['nombre'] ?? "Vacio";
    
            $res->write("Parametro recibido por get: ". $value);
            return $res;
        });
    //
        $this->post('/receiveDataFromPost/', function ( Request $req, Response $res, $args) {
            $dataReceived       = $req->getParsedBody();
            $object             = new stdclass();
            if(sizeof($dataReceived) < 3){
                return $res->write("No se recibieron datos. O los datos son insuficientes.");
            }
            $object->nombre     = $dataReceived["nombre"] ;
            $object->apellido   = $dataReceived["apellido"] ;
            $object->edad       = $dataReceived["edad"] ;
            $object->id         = $dataReceived["id"] ;
            
            $res->write("<br>"."Nombre:". $object->nombre."<br>"."Apellido:". $object->apellido
                ."<br>"."Edad:".$object->edad."<br>"."ID:".$object->id);
    
            return $res;
        });
        
    });

    $middleWare = function($request, $response, $next){
        $response->write('<br>Entro al MiddleWare<br>');
        authApi::validate($request,$response);
        $response = $next($request,$response);
        $response->write('<br>Sali del MiddleWare<br>');
        return $response;
    };

    $segundoMiddleWare = function($request, $response, $next){
        $response->write('<br>Entro al segundoMiddleWare<br>');
        $response = $next($request,$response);
        $response->write('<br>Sali del segundoMiddleWare<br>');
        return $response;
    };
    

    $app->group('/tokenFunctions', function(){
        $this->post('/login[/]', authApi::class. ':login');
        $this->post('/validarToken[/]',authApi::class. ':validate');
    })->add($middleWare)->add($segundoMiddleWare);

//
//$app->put('[/]{id}[/{nombre}][/{apellido}][/{direccion}][/{ciudad}][/{edad}]', function ( Request $req, Response $res, $args = []) {
//    $res->getBody()->write("Datos a insertar:"."<br>"."NOMBRE:".$args['nombre']."<br>"."APELLIDO:".$args['apellido']);
//    $res->getBody()->write("EDAD:".$args['edad']."<br>"."DIRECCION:".$args['direccion']);
//    return $res;
//});
//
//$app->delete('[/]', function ( Request $req, Response $res, $args = []) {
//    $res->withStatus(400)->write('Bad Request');
//    return $res;
//});
    $app->run();
?>