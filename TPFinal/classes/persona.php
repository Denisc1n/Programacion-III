<?php
include_once "humano.php";

class Persona extends Humano
{
    public $dni;
    public $photoId;
    public function __construct($inputNombre, $inputApellido, $inputDni)
    {
        parent::__construct($inputNombre, $inputApellido);
        $this->dni = $inputDni;
        $this->photoId = "";
    }
    public function ToJSON(){
        return json_encode($this);
    }
}
?>
