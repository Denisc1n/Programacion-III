<?php

    include_once 'classes\accesodatos.php';

    class UsuarioDAO
    {
        public $perfil;
        public $nombre;
        public $clave;
        public $id;

        public static function InsertarUsuario( $usuario )
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

            $consulta =$objetoAccesoDato->RetornarConsulta("INSERT INTO usuario (nombre, clave, tipo)"
                                                        . "VALUES(:nombre, :clave, :perfil)");
            $consulta->bindValue(':nombre', $usuario->nombre, PDO::PARAM_STR);
            $consulta->bindValue(':clave', $usuario->clave, PDO::PARAM_STR);
            $consulta->bindValue(':perfil', $usuario->perfil, PDO::PARAM_STR);

            $salida = $consulta->execute();  

            $ultimoID = $objetoAccesoDato->ultimoID();

            return $ultimoID;
        }

        public static function ValidarCredenciales($legajo, $clave)
        {    
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

            $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * FROM usuario WHERE id <=>:id and clave<=>:clave");        
            $consulta->bindValue(':id', $legajo, PDO::PARAM_STR);
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

        public static function TraerUsuario($id)
        {    
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

            $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * FROM usuario WHERE id <=>:id");        
            $consulta->bindValue(':id', $id, PDO::PARAM_INT);
            $consulta->execute();

            return $consulta->fetchAll(PDO::FETCH_CLASS,"usuarioDao"); 
        }

        public static function TraerUsuarioPorNombre($nombre)
        {    
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

            $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * FROM usuario WHERE nombre <=>:nombre");        
            $consulta->bindValue(':nombre', $nombre, PDO::PARAM_INT);
            $consulta->execute();

            return $consulta->fetchAll(PDO::FETCH_CLASS,"usuarioDao"); 
        }

        public static function ModificarUsuario( $array, $perfil )
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        

            if($perfil=="alumno"){
                $email = $array[0];
                $consulta =$objetoAccesoDato->RetornarConsulta("UPDATE `usuario` SET email=:mail, foto =:foto WHERE id=:legajo");
                $boolOne = $consulta->bindValue(':mail', $email, PDO::PARAM_STR);
                $consulta->bindValue(':foto', $array[1], PDO::PARAM_STR);
                $boolTwo = $consulta->bindValue(':legajo', (int)$array[2], PDO::PARAM_INT);

                $salida = $consulta->execute();  
               
            }
            if($perfil=="profesor"){
                $email = $array[0];
                $consulta =$objetoAccesoDato->RetornarConsulta("UPDATE `usuario` SET email=:mail, materiasdictadas =:materias WHERE id=:legajo");
                $boolOne = $consulta->bindValue(':mail', $email, PDO::PARAM_STR);
                $consulta->bindValue(':materias', $array[1], PDO::PARAM_STR);
                $boolTwo = $consulta->bindValue(':legajo', (int)$array[2], PDO::PARAM_INT);

                $salida = $consulta->execute();  
               
            }

            if($perfil=="admin"){
        
                $consulta = "UPDATE 'usuario' set ";
                if($array[0] != ""){
                    $consulta."email:=mail,";
                    $email = true;
                    }
                
                if($array[1]!=null){
                    $consulta."materiasdictadas:=mat,";
                    $materias = true;
                }
                if($array[2]!= null){
                    
                    $consulta."foto:=fot";
                    $foto = true;
                }
                
                
                if( substr($consulta, -1) == ","){
                    substr($consulta,0,-1);
                }

                $consulta. " WHERE id:=id";

                $consultaQuery =$objetoAccesoDato->RetornarConsulta("UPDATE `usuario` SET email=:mail, materiasdictadas =:materias, foto=:foto WHERE id=:legajo");
                var_dump($array);
                if($foto)
                    $consultaQuery->bindValue(':foto', $array[2], PDO::PARAM_STR);

                if($materias)
                    $consultaQuery->bindValue(':materias', $array[1], PDO::PARAM_STR);

                if($email)
                    $consultaQuery->bindValue(':mail', $array[0], PDO::PARAM_STR);
                
                
                $consultaQuery->bindValue(':legajo',(int)$array[3],PDO::PARAM_INT);  

                $salida = $consultaQuery->execute();  
               
            }
    
        }

        public static function Inscribir($materia,$legajo)
        {    
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
    
                $consulta = $objetoAccesoDato->RetornarConsulta("SELECT cupos FROM 'materia' WHERE id=:id");        
                $consulta->bindValue(':id', (int)$materia, PDO::PARAM_INT);
            
                $result = $consulta->execute();
               if($result){
                  // $cupos = (int)$materiaRetrieved;
                  // if($cupos > 0){
                       $inscQuery = $objetoAccesoDato->RetornarConsulta("UPDATE materia set cupos = 49 WHERE id <=>:id");
                       $consulta->bindValue(':id', (int)$materia, PDO::PARAM_INT);
                       //$consulta->bindValue(':cupos', $materiaRetrieved-1, PDO::PARAM_INT);
                    //    inscQuery->exec
                       return true;
                   //}
                   //lse{
                   //   return false;
                }
               
            }
 
        }

    


?>
