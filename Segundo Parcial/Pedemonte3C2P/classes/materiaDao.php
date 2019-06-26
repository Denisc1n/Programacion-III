<?php

    include_once 'classes\accesodatos.php';

    class MateriaDAO
    {
        public $articulo;
        public $fecha;
        public $precio;
        public $id;

        public static function InsertarMateria( $materia )
        {
            try{
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

            $consulta =$objetoAccesoDato->RetornarConsulta("INSERT INTO materia (nombre, cuatrimestre, cupos)"
                                                        . "VALUES(:nombre, :cuatrimestre, :cupos)");
            
            $consulta->bindValue(':nombre', $materia->nombre, PDO::PARAM_STR);
            $consulta->bindValue(':cuatrimestre', $materia->cuatrimestre, PDO::PARAM_STR);
            $consulta->bindValue(':cupos', $materia->cupos, PDO::PARAM_STR);

            $salida = $consulta->execute();  
            return $objetoAccesoDato->ultimoId();
            }
            catch( exception $ex ){
                throw new Exception("Fallo la creacion". $ex);
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
