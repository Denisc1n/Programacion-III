<?php

    require_once "vendor\autoload.php";
    require_once "classes\usuarioApi.php";
    require_once "classes\compraApi.php";
    require_once "classes\middleware.php";
    require_once "classes\historialDao.php";

    use \Psr\Http\Message\ServerRequestInterface as Request;
    use \Psr\Http\Message\ResponseInterface as Response;   

    $config["displayErrorDetails"]    = true;
    $config["addContentLengthHeader"] = false;
    date_default_timezone_set("America/Argentina/Buenos_Aires");
    $app = new \Slim\App(["settings" => $config]);
    $historialMiddleware = function( Request $req, Response $res, $next ){
        $packageReceived = $req->getHeader('token');
        $timestamp = time(); 
        
        $timestamp = date("H:i:s");
        $ruta = $_SERVER["REDIRECT_URL"];
        if($packageReceived != null)
        {
            $decode = MiddleWare::validarToken($req,$res);
            $uid = $decode->id;
            $usuario = UsuarioDAO::TraerUsuario($uid);
            $username = $usuario[0]->nombre;
            HistorialDao::InsertarHistorial( $username, $ruta, $timestamp);
            
        }else{
            HistorialDao::InsertarHistorial( "UsuarioNoLogueado", $ruta, $timestamp);
        }
        $res = $next($req,$res);
        return $res;
    };

    $app->group('/', function(){

        $tokenMiddleWare = function(Request $request, Response $response, $next){
            $decoding = Middleware::validarToken($request,$response);

            if( $decoding != "INVALID" )
            {
                $profile = $decoding->data;
                $response = $next($request->withAttribute('perfil', $profile),$response);
                return $response;
            }

            $response->write('<br>TOKEN INVALIDO<br>');
            return $response;
        };
    
        $tipoUsuarioMiddleWare = function(Request $request,Response $response,$next){
            $profile = $request->getAttribute('perfil');

            if($profile != "administrador"){
                $response->write("HOLA");
                return $response;
            }

            $response->write("ES ADMIN");
            $response = $next($request,$response);
            return $response;
        };

        
        

        $this->post('usuario[/]', UsuarioApi::class . ":guardarUsuario");
        $this->post('login[/]', UsuarioApi::class . ":loginUsuario");
        $this->post('compra[/]', CompraApi::class . ":guardarCompra")->add($tokenMiddleWare);
        $this->get('usuario[/]', UsuarioApi::class . ":traerUsuarios")->add($tipoUsuarioMiddleWare)->add($tokenMiddleWare);
        $this->get('compra[/]', CompraApi::class . ":traerCompras");
        
    })->add($historialMiddleware);

    //eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1NjEzMjIwMDMsImV4cCI6MTU2MTMyNTYwMywiZGF0YSI6InVzdWFyaW8iLCJpZCI6IjIifQ.xi70eaVbvFqYst7pmK5ac870FCUoa07d3XckinPXgLoES
    $app->run();

?>