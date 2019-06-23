<?php

    include_once 'classes\accesodatos.php';

    class CompraDAO
    {
        public $articulo;
        public $fecha;
        public $precio;
        public $id;

        public static function InsertarCompra( $compra )
        {
            try{
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

            $consulta =$objetoAccesoDato->RetornarConsulta("INSERT INTO compra (articulo, fecha, precio,idUsuario)"
                                                        . "VALUES(:articulo, :fecha, :precio,:usuario)");
            
            $consulta->bindValue(':articulo', $compra->articulo, PDO::PARAM_STR);
            $consulta->bindValue(':fecha', $compra->fecha, PDO::PARAM_STR);
            $consulta->bindValue(':precio', $compra->precio, PDO::PARAM_STR);
            $consulta->bindValue(':usuario', (int)$compra->usuario, PDO::PARAM_INT);
            $salida = $consulta->execute();  
            return $objetoAccesoDato->ultimoId();
            }
            catch( exception $ex ){
                return "FAILURE";
            }
        }


        public static function TraerTodasLasCompras()
        {    
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

            $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * FROM compra");        

            $consulta->execute();

            return $consulta->fetchAll(PDO::FETCH_CLASS,"compraDao"); 
        }

        public static function TraerTodasLasComprasDeUnUsuario($id)
        {    
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * FROM compra Where idUsuario <=> :id");        
            $consulta->bindValue(':id', $id, PDO::PARAM_INT);
            $salida = $consulta->execute();
    

            return $consulta->fetchAll(PDO::FETCH_CLASS,"compraDao"); 
        }

    }


?>
