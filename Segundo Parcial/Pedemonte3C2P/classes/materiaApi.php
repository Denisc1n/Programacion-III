<?php

    require_once "materiaDao.php";
    require_once "materia.php";
    require_once "middleware.php";

    use \Firebase\JWT\JWT;
    use \Psr\Http\Message\ServerRequestInterface as Request;
    use \Psr\Http\Message\ResponseInterface as Response;   

    class MateriaApi
    {
        public function guardarMateria( Request $req, Response $res, $args)
        {
            try
            {
                $dataReceived       = $req->getParsedBody();

                if(sizeof($dataReceived) < 2){
                    return $res->write("No se recibieron datos. O los datos son insuficientes.");
                }
                $decoding = Middleware::validarToken($req,$res);
                $perfil = $decoding->data;

                if(strtolower($perfil) != "admin"){
                    return $res->write("PERFIL NO HABILITADO PARA CARGAR MATERIAS");
                }
        
                $materia = new Materia($dataReceived["nombre"], $dataReceived["cuatrimestre"],$dataReceived['cupos'] );
                $idcreado = MateriaDAO::InsertarMateria($materia);

                
                return $res->write("Materia Guardada con Exito. Id Materia: ".$idcreado);
            } catch( exception $e ) {
                print "Error!!!<br/>" . $e->getMessage();
                die();
            }
        }

        function traerCompras(Request $req, Response $res, $args )
        {
            $decoding = Middleware::validarToken($req,$res);
            $perfil = $decoding->data;

            if($perfil == "administrador")
            {
                echo("Es admin");
               // $compras = CompraDao::TraerTodasLasCompras();
               // $respuestaCompras = $res->withJson($compras,200);
               // return $respuestaCompras;
            }
            else{
                $uid = $decoding->id;
                $compras = CompraDao::TraerTodasLasComprasDeUnUsuario((int)$uid);
                $respuestaCompras = $res->withJson($compras,200);
                return $respuestaCompras;
            }

            
        }
    }



?>