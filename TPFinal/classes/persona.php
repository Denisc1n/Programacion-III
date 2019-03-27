<?php
include_once "humano.php";

class Persona extends Humano
{
    public $dni;

    public function __construct($inputNombre, $inputApellido, $inputDni)
    {
        parent::__construct($inputNombre, $inputApellido);
        $this->dni = $inputDni;
    }
    public function ToJSON(){
        return json_encode($this);
    }
}
?>
