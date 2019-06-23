<?php

    class Compra
    {
        public $id;
        public $fecha;
        public $precio;
        public $articulo;
        public $usuario;
        public $rutafoto;

        public function __construct($inputArticulo,$inputFecha,$inputPrecio, $Inputusuario)
        {
            $this->articulo   = $inputArticulo;

            $this->fecha      = str_replace('/','-',$inputFecha );

            $this->precio     = $inputPrecio;
            $this->usuario   = $Inputusuario;

        }
    }


?>