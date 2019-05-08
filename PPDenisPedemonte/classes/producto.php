<?php
    class Producto{

        public $id;
        public $nombre;
        public $precio;
        public $imagen;
        public $usuario;

        public function __construct($inputId, $inputNombre, $inputPrecio, $inputUsuario )
        {
            $this->nombre = $inputNombre;
            $this->id = $inputId;
            $this->precio = $inputPrecio;
            $this->imagen = "";
            $this->usuario = $inputUsuario;
        }


        public function ToJSON(){
            return json_encode($this);
        }

        public function ToCSV()
        {
            return $this->nombre.";".$this->id.";".$this->precio.";".$this->imagen.";".$this->usuario.';'.PHP_EOL;
        }

        public function ToString()
        {
            return $this->nombre.";".$this->id.";".$this->precio.";".$this->imagen.";".$this->usuario.';'.PHP_EOL;
        }
        

    }
?>