<?php
    include_once "persona.php";

    class Alumno extends Persona
    {
        public $legajo;
        
        public function __construct($inputNombre,$inputApellido,$inputDNI,$inputLegajo)
        {
            parent::__construct($inputNombre,$inputApellido,$inputDNI);
            $this->legajo = $inputLegajo;
        }

        public function ToJSON(){
            return json_encode($this);
        }
    }

?>