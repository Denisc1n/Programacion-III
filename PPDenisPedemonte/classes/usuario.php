<?php
    class Usuario{

        public $nombre;
        public $clave;

        public function __construct($inputNombre,$inputClave )
        {
            $this->nombre = $inputNombre;
            $this->clave = $inputClave;
        }

        public function ToJSON(){
            return json_encode($this);
        }

        public function ToString(){
            return '<br>'.'Nombre:'.$this->nombre.'<br>'.'Clave:'.$this->clave.'<br>';
        }

        public function ToCSV()
        {
            return $this->nombre.";".$this->clave.";".PHP_EOL;
        }
    }

?>