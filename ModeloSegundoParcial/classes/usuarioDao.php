<?php

    include_once 'classes\accesodatos.php';

    class UsuarioDAO
    {
        public $perfil;
        public $nombre;
        public $clave;
        public $sexo;
        public $id;

        public static function InsertarUsuario( $usuario )
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

            $consulta =$objetoAccesoDato->RetornarConsulta("INSERT INTO usuario (nombre, clave, sexo,perfil)"
                                                        . "VALUES(:nombre, :clave, :sexo, :perfil)");
            
            $consulta->bindValue(':nombre', $usuario->nombre, PDO::PARAM_STR);
            $consulta->bindValue(':clave', $usuario->clave, PDO::PARAM_STR);
            $consulta->bindValue(':sexo', $usuario->sexo, PDO::PARAM_STR);
            $consulta->bindValue(':perfil', $usuario->perfil, PDO::PARAM_STR);

            $salida = $consulta->execute();   
        }

        public static function ValidarCredenciales($usuario, $clave)
        {    
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

            $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * FROM usuario WHERE nombre <=>:nombre and clave<=>:clave");        
            $consulta->bindValue(':nombre', $usuario, PDO::PARAM_STR);
            $consulta->bindValue(':clave', $clave, PDO::PARAM_STR);
            $salida = $consulta->execute();
            $usuarioResultado = $consulta->fetchObject('usuarioDao');
            return $usuarioResultado; 
        }

        public static function TraerTodosLosUsuarios()
        {    
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

            $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * FROM usuario");        

            $consulta->execute();

            return $consulta->fetchAll(PDO::FETCH_CLASS,"usuarioDao"); 
        }

    }


?>
