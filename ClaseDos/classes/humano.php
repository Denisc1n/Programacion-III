<?php

class Humano
{
    public $nombre;
    public $apellido;

    public function __construct($inputNombre, $inputApellido){
        $this->nombre = $inputNombre;
        $this->apellido = $inputApellido;
    }
    public function ToJSON()
    {
        return json_encode($this);
    }
}
?>