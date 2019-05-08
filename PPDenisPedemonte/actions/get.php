<?php

    function doGet()
    {
        $operation = strtolower($_GET['operacion']);

        switch($operation){
            case 'listarusuarios':
                listarUsuarios();
                break;
            case 'listarproductos':
                listarProductos();
                break;

            default:
                echo "Invalid Operation";
                break;
        }

    }
    
    function listarUsuarios(){
        $nombreConsulta = $_GET['nombre'];
        if(is_null($nombreConsulta) ){
            echo "Nombre invalido";
            return;
        }

        $referenceFile = fopen('data/usuarios.txt', 'r');
        $arrayDeUsuarios = array();
        while(!feof($referenceFile))
        {
            $stringUsuario = fgets($referenceFile);
            $arrayDataUsuario = explode(";",$stringUsuario);
     

            //Constructor $inputNombre,$inputEmail,$inputId,$inputFoto
            //ToCSV $this->id.";".$this->nombre.";".$this->email.";".$this->foto.";"
            

            if($arrayDataUsuario[0] == "")
                continue;

            if(strtolower($arrayDataUsuario[0]) == strtolower($nombreConsulta)){
                $usuarioEncontrado = new Usuario($arrayDataUsuario[0],$arrayDataUsuario[1]);
                array_push($arrayDeUsuarios, $usuarioEncontrado);
            }

        }
        fclose($referenceFile);
        if(count($arrayDeUsuarios) > 0){
            echo "Usuario(s) encontrado(s)";

            foreach ($arrayDeUsuarios as $ap) {
                echo $ap->ToString();
            }
        }

        else{
            echo "No existen ocurrencias para este parametro de busqueda:".$nombreConsulta;
        }
    }

    function listarProductos()
    {
        if(isset($_GET['criterio'])){
            listarProductosPorCriterio();
            return;
        }

        $referenceFile = fopen('data/productos.txt', 'r');
        $arrayDeProductos = array();
        while(!feof($referenceFile))
        {
            $stringProducto = fgets($referenceFile);
            $arrayDataProducto = explode(";",$stringProducto);

            if($arrayDataProducto[0] == "")
                continue;

            $prodEncontrado = new Producto($arrayDataProducto[1],$arrayDataProducto[0],$arrayDataProducto[2],$arrayDataProducto[4]);
            $prodEncontrado->imagen = $arrayDataProducto[3];
            array_push($arrayDeProductos, $prodEncontrado);
        }

    
        fclose($referenceFile);
        if(count($arrayDeProductos) > 0){
            echo "Producto(s) encontrado(s)"."<br>";

            foreach ($arrayDeProductos as $ap) {
                echo $ap->ToString();
            }
        }

        else{
            echo "No existen productos cargados";
        }
    }

    function listarProductosPorCriterio(){
        
        $referenceFile = fopen('data/productos.txt', 'r');
        $arrayDeProductos = array();
        $valorBusqueda = $_GET['valor'];
        while(!feof($referenceFile))
        {
            $stringProducto = fgets($referenceFile);
            $arrayDataProducto = explode(";",$stringProducto);

            if($arrayDataProducto[0] == "")
                continue;
            
            $indice = 0;
            if(strtolower($_GET['criterio']) == 'producto'){
                $indice = 0;
            }
            elseif(strtolower($_GET['criterio']) == 'usuario'){
                $indice = 4;
            } 
            if(strtolower($arrayDataProducto[$indice]) == strtolower($valorBusqueda)){
                $prodEncontrado = new Producto($arrayDataProducto[1],$arrayDataProducto[0],$arrayDataProducto[2],$arrayDataProducto[4]);
                $prodEncontrado->imagen = $arrayDataProducto[3];
                array_push($arrayDeProductos, $prodEncontrado);
            }
        }

    
        fclose($referenceFile);
        if(count($arrayDeProductos) > 0){
            echo "Producto(s) encontrado(s)"."<br>";

            foreach ($arrayDeProductos as $ap) {
                echo $ap->ToString();
            }
        }

        else{
            echo "No existen productos cargados con ese criterio:"." ".$_GET['criterio'].':'.$valorBusqueda;
        }
    }
?>