<?php

    require_once "vendor\autoload.php";
    require_once "classes\usuarioApi.php";
    require_once "classes\materiaApi.php";


    use \Psr\Http\Message\ServerRequestInterface as Request;
    use \Psr\Http\Message\ResponseInterface as Response;   

    $config["displayErrorDetails"]    = true;
    $config["addContentLengthHeader"] = false;
    date_default_timezone_set("America/Argentina/Buenos_Aires");
    $app = new \Slim\App(["settings" => $config]);

    $app->group('/', function(){

        $this->post('usuario[/]', UsuarioApi::class . ":guardarUsuario");
        $this->post('login[/]', UsuarioApi::class . ":loginUsuario");
        $this->post('materia[/]', MateriaApi::class . ":guardarMateria");
        $this->post('usuario/{legajo}', UsuarioApi::class . ":modificarUsuario");
        $this->post('inscripcion/{idmateria}', UsuarioApi::class . ":inscribirUsuario");
    });

    $app->run();

?>