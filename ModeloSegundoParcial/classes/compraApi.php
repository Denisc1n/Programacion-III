<?php

    require_once "compraDao.php";
    require_once "compra.php";

    use \Firebase\JWT\JWT;
    use \Psr\Http\Message\ServerRequestInterface as Request;
    use \Psr\Http\Message\ResponseInterface as Response;   

    class CompraApi
    {
        public function guardarCompra( Request $req, Response $res, $args)
        {
            try
            {
                $dataReceived       = $req->getParsedBody();

                if(sizeof($dataReceived) < 3){
                    return $res->write("No se recibieron datos. O los datos son insuficientes.");
                }
                $decoding = Middleware::validarToken($req,$res);
                $uid = $decoding->id;
        
                $compra = new Compra($dataReceived["articulo"], $dataReceived["fecha"],$dataReceived['precio'],$uid );
                $compra->fecha = date("Y-m-d", strtotime($compra->fecha));
                $valor = CompraDao::InsertarCompra($compra);

                $origen = $_FILES["imageInput"]["tmp_name"];
                $uploadedFileOriginalName = $_FILES["imageInput"]["name"];
                $ext = pathinfo($uploadedFileOriginalName, PATHINFO_EXTENSION);
                $fileDestination = "IMGCompras/".$compra->articulo."_".$valor.".".$ext;
                move_uploaded_file($origen, $fileDestination);
                return $res->write("Compra Guardada con Exito.");
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