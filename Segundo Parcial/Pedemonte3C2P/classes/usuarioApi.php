<?php
require_once "usuarioDao.php";
require_once "usuario.php";
require_once "middleware.php";

use \Firebase\JWT\JWT;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;   

class UsuarioApi
{
    public function guardarUsuario( Request $req, Response $res, $args)
     {
        try
        {
            $dataReceived       = $req->getParsedBody();
            
            if(sizeof($dataReceived) < 2){
                return $res->write("No se recibieron datos. O los datos son insuficientes.");
            }
            if( strtolower($dataReceived["perfil"]) != "admin" && 
                strtolower($dataReceived["perfil"]) != "alumno" &&
                    strtolower($dataReceived["perfil"]) != "profesor"){
                        $res->write("Perfil invalido. Opciones: Admin, Alumno, Profesor.");
                        return $res;
                    }
            
            $usuariosRepetidos = UsuarioDao::TraerUsuarioPorNombre(strtolower($dataReceived["nombre"]));
            if(sizeof($usuariosRepetidos)>0){
                return $res->write("Usuario ya existe");
            }
            $usuario = new Usuario(strtolower($dataReceived["nombre"]), $dataReceived["clave"],$dataReceived['perfil'] );
            $id = UsuarioDAO::InsertarUsuario($usuario);
        
          $res->write("Usuario Guardado con Exito. Legajo: ".$id);
                    return $res;
        } catch( exception $e ) {
            print "Error!!!<br/>" . $e->getMessage();
            die();
        }
    }

    public function loginUsuario( Request $req, Response $res, $args) {

        $dataReceived       = $req->getParsedBody();

        if(sizeof($dataReceived) < 1){
            return $res->write("No se recibieron datos. O los datos son insuficientes.");
        }

        $usuario = $dataReceived['legajo'];
        $password = $dataReceived['clave'];


        $salida = UsuarioDao::ValidarCredenciales($usuario,$password);
    
        if($salida){

            $currentTime = time();
            $payload = array(
                'iat' => $currentTime,
                'exp' => $currentTime+3600,
                'data' => $salida->tipo,
                'id' => $salida->id
            );
            $token = JWT::encode($payload,'serverkey');

            return $res->write($token);
        }
        else{
            return $res->write("Credenciales Incorrectas");
        }
        
    }


    public function modificarUsuario( Request $req, Response $res, $args) {

        $dataReceived       = $req->getParsedBody();

        if(sizeof($dataReceived) == 0){
            return $res->write("No se recibieron datos. O los datos son insuficientes.");
        }

        $decoding = Middleware::validarToken($req,$res);
        $email;
        $foto;
        $receivedDataArray = array();
        if(strtolower($decoding->data) == "alumno"){
            if(!isset($dataReceived["email"]) || !isset($_FILES["foto"]) )
                return $res->write("DATOS INSUFICIENTES PARA EDITAR PERFIL ALUMNO");

            $email = $dataReceived["email"];

            $origen = $_FILES["foto"]["tmp_name"];
            $uploadedFileOriginalName = $_FILES["foto"]["name"];
            $ext = pathinfo($uploadedFileOriginalName, PATHINFO_EXTENSION);
            $fileDestination = "IMG/".$decoding->data."_".$decoding->id.".".$ext;
            move_uploaded_file($origen, $fileDestination);
            array_push($receivedDataArray,$email,$fileDestination,$decoding->id);
            UsuarioDao::modificarUsuario($receivedDataArray, "alumno");
            return $res->write("Perfil editado");
        }
        else if(strtolower($decoding->data) == "profesor"){
            if(!isset($dataReceived["email"]) || !isset($dataReceived["materiasdictadas"]) )
                return $res->write("DATOS INSUFICIENTES PARA EDITAR PERFIL profesor");

                $email = $dataReceived["email"];
                $materias = $dataReceived["materiasdictadas"];

                array_push($receivedDataArray, $email, $materias, $decoding->id);
                UsuarioDao::modificarUsuario($receivedDataArray, "profesor");
                return $res->write("Perfil editado");
        }

        else if(strtolower($decoding->data) == "admin"){
           

                $email = $dataReceived["email"] ?? "";
                $materias = $dataReceived["materiasdictadas"] ??"";
                $legajo = $args["legajo"];

                $origen = $_FILES["foto"]["tmp_name"];
                $uploadedFileOriginalName = $_FILES["foto"]["name"];
                $ext = pathinfo($uploadedFileOriginalName, PATHINFO_EXTENSION);
                $fileDestination = "IMG/".$decoding->data."_".$decoding->id.".".$ext;
                move_uploaded_file($origen, $fileDestination);

                $foto = $fileDestination;

                array_push($receivedDataArray, $email, $materias, $foto, $legajo);

                UsuarioDao::modificarUsuario($receivedDataArray, "admin");
                return $res->write("Perfil editado");
        }


        
    }

    public function inscribirUsuario( Request $req, Response $res, $args) {

        $dataReceived       = $req->getParsedBody();

        $decoding = Middleware::validarToken($req,$res);

        if(strtolower($decoding->data) != "alumno"){
            return $res->write("SOLO SE INSCRIBEN ALUMNOS");
        }
        $materia = $args["idmateria"];
        $id = $decoding->data;

        UsuarioDAO::Inscribir($materia,$id);
    }

    function traerUsuarios(Request $req, Response $res, $args )
    {
       $usuarios = UsuarioDAO::TraerTodosLosUsuarios();

       $respuestaUsuarios = $res->withJson($usuarios,200);

       return $respuestaUsuarios;
    }

}

?>