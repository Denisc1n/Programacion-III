<?php

include_once 'classes\accesodatos.php';

class HistorialDao{
    public $id;
    public $usuario;
    public $ruta;
    public $hora;

    public static function InsertarHistorial( $usuarioInput, $rutaInput, $horaInput )
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

            $consulta =$objetoAccesoDato->RetornarConsulta("INSERT INTO historial (usuario, ruta, hora)"
                                                        . "VALUES(:usuario, :ruta, :hora)");
            
            $horaInput=date("H:i:s");
            $consulta->bindValue(':usuario', $usuarioInput, PDO::PARAM_STR);
            $consulta->bindValue(':ruta', $rutaInput, PDO::PARAM_STR);
            $consulta->bindValue(':hora', $horaInput, PDO::PARAM_STR);

            $salida = $consulta->execute();   
        }

}

?>