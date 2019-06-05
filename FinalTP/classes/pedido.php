<?php

    class item
    {
        public $nombre;
        public $costo;
    }

    class comida extends item
    {
        public $barra;
    }

    class pedido
    {
        public $idPedido;
        public $comida;
        public $bebida;
        public $postre;
        public $nombreCliente;
        public $estado;
        public $costoTotal;
        public $tiempoDePreparacion;
        public $foto;
    }

    class mesa
    {
        public $idMesa;
        public $estado;
        public $cuenta;
        public $mozoAsignado;
    }
?>