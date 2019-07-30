<?php
    include_once("Entidades/Token.php");
    include_once("Entidades/Pedido.php");
    include_once("Entidades/Mesa.php");

    class PedidoAPI extends Pedido
    {
        public function RegistrarPedido($request, $response, $args)
        {
            $parametros = $request->getParsedBody();
            $id_mesa = $parametros["id_mesa"];
            $pedido = $parametros["pedido"];
            $cantidad = $parametros["cantidad"];
            $importe = $parametros["importe"];
            $nombre_cliente = $parametros["cliente"];
            $sector = $parametros["sector"];
            $payload = $request->getAttribute("payload")["Payload"];
            $mozo = $payload->nombre;

            $respuesta = Pedido::Registrar($id_mesa, $pedido, $cantidad, $importe, $mozo, $nombre_cliente, $sector);
            Mesa::EstadoEsperandoPedido($id_mesa);

            $newResponse = $response->withJson($respuesta, 200);
            return $newResponse;
        }




        public function CancelarPedido($request, $response, $args)
        {
            $codigo = $args["codigo"];
            $respuesta = Pedido::Cancelar($codigo);
            //Mesa::EstadoCerrada($codigo);

            if($respuesta["Cantidad"]>0)
            {
                 $respuesta["Resultado"]="Pedido $codigo cancelado!";
            }
            else
            {
                $respuesta["Resultado"]="No se encontro el pedido!";
            }

            $newResponse = $response->withJson($respuesta["Resultado"], 200);
            return $newResponse;
        }




        public function TomarPedidoPendiente($request, $response, $args)
        {
            $parametros = $request->getParsedBody();
            $codigo = $parametros["codigo"];
            $minutosEstimados = $parametros["minutosEstimados"];
            $mesa = $parametros["mesa"];
            $payload = $request->getAttribute("payload")["Payload"];
            $encargadoId = $payload->id;
            $encargadoPerfil = $payload->perfil;
            $respuesta = Pedido::TomarPedido($codigo, $encargadoId, $encargadoPerfil, $minutosEstimados, $mesa);
            
            if($respuesta["Cantidad"]>0)
            {
                 $respuesta["Resultado"]="Pedido tomado por: " . $encargadoId;
                 Mesa::EstadoEsperandoPedido($mesa);
            }
            else
            {
                $respuesta["Resultado"]="No se encontro el pedido!";
            }

            $newResponse = $response->withJson($respuesta["Resultado"], 200); //$respuesta["Resultado"]
            return $newResponse;
        }



        public function InformarPedidoListo($request, $response, $args)
        {
            $parametros = $request->getParsedBody();
            $codigo = $parametros["codigo"];
            $respuesta = Pedido::InformarListoParaServir($codigo);

            if($respuesta["Cantidad"]>0)
            {
                 $respuesta["Resultado"]="Pedido listo para servir.";
            }
            else
            {
                $respuesta["Resultado"]="Pedido incorrecto, verificar nro ID y que el estado sea 'En Preparacion'";
            }

            $newResponse = $response->withJson($respuesta["Resultado"], 200);
            return $newResponse;
        }



        public function ServirPedido($request, $response, $args)
        {
            $parametros = $request->getParsedBody();
            $codigo = $parametros["codigo"];
            $respuesta = Pedido::InformarPedidoServido($codigo);
            $mesa = $respuesta["Mesa"];

            if($respuesta["Cantidad"]>0)
            {
                $respuesta["Resultado"]="Pedido servido.";
                Mesa::EstadoComiendo($mesa); //ver funcion que verifique que todos los pedidos estan servidos          
            }
            else
            {
                $respuesta["Resultado"]="Pedido incorrecto, verificar nro ID y que el estado sea 'Listo para servir'";
            }

            $newResponse = $response->withJson($respuesta["Resultado"], 200);
            return $newResponse;
        }



        public function CobrarPedidosMesa($request, $response, $args)
        {
            $parametros = $request->getParsedBody();
            $codigoMesa = $parametros["codigoMesa"];
            $respuesta = Pedido::CobrarPedidos($codigoMesa);

            $newResponse = $response->withJson($respuesta, 200);
            return $newResponse;
        }




        public function TiempoRestantePedido($request, $response, $args)
        {
            $codigo = $args["codigoPedido"];
            $respuesta = Pedido::TiempoRestante($codigo);
            $newResponse = $response->withJson($respuesta, 200);
            return $newResponse;
        }


        public function ListarTodosPedidos($request, $response, $args)
        {
            $respuesta = Pedido::ListarTodos();
            $newResponse = $response->withJson($respuesta, 200);
            return $newResponse;
        }


        /*
        public function ListarPedidoPendientes($request, $response, $args)
        {
            $respuesta = Pedido::ListarPendientes();
            $newResponse = $response->withJson($respuesta, 200);
            return $newResponse;
        }*/


        public function LoMasVendido($request, $response, $args)
        {
            $respuesta = Pedido::MasVendido();
            $newResponse = $response->withJson($respuesta, 200);
            return $newResponse;
        }


        public function LoMenosVendido($request, $response, $args)
        {
            $respuesta = Pedido::MenosVendido();
            $newResponse = $response->withJson($respuesta, 200);
            return $newResponse;
        }


        public function ListarPedidosFueradeTiempo($request, $response, $args)
        {
            $respuesta = Pedido::ListarFueraDelTiempoEstipulado();
            $newResponse = (count($respuesta) > 0) ? $response->withJson($respuesta, 200) : "No hay pedidos fuera de tiempo";
            return $newResponse;
        }


        public function ListarPedidosCancelados($request, $response, $args)
        {
            $respuesta = Pedido::ListarCancelados();
            $newResponse = (count($respuesta) > 0) ? $response->withJson($respuesta, 200) : "No hay pedidos cancelados";
            return $newResponse;
        }

    }
?>