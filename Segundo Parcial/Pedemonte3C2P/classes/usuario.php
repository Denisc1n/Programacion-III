<?php

    class Usuario
    {
        public $perfil;
        public $nombre;
        public $clave;
        public $id;

        public function __construct($inputNombre,$inputClave,$inputPerfil)
        {
            $this->nombre   = $inputNombre;
            $this->clave    = $inputClave;
            $this->perfil   = $inputPerfil;
        }
    }


?>