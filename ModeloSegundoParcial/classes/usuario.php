<?php

    class Usuario
    {
        public $perfil;
        public $nombre;
        public $clave;
        public $sexo;
        public $id;

        public function __construct($inputNombre,$inputClave,$inputSexo,$inputPerfil)
        {
            $this->nombre   = $inputNombre;
            $this->clave    = $inputClave;
            $this->sexo     = $inputSexo;
            $this->perfil   = $inputPerfil;
        }
    }


?>