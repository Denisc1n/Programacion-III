<?php

    require_once "vendor\autoload.php";
    require_once "classes\usuarioApi.php";
    require_once "classes\middleware.php";

    use \Psr\Http\Message\ServerRequestInterface as Request;
    use \Psr\Http\Message\ResponseInterface as Response;   

    $config["displayErrorDetails"]    = true;
    $config["addContentLengthHeader"] = false;

    $app = new \Slim\App(["settings" => $config]);

    
    $app->group('/', function(){

        $tokenMiddleWare = function(Request $request, Response $response, $next){
            $response->write('<br>Entro al MiddleWare<br>');
            $decoding = Middleware::validarToken($request,$response);
            var_dump($decoding->data);
            $response = $next($request,$response,$decoding);
            $response->write('<br>Sali del MiddleWare<br>');
            return $response;
        };
    
        $tipoUsuarioMiddleWare = function(Request $request,Response $response,$payload){
            $response->write('<br>MiddlewareDelPayloads<br>');
            return $response;
        };
        

        $this->post('usuario[/]', UsuarioApi::class . ":guardarUsuario");
        $this->post('login[/]', UsuarioApi::class . ":loginUsuario");
        $this->get('usuario[/]', UsuarioApi::class . ":traerUsuarios")->add($tipoUsuarioMiddleWare)->add($tokenMiddleWare);
    });

    //eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1NjA5MDQ0NjYsImV4cCI6MTU2MDkwNDQ5NiwiZGF0YSI6ImFkbWluIn0.MB0JSuoHkVucZCmEQUzOeU4TUaPlCREudP41xFH_O8w
    $app->run();

?>