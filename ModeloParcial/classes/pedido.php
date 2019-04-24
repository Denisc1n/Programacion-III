<?php

    class Pedido
    {
        public $producto;
        public $cantidad;
        public $idProveedor;

        public function __construct($prod, $cant, $idProv)
        {
            $this->producto = $prod;
            $this->cantidad = $cant;
            $this->idProveedor = $idProv;
        }

        public function ToCSV()
        {
            return $this->producto.";".$this->cantidad.";".$this->idProveedor";".PHP_EOL;
        }

        public function ToString(){
            return 'Producto:'.$this->producto.'<br>'.'Cantidad:'.$this->cantidad.'<br>'
                .'Proveedor:'.$this->idProveedor'<br>';
        }
    }

?>