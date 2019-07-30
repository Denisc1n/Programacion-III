<?php
    class MDWEmpleado
    {
        public static function ValidarToken($request, $response, $next)
        {
            $token = $request->getHeader("token");
            $validacionToken = Token::DecodificarToken($token[0]);
            if ($validacionToken["Estado"] == "OK") {
                $request = $request->withAttribute("payload", $validacionToken);   //ver ObtenerData del 2doParcial
                return $next($request, $response);
            } else {
                $newResponse = $response->withJson($validacionToken, 200);
                return $newResponse;
            }
        }


        public static function ValidarSocio($request, $response, $next)
        {
            $payload = $request->getAttribute("payload")["Payload"];

            if ($payload->perfil == "socio") {
                return $next($request, $response);
            } else {
                $respuesta = array("Estado" => "ERROR", "Mensaje" => "Solo categoria socio.");
                $newResponse = $response->withJson($respuesta, 200);
                return $newResponse;
            }
        }


        public static function ValidarMozo($request, $response, $next)
        {
            $payload = $request->getAttribute("payload")["Payload"];
            $tipoEmpleado = $payload->perfil;
            if ($tipoEmpleado == "mozo" || $tipoEmpleado == "socio") {
                return $next($request, $response);
            } else {
                $respuesta = array("Estado" => "ERROR", "Mensaje" => "No tienes permiso para realizar esta accion (Solo categoria mozo).");
                $newResponse = $response->withJson($respuesta, 200);
                return $newResponse;
            }
        }


        public static function FiltrarPedidos($request, $response, $next) {

            $objDelaRespuesta= new stdclass();
            $objDelaRespuesta->respuesta="";

            $payload = $request->getAttribute("payload")["Payload"];
            $perfil = $payload->perfil;

              //va a la api
            $response = $next($request, $response);
              //vuelve de la api con la info
            $pedidos = json_decode($response->getBody()->__toString());
            if($perfil != "socio") {
                if (is_array($pedidos)) {
                    foreach ($pedidos as $key => $pedido) {

                        if ($perfil == "bartender" && $pedido->sector != "entrada" )
                            unset($pedidos[$key]);
                        
                        if ($perfil == "cervecero" && $pedido->sector != "barra" )
                            unset($pedidos[$key]);

                        if ($perfil == "cocinero" && $pedido->sector != "cocina" )
                            unset($pedidos[$key]);

                    }
                }
                $objDelaRespuesta->perfil = $perfil;
                $objDelaRespuesta->lista = $pedidos;            
                  
            } else if($perfil == "socio") {
                $objDelaRespuesta->perfil = $perfil;
                $objDelaRespuesta->lista = $pedidos;
            }

            if($objDelaRespuesta->respuesta!="") {
              $nueva=$response->withJson($objDelaRespuesta, 401);
              return $nueva;
            }

            $nueva=$response->withJson($objDelaRespuesta, 200);
            return $nueva;
        }


    }
?>