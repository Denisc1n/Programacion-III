<?php
    include_once("AccesoDatos.php");

    class Mesa
    {
        public static function Registrar($codigo)
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $respuesta = "";
            try {
                $consulta = $objetoAccesoDato->RetornarConsulta("INSERT INTO mesa (codigo, estado) 
                                                                VALUES (:codigo, 'Cerrada');");

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


        public static function Listar()
        {
            try {
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

                $consulta = $objetoAccesoDato->RetornarConsulta("SELECT codigo as codigo, estado, foto FROM mesa");

                $consulta->execute();

                $resultado = $consulta->fetchAll(PDO::FETCH_CLASS, "Mesa");

            } catch (Exception $e) {
                $mensaje = $e->getMessage();
                $resultado = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
            }
            finally {
                return $resultado;
            }
        }


        public static function Baja($codigo)
        {
            try {
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

                $consulta = $objetoAccesoDato->RetornarConsulta("DELETE FROM mesa WHERE codigo = :codigo");

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


        public static function ActualizarFoto($foto, $codigo)
        {
            try {
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

                $consulta = $objetoAccesoDato->RetornarConsulta("UPDATE mesa SET foto = :foto WHERE codigo = :codigo");

                $consulta->bindValue(':codigo', $codigo, PDO::PARAM_STR);
                $consulta->bindValue(':foto', $foto, PDO::PARAM_STR);

                $consulta->execute();

                $resultado = array("Estado" => "OK", "Mensaje" => "Foto actualizada correctamente.");
            } catch (Exception $e) {
                $mensaje = $e->getMessage();
                $resultado = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
            }
            finally {
                return $resultado;
            }
        }



        public static function ObtenerPorCodigo($codigoMesa)
        {
            try {
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

                $consulta = $objetoAccesoDato->RetornarConsulta("SELECT codigo as codigo, estado, foto FROM mesa
                                                                    WHERE codigo = :codigo");

                $consulta->bindValue(':codigo', $codigoMesa, PDO::PARAM_STR);
                $consulta->execute();

                $resultado = $consulta->fetchAll(PDO::FETCH_CLASS, "Mesa");
            } catch (Exception $e) {
                $mensaje = $e->getMessage();
                $resultado = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
            }
            finally {
                return $resultado;
            }
        }





        public static function EstadoEsperandoPedido($codigoMesa)
        {
            try {
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

                $consulta = $objetoAccesoDato->RetornarConsulta("UPDATE mesa SET estado = 'Con cliente esperando pedido' WHERE codigo = :codigo");

                $consulta->bindValue(':codigo', $codigoMesa, PDO::PARAM_STR);

                $consulta->execute();

                $resultado = array("Estado" => "OK", "Mensaje" => "Cliente esperando pedido.");
            } catch (Exception $e) {
                $mensaje = $e->getMessage();
                $resultado = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
            }
            finally {
                return $resultado;
            }

        }

        public static function EstadoComiendo($codigoMesa)
        {
            try {
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

                $consulta = $objetoAccesoDato->RetornarConsulta("UPDATE mesa SET estado = 'Con clientes comiendo' WHERE codigo = :codigo");

                $consulta->bindValue(':codigo', $codigoMesa, PDO::PARAM_STR);

                $consulta->execute();

                $resultado = array("Estado" => "OK", "Mensaje" => "Cliente comiendo.");
            } catch (Exception $e) {
                $mensaje = $e->getMessage();
                $resultado = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
            }
            finally {
                return $resultado;
            }

        }

        public static function EstadoPagando($codigoMesa)
        {
            try {
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

                $consulta = $objetoAccesoDato->RetornarConsulta("UPDATE mesa SET estado = 'Con cliente pagando' WHERE codigo = :codigo");

                $consulta->bindValue(':codigo', $codigoMesa, PDO::PARAM_STR);

                $consulta->execute();

                $resultado = array("Estado" => "OK", "Mensaje" => "Cliente pagando.");
            } catch (Exception $e) {
                $mensaje = $e->getMessage();
                $resultado = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
            }
            finally {
                return $resultado;
            }

        }

        public static function EstadoCerrada($codigoMesa)
        {
            try {
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

                $consulta = $objetoAccesoDato->RetornarConsulta("UPDATE mesa SET estado = 'Cerrada' WHERE codigo = :codigo");

                $consulta->bindValue(':codigo', $codigoMesa, PDO::PARAM_STR);

                $consulta->execute();

                $resultado = array("Estado" => "OK", "Mensaje" => "Mesa cerrada.");
            } catch (Exception $e) {
                $mensaje = $e->getMessage();
                $resultado = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
            }
            finally {
                return $resultado;
            }

        }
        

        public static function MasUsada()
        {
            try {
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

                $consulta = $objetoAccesoDato->RetornarConsulta("SELECT id_mesa, count(id_mesa) as Cantidad_Usos FROM pedido GROUP BY id_mesa ORDER BY Cantidad_Usos DESC LIMIT 1");

                $consulta->execute();

                $resultado = $consulta->fetchAll(PDO::FETCH_CLASS, "Mesa");

            } catch (Exception $e) {
                $mensaje = $e->getMessage();
                $resultado = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
            }
            finally {
                return $resultado;
            }
        }


        public static function MenosUsada()
        {
            try {
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

                $consulta = $objetoAccesoDato->RetornarConsulta("SELECT id_mesa, count(id_mesa) as Cantidad_Usos FROM pedido GROUP BY id_mesa ORDER BY Cantidad_Usos ASC LIMIT 1");

                $consulta->execute();

                $resultado = $consulta->fetchAll(PDO::FETCH_CLASS, "Mesa");
            } catch (Exception $e) {
                $mensaje = $e->getMessage();
                $resultado = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
            }
            finally {
                return $resultado;
            }
        }



        public static function MasFacturacion()
        {
            try {
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

                $consulta = $objetoAccesoDato->RetornarConsulta("SELECT id_mesa, SUM(importe) as Total_Facturado FROM pedido GROUP BY id_mesa ORDER BY Total_Facturado DESC LIMIT 1");

                $consulta->execute();

                $resultado = $consulta->fetchAll(PDO::FETCH_CLASS, "Mesa");
            } catch (Exception $e) {
                $mensaje = $e->getMessage();
                $resultado = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
            }
            finally {
                return $resultado;
            }
        }



        public static function MenosFacturacion()
        {
            try {
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

                $consulta = $objetoAccesoDato->RetornarConsulta("SELECT id_mesa, SUM(importe) as Total_Facturado FROM pedido GROUP BY id_mesa ORDER BY Total_Facturado ASC LIMIT 1");

                $consulta->execute();

                $resultado = $consulta->fetchAll(PDO::FETCH_CLASS, "Mesa");
            } catch (Exception $e) {
                $mensaje = $e->getMessage();
                $resultado = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
            }
            finally {
                return $resultado;
            }
        }



        public static function FacturaConMayorImporte()
        {
            try {
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

                $consulta = $objetoAccesoDato->RetornarConsulta("SELECT f.codigoMesa, f.importe as importe FROM factura f WHERE f.importe = 
                                                                (SELECT MAX(f2.importe) as importe FROM factura f2 ) GROUP BY (f.codigoMesa);");

                $consulta->execute();

                $resultado = $consulta->fetchAll(PDO::FETCH_CLASS, "Mesa");

            } catch (Exception $e) {
                $mensaje = $e->getMessage();
                $resultado = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
            }
            finally {
                return $resultado;
            }
        }



        public static function FacturaConMenorImporte()
        {
            try {
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

                $consulta = $objetoAccesoDato->RetornarConsulta("SELECT f.codigoMesa, f.importe as importe FROM factura f WHERE f.importe = 
                                                                (SELECT MIN(f2.importe) as importe FROM factura f2 ) GROUP BY (f.codigoMesa);");

                $consulta->execute();

                $resultado = $consulta->fetchAll(PDO::FETCH_CLASS, "Mesa");
            } catch (Exception $e) {
                $mensaje = $e->getMessage();
                $resultado = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
            }
            finally {
                return $resultado;
            }
        }



        public static function FacturacionEntreFechas($codigoMesa, $fecha1, $fecha2)
        {
            try {
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

                $consulta = $objetoAccesoDato->RetornarConsulta("SELECT f.codigoMesa, f.fecha, f.importe FROM factura f 
                                                            WHERE f.codigoMesa = :codigoMesa AND f.fecha BETWEEN :fecha1 AND :fecha2");

                

                $consulta->bindValue(':codigoMesa', $codigoMesa, PDO::PARAM_STR);
                $consulta->bindValue(':fecha1', $fecha1, PDO::PARAM_STR);
                $consulta->bindValue(':fecha2', $fecha2, PDO::PARAM_STR);
                $consulta->execute();

                $resultado = $consulta->fetchAll();

            } catch (Exception $e) {
                $mensaje = $e->getMessage();
                $resultado = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
            }
            finally {
                return $resultado;
            }
        }



        public static function MejorPuntuacion()
        {
            try {
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

                $consulta = $objetoAccesoDato->RetornarConsulta("SELECT e.codigoMesa, e.comentario, AVG(e.puntuacion_mesa) as puntuacion_promedio FROM encuesta e 
                                                                GROUP BY(e.codigoMesa) HAVING AVG(e.puntuacion_mesa) = 
                                                                (SELECT MAX(sel.puntuacion_promedio) FROM
                                                                (SELECT AVG(e2.puntuacion_mesa) as puntuacion_promedio FROM encuesta e2 GROUP BY(e2.codigoMesa)) sel);");

                $consulta->execute();

                $resultado = $consulta->fetchAll();

            } catch (Exception $e) {
                $mensaje = $e->getMessage();
                $resultado = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
            }
            finally {
                return $resultado;
            }
        }



        public static function PeorPuntuacion()
        {
            try {
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

                $consulta = $objetoAccesoDato->RetornarConsulta("SELECT e.codigoMesa, e.comentario, AVG(e.puntuacion_mesa) as puntuacion_promedio FROM encuesta e 
                                                                GROUP BY(e.codigoMesa) HAVING AVG(e.puntuacion_mesa) = 
                                                                (SELECT MIN(sel.puntuacion_promedio) FROM
                                                                (SELECT AVG(e2.puntuacion_mesa) as puntuacion_promedio FROM encuesta e2 GROUP BY(e2.codigoMesa)) sel);");

                $consulta->execute();

                $resultado = $consulta->fetchAll();
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