<?php
require_once "usuarioDao.php";
require_once "usuario.php";

use \Firebase\JWT\JWT;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;   

class UsuarioApi
{
    public function guardarUsuario( Request $req, Response $res, $args) {

        $dataReceived       = $req->getParsedBody();
        

        if(sizeof($dataReceived) < 3){
            return $res->write("No se recibieron datos. O los datos son insuficientes.");
        }

        $usuario = new Usuario($dataReceived["nombre"], $dataReceived["clave"],$dataReceived['sexo'],$dataReceived['perfil'] );
        UsuarioDAO::InsertarUsuario($usuario);
        return $res->write("GRABO");
    }

    public function loginUsuario( Request $req, Response $res, $args) {

        $dataReceived       = $req->getParsedBody();

        if(sizeof($dataReceived) < 2){
            return $res->write("No se recibieron datos. O los datos son insuficientes.");
        }

        $usuario = $dataReceived['nombre'];
        $password = $dataReceived['clave'];


        $salida = UsuarioDao::ValidarCredenciales($usuario,$password);

        if($salida){

            $currentTime = time();
            $payload = array(
                'iat' => $currentTime,
                'exp' => $currentTime+3600,
                'data' => $salida->perfil
            );
            $token = JWT::encode($payload,'serverkey');

            return $res->write($token);
        }
        else{
            return $res->write("Credenciales Incorrectas");
        }
        
    }

    function traerUsuarios(Request $req, Response $res, $args )
    {
       $usuarios = UsuarioDAO::TraerTodosLosUsuarios();

       $respuestaUsuarios = $res->withJson($usuarios,200);

       return $respuestaUsuarios;
    }

}

?>