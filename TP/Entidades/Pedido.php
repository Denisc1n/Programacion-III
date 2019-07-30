<?php
include_once("AccesoDatos.php");

    class Pedido
    {

        public static function Registrar($id_mesa, $pedido, $cantidad, $importe, $mozo, $nombre_cliente, $sector)
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            try {
                $codigo = substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyz", 5)), 0, 5);

                date_default_timezone_set("America/Argentina/Buenos_Aires");
                $fecha = date('Y-m-d');
                $hora_pedido = date('H:i');

                $consulta = $objetoAccesoDato->RetornarConsulta("INSERT INTO pedido (codigo, id_mesa, pedido, cantidad, importe, fecha, hora_pedido, mozo, nombre_cliente, estadoPedido, sector) 
                                                                    VALUES (:codigo, :id_mesa, :pedido, :cantidad, :importe, :fecha, :hora_pedido, :mozo, :nombre_cliente , 'Pendiente', :sector);");

                $consulta->bindValue(':codigo', $codigo, PDO::PARAM_STR);
                $consulta->bindValue(':id_mesa', $id_mesa, PDO::PARAM_STR);
                $consulta->bindValue(':pedido', $pedido, PDO::PARAM_STR);
                $consulta->bindValue(':cantidad', $cantidad, PDO::PARAM_INT);
                $consulta->bindValue(':importe', $importe, PDO::PARAM_INT);
                $consulta->bindValue(':fecha', $fecha, PDO::PARAM_STR);
                $consulta->bindValue(':hora_pedido', $hora_pedido, PDO::PARAM_STR);
                $consulta->bindValue(':mozo', $mozo, PDO::PARAM_STR);
                $consulta->bindValue(':nombre_cliente', $nombre_cliente, PDO::PARAM_STR);
                $consulta->bindValue(':sector', $sector, PDO::PARAM_STR);
                $consulta->execute();

                $respuesta = array("Estado" => "OK", "Mensaje" => "Pedido registrado correctamente.", "Mesa" => $id_mesa, "Codigo" => $codigo);

            } catch (Exception $e) {
                $mensaje = $e->getMessage();
                $respuesta = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
            }
            finally {
                return $respuesta;

            }
        }




        public static function Cancelar($codigo)
        {
            try {
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

                $consulta = $objetoAccesoDato->RetornarConsulta("UPDATE pedido SET estadoPedido = 'Cancelado' WHERE codigo = :codigo");

                $consulta->bindValue(':codigo', $codigo, PDO::PARAM_STR);

                $consulta->execute();

                $respuesta = array("Estado" => "OK", "Cantidad" => $consulta->rowCount());
            
            } catch (Exception $e) {
                $mensaje = $e->getMessage();
                $respuesta = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
            }
            finally {
                return $respuesta;
            }
        }



        public static function TomarPedido($codigo, $encargadoId, $encargadoPerfil, $minutosEstimadosDePreparacion, $id_mesa)
        {
            try {
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

                $consulta = $objetoAccesoDato->RetornarConsulta("SELECT sector FROM pedido WHERE codigo = :codigo");

                $consulta->bindValue(':codigo', $codigo, PDO::PARAM_STR);

                $consulta->execute();
                $sector = $consulta->fetch();

                
                if (($encargadoPerfil == "bartender" && $sector[0] != "entrada" ) || 
                    ($encargadoPerfil == "cervecero" && $sector[0] != "barra" ) ||
                    ($encargadoPerfil == "cocinero" && $sector [0]!= "cocina" ))
                {
                    return array("Estado" => "OK", "Mensaje" => "El sector $sector no puede ser tomado por un $encargadoPerfil");
                }
                

                $time = new DateTime('now', new DateTimeZone('America/Argentina/Buenos_Aires'));
                $time->add(new DateInterval('PT' . $minutosEstimadosDePreparacion . 'M'));

                $hora_entrega_estimada = $time->format('H:i');

                $consulta = $objetoAccesoDato->RetornarConsulta("UPDATE pedido SET estadoPedido = 'En preparacion', 
                                                                hora_entrega_estimada = :hora_entrega_estimada, encargado_pedido = :encargado WHERE codigo = :codigo AND id_mesa = :id_mesa");

                $consulta->bindValue(':codigo', $codigo, PDO::PARAM_STR);
                $consulta->bindValue(':encargado', $encargadoId, PDO::PARAM_INT);
                $consulta->bindValue(':hora_entrega_estimada', $hora_entrega_estimada, PDO::PARAM_STR);
                $consulta->bindValue(':id_mesa', $id_mesa, PDO::PARAM_STR);

                $consulta->execute();

                $respuesta = array("Estado" => "OK", "Cantidad" => $consulta->rowCount(), $tipo);

            } catch (Exception $e) {
                $mensaje = $e->getMessage();
                $respuesta = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
            }
            finally {
                return $respuesta;
            }
        }



        public static function InformarListoParaServir($codigo)
        {
            try {
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

                $consulta = $objetoAccesoDato->RetornarConsulta("UPDATE pedido SET estadoPedido = 'Listo para servir' 
                                                                WHERE codigo = :codigo AND estadoPedido = 'En preparacion'");

                $consulta->bindValue(':codigo', $codigo, PDO::PARAM_STR);

                $consulta->execute();

                $respuesta = array("Estado" => "OK", "Cantidad" => $consulta->rowCount());

            } catch (Exception $e) {
                $mensaje = $e->getMessage();
                $respuesta = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
            }
            finally {
                return $respuesta;
            }
        }



        public static function InformarPedidoServido($codigo)
        {
            try {
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

                $consulta = $objetoAccesoDato->RetornarConsulta("SELECT id_mesa FROM pedido 
                                                                WHERE codigo = :codigo");

                $consulta->bindValue(':codigo', $codigo, PDO::PARAM_STR);
                $consulta->execute();
                $mesa = $consulta->fetch();


                $time = new DateTime('now', new DateTimeZone('America/Argentina/Buenos_Aires'));
                $hora_entrega = $time->format('H:i');

                $consulta = $objetoAccesoDato->RetornarConsulta("UPDATE pedido SET estadoPedido = 'Servido', hora_entrega = :hora_entrega
                                                                WHERE codigo = :codigo AND estadoPedido = 'Listo para servir'");

                $consulta->bindValue(':codigo', $codigo, PDO::PARAM_STR);
                $consulta->bindValue(':hora_entrega', $hora_entrega, PDO::PARAM_STR);

                $consulta->execute();

                $respuesta = array("Estado" => "OK", "Mesa" => $mesa["id_mesa"], "Cantidad" => $consulta->rowCount());

            } catch (Exception $e) {
                $mensaje = $e->getMessage();
                $respuesta = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
            }
            finally {
                return $respuesta;
            }
        }



        public static function CobrarPedidos($codigoMesa)
        {
            try {
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

                $consulta = $objetoAccesoDato->RetornarConsulta("SELECT estadoPedido, importe, id_mesa FROM pedido WHERE id_mesa = :codigo AND estadoPedido NOT IN ('Cancelado')");

                $consulta->bindValue(':codigo', $codigoMesa, PDO::PARAM_STR);
                $consulta->execute();

                $pedidos = $consulta->fetchAll(PDO::FETCH_CLASS, "Pedido");

                if(count($pedidos) > 0)
                {
                    $importeMesa = 0;
                    foreach ($pedidos as $pedido)
                    {
                        if($pedido->estadoPedido != 'Servido')
                            return $respuesta["Mensaje"]="Todos los pedidos de la mesa deben estar servidos para cobrar";
                        else
                            $importeMesa += $pedido->importe;
                    }

                    Mesa::EstadoPagando($codigoMesa);
                    Facturacion::Generar($importeMesa, $codigoMesa);

                    $respuesta = array("Estado" => "OK", "Mensaje" => "La mesa $codigoMesa ha sido cobrada", "pedidos" => $pedidos);
                }
                else
                    $respuesta = array("Estado" => "ERROR", "Mensaje" => "La mesa no existe o no tiene pedidos a cobrar");

            } catch (Exception $e) {
                $mensaje = $e->getMessage();
                $respuesta = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
            }
            finally {
                return $respuesta;
            }
        }




        public static function TiempoRestante($codigo)
        {
            try {
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

                $consulta = $objetoAccesoDato->RetornarConsulta("SELECT hora_entrega_estimada, estadoPedido as estado FROM pedido 
                                                                WHERE codigo = :codigo");

                $consulta->bindValue(':codigo', $codigo, PDO::PARAM_STR);
                $consulta->execute();
                $pedido = $consulta->fetch();

                if ($pedido["estado"] == 'En preparacion') {

                    $time = new DateTime('now', new DateTimeZone('America/Argentina/Buenos_Aires'));
                    $hora_entrega = new DateTime($pedido["hora_entrega_estimada"], new DateTimeZone('America/Argentina/Buenos_Aires'));

                    if ($time > $hora_entrega) {
                        $resultado = "Pedido retrasado.";
                    } else {
                        $intervalo = $time->diff($hora_entrega);
                        $estimado = $intervalo->format('%H:%I:%S');
                        $resultado = "Tiempo estimado: " . $estimado;
                    }
                } else {
                    $resultado = "El pedido se encuentra " . $pedido["estado"];
                }
            } catch (Exception $e) {
                $mensaje = $e->getMessage();
                $resultado = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
            }
            finally {
                return $resultado;
            }
        }



        /*
        public static function ListarPendientes()
        {
            try {
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

                $consulta = $objetoAccesoDato->RetornarConsulta("SELECT codigo, hora_pedido, id_mesa, pedido, cantidad, importe, nombre_cliente, estadoPedido
                                                                 FROM pedido WHERE estadoPedido = 'Pendiente'");
                $consulta->execute();

                $resultado = $consulta->fetchAll(PDO::FETCH_CLASS, "Pedido");
            } catch (Exception $e) {
                $mensaje = $e->getMessage();
                $resultado = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
            }
            finally {
                return $resultado;
            }
        }*/



        public static function ListarTodos()
        {
            try {
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

                $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * FROM pedido");

                $consulta->execute();

                $resultado = $consulta->fetchAll(PDO::FETCH_CLASS, "Pedido");


            } catch (Exception $e) {
                $mensaje = $e->getMessage();
                $resultado = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
            }
            finally {
                return $resultado;
            }
        }



        public static function MasVendido()
        {
            try {
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

                $consulta = $objetoAccesoDato->RetornarConsulta("SELECT pedido, SUM(cantidad) as Cantidad_Ventas FROM pedido GROUP BY pedido ORDER BY Cantidad_Ventas DESC LIMIT 3");

                $consulta->execute();

                $respuesta = $consulta->fetchAll(PDO::FETCH_CLASS, "Pedido");
            } catch (Exception $e) {
                $mensaje = $e->getMessage();
                $respuesta = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
            }
            finally {
                return $respuesta;
            }
        }



        public static function MenosVendido()
        {
            try {
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

                $consulta = $objetoAccesoDato->RetornarConsulta("SELECT pedido, SUM(cantidad) as Cantidad_Ventas FROM pedido GROUP BY pedido ORDER BY Cantidad_Ventas ASC LIMIT 3");


                $consulta->execute();

                $respuesta = $consulta->fetchAll(PDO::FETCH_CLASS, "Pedido");

            } catch (Exception $e) {
                $mensaje = $e->getMessage();
                $respuesta = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
            }
            finally {
                return $respuesta;
            }
        }



        public static function ListarFueraDelTiempoEstipulado()
        {
            try {
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

                $consulta = $objetoAccesoDato->RetornarConsulta("SELECT hora_pedido, hora_entrega_estimada, hora_entrega, id_mesa, nombre_cliente, encargado_pedido
                                                                 FROM pedido WHERE hora_entrega_estimada < hora_entrega");

                $consulta->execute();

                $respuesta = $consulta->fetchAll(PDO::FETCH_CLASS, "Pedido");

            } catch (Exception $e) {
                $mensaje = $e->getMessage();
                $respuesta = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
            }
            finally {
                return $respuesta;
            }
        }



        public static function ListarCancelados()
        {
            try {
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

                $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * FROM pedido WHERE estadoPedido = 'Cancelado'");
                $consulta->execute();

                $resultado = $consulta->fetchAll(PDO::FETCH_CLASS, "Pedido");

            } catch (Exception $e) {
                $mensaje = $e->getMessage();
                $resultado = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
            }
            finally {
                return $resultado;
            }
        }

    }


?>