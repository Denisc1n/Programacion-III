<?php
    include_once("Entidades/Token.php");
    include_once("Entidades/Mesa.php");

    class MesaApi extends Mesa
    {
        public function RegistrarMesa($request, $response, $args)
        {
            $parametros = $request->getParsedBody();
            $codigo = $parametros["codigo"];
            $respuesta = Mesa::Registrar($codigo);

            if($respuesta["Cantidad"]>0)
            {
                 $respuesta["Resultado"]="Mesa $codigo registrada correctamente.";
            }
            else
            {
                $respuesta["Resultado"]="El codigo de mesa ya existe!";
            }

            $newResponse = $response->withJson($respuesta["Resultado"], 200);
            return $newResponse;
        }



        public function ListarMesas($request, $response, $args)
        {
            $respuesta = Mesa::Listar();
            $newResponse = $response->withJson($respuesta, 200);
            return $newResponse;
        }



        public function BajaMesa($request, $response, $args)
        {
            $codigo = $args["codigo"];
            $respuesta = Mesa::Baja($codigo);
            
            if($respuesta["Cantidad"]>0)
            {
                 $respuesta["Resultado"]="Mesa $codigo dada de baja correctamente.";
            }
            else
            {
                $respuesta["Resultado"]="Codigo de mesa incorrecto!";
            }

            $newResponse = $response->withJson($respuesta["Resultado"], 200);
            return $newResponse;
        }



        public function ActualizarFotoMesa($request, $response, $args)
        {
            $parametros = $request->getParsedBody();
            $files = $request->getUploadedFiles();
            $codigoMesa = $parametros["codigo"];
            $foto = $files["foto"];

            $mediaType = $foto->getClientMediaType();
            $retorno = "";
            switch ($mediaType) {
                case "image/jpeg":
                    $retorno = ".jpg";
                    break;
                case "image/png":
                    $retorno = ".png";
                    break;
                default:
                    $retorno = "ERROR";
                    break;
            }
            $ext = $retorno;

            if ($ext != "ERROR") {

                $rutaFoto = "./fotos/" . $codigoMesa . $ext;

                $foto->moveTo($rutaFoto);

                $respuesta = Mesa::ActualizarFoto($rutaFoto, $codigoMesa);
                $newResponse = $response->withJson($respuesta, 200);
                return $newResponse;
            } else {
                $respuesta = "Solo archivos jpg o png.";
                $newResponse = $response->withJson($respuesta, 200);
                return $newResponse;
            }
        }



        public function EsperandoPedido($request, $response, $args)
        {
            $codigo = $args["codigo"];
            $respuesta = Mesa::EstadoEsperandoPedido($codigo);
            $newResponse = $response->withJson($respuesta, 200);
            return $newResponse;
        }

        public function Comiendo($request, $response, $args)
        {
            $codigo = $args["codigo"];
            $respuesta = Mesa::EstadoComiendo($codigo);
            $newResponse = $response->withJson($respuesta, 200);
            return $newResponse;
        }

        public function Pagando($request, $response, $args)
        {
            $codigo = $args["codigo"];
            $respuesta = Mesa::EstadoPagando($codigo);
            $newResponse = $response->withJson($respuesta, 200);
            return $newResponse;
        }

        public function Cerrada($request, $response, $args)
        {
            $codigo = $args["codigo"];
            $respuesta = Mesa::EstadoCerrada($codigo);
            $newResponse = $response->withJson($respuesta, 200);
            return $newResponse;
        }

        //ESTADISTICAS

        public function MesaMasUsada($request, $response, $args)
        {
            $respuesta = Mesa::MasUsada();
            $newResponse = $response->withJson($respuesta, 200);
            return $newResponse;
        }

        public function MesaMenosUsada($request, $response, $args)
        {
            $respuesta = Mesa::MenosUsada();
            $newResponse = $response->withJson($respuesta, 200);
            return $newResponse;
        }

        public function MesaMasFacturacion($request, $response, $args)
        {
            $respuesta = Mesa::MasFacturacion();
            $newResponse = $response->withJson($respuesta, 200);
            return $newResponse;
        }

        public function MesaMenosFacturacion($request, $response, $args)
        {
            $respuesta = Mesa::MenosFacturacion();
            $newResponse = $response->withJson($respuesta, 200);
            return $newResponse;
        }

        public function MesaMayorImporte($request, $response, $args)
        {
            $respuesta = Mesa::FacturaConMayorImporte();
            $newResponse = $response->withJson($respuesta, 200);
            return $newResponse;
        }

        public function MesaMenorImporte($request, $response, $args)
        {
            $respuesta = Mesa::FacturaConMenorImporte();
            $newResponse = $response->withJson($respuesta, 200);
            return $newResponse;
        }

        public function MesaFacturacionEntreFechas($request, $response, $args)
        {
            $parametros = $request->getParsedBody();
            $codigoMesa = $parametros["codigoMesa"];
            $fecha1 = $parametros["fecha1"];
            $fecha2 = $parametros["fecha2"];
            $mesas = Mesa::FacturacionEntreFechas($codigoMesa, $fecha1, $fecha2);

            foreach ($mesas as $mesa) {
            $respuesta[] = array("Cod: " . $mesa["codigoMesa"], "Fecha: " . $mesa["fecha"], "Importe: " . $mesa["importe"]);
            }


            $newResponse = $response->withJson($respuesta, 200);
            return $newResponse;
        }

        public function MesaMejorPuntuacion($request, $response, $args)
        {
            $mesa = Mesa::MejorPuntuacion();

            $respuesta[] = array("Cod: " . $mesa[0]["codigoMesa"], "Promedio_Puntaje: " . $mesa[0]["puntuacion_promedio"], "Comentario: " . $mesa[0]["comentario"]);

            $newResponse = $response->withJson($respuesta, 200);
            return $newResponse;
        }

        public function MesaPeorPuntuacion($request, $response, $args)
        {
            $mesa = Mesa::PeorPuntuacion();

            $respuesta[] = array("Cod: " . $mesa[0]["codigoMesa"], "Promedio_Puntaje: " . $mesa[0]["puntuacion_promedio"], "Comentario: " . $mesa[0]["comentario"]);

            $newResponse = $response->withJson($respuesta, 200);
            return $newResponse;
        }

    }
?>