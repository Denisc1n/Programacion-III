<?php

    class Proveedor
    {
        public $id;
        public $nombre;
        public $email;
        public $foto;

        public function __construct($inputNombre,$inputEmail,$inputId )
        {
            $this->id = $inputId;
            $this->nombre = $inputNombre;
            $this->email = $inputEmail;
            $this->foto = "";
        }

        public function ToCSV()
        {
            return $this->id.";".$this->nombre.";".$this->email.";".$this->foto.";".PHP_EOL;
        }

        public function ToString(){
            return "Datos:".'<br>'.'id:'.$this->id.'<br>'.'nombre:'.$this->nombre.'<br>'
                .'email:'.$this->email.'<br>'.'Ruta Foto:'.$this->foto.'<br>';
        }
    }


?>