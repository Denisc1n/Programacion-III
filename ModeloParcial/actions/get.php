<?php
    include_once 'post.php';
    include_once 'classes/proveedor.php';
    function doGet()
    {
        $operation = strtolower($_GET['operacion']);

        switch($operation){
            case 'consultarProveedor':
                consultarProveedor();
                break;
            case 'proveedores':
                proveedores();
                break;

            case 'listarPedidos':
                listarPedidos();

            case 'listarPedidosProveedor':
                listarPedidos();

            default:
                echo "Invalid Operation";
                break;
        }
    }

    function consultarProveedor(){
        $nombreConsulta = $_GET['nombre'];
        if(is_null($nombreConsulta) ){
            echo "Nombre invalido";
            return;
        }

        $referenceFile = fopen('data/proveedores.txt', 'r');
        $arrayDeProveedores = array();
        while(!feof($referenceFile))
        {
            $stringProveedor = fgets($referenceFile);
            $arrayDataProveedor = explode(";",$stringProveedor);
     

            //Constructor $inputNombre,$inputEmail,$inputId,$inputFoto
            //ToCSV $this->id.";".$this->nombre.";".$this->email.";".$this->foto.";"
            

            if($arrayDataProveedor[0] == "")
                continue;

            if(strtolower($arrayDataProveedor[1]) == strtolower($nombreConsulta)){
                $provEncontrado = new Proveedor($arrayDataProveedor[1],$arrayDataProveedor[2],$arrayDataProveedor[0]);
                $provEncontrado->foto = $arrayDataProveedor[3];
                array_push($arrayDeProveedores, $provEncontrado);
            }

        }
        fclose($referenceFile);
        if(count($arrayDeProveedores) > 0){
            echo "Proveedor(es) encontrado(s)";

            foreach ($arrayDeProveedores as $ap) {
                echo $ap->ToString();
            }
        }

        else{
            echo "No existen ocurrencias para este parametro de busqueda:".$nombreConsulta;
        }

    }

    function proveedores(){
        $referenceFile = fopen('data/proveedores.txt', 'r');
        $arrayDeProveedores = array();
        while(!feof($referenceFile))
        {
            $stringProveedor = fgets($referenceFile);
            $arrayDataProveedor = explode(";",$stringProveedor);

            if($arrayDataProveedor[0] == "")
                continue;

            $provEncontrado = new Proveedor($arrayDataProveedor[1],$arrayDataProveedor[2],$arrayDataProveedor[0]);
            $provEncontrado->foto = $arrayDataProveedor[3];
            array_push($arrayDeProveedores, $provEncontrado);
        }

            if(count($arrayDeProveedores) > 0){
                echo "Proveedor(es) encontrado(s)";
    
                foreach ($arrayDeProveedores as $ap) {
                    echo $ap->ToString();
                }
            }
            else{
                echo "no hay proveedores";
            }

        }

        function listarPedidos()
        {

            $referenceFile = fopen("data/pedidos.txt");

            $arrayDePedidos = array();
            while(!feof($referenceFile))
            {
                $stringProveedor = fgets($referenceFile);
                $arrayDataPedido = explode(";",$stringProveedor);

                if($arrayDataProveedor[0] == "")
                    continue;

                //$this->producto.";".$this->cantidad.";".$this->idProveedor";
                $pedEncontrado = new pedidos($arrayDataPedido[0],$arrayDataPedido[1],$arrayDataPedido[2]);
         
                array_push($arrayDePedidos, $pedEncontrado);

                if(count($arrayDePedidos) > 0){
                    echo "Pedido(s) encontrado(s)";
        
                    foreach ($arrayDePedidos as $ap) {
                        $proveedor = validarProveedor($ap->idProveedor);

                        $ap->idProveedor = $ap->idProveedor." ".$proveedor->nombre;

                        echo $ap->ToString();
                    }
                }
                else{
                    echo "No hay Pedidos";
                }
            }

        }

        public listarPedidosProveedor()
        {
            $proveedorConsulta = $_GET['idProveedor'];
            
            if(is_null($proveedorConsulta) || $proveedorConsulta == ""){
                echo "Proveedor invalido";
                return;
            }

            

        }
    
?>