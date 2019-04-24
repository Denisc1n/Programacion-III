<?php
    include_once('classes/proveedor.php');

    function doPost(){
        $operation = strtolower($_POST['operacion']);

        switch($operation){
            case 'cargarProveedores':
                cargarProveedores();
                break;
            case 'hacerPedido':
                hacerPedido();
                break;

            default:
                echo "Invalid Operation";
                break;
        }
    }

    function cargarProveedores()
    {
        $proveedor = new Proveedor( $_POST['inputNombre'],$_POST['inputEmail'],$_POST['inputId'] );
        $origen = $_FILES["imageInput"]["tmp_name"];
        $uploadedFileOriginalName = $_FILES["imageInput"]["name"];
        $ext = pathinfo($uploadedFileOriginalName, PATHINFO_EXTENSION);
        $fileDestination = "data/".$proveedor->nombre."_".$proveedor->id.".".$ext;
        $proveedor->foto = $fileDestination;
        move_uploaded_file($origen, $fileDestination);

        $referenceFile = fopen("data/proveedores.txt", 'a');
        $stringToFile = "";
        $stringToFile = $stringToFile . $proveedor->ToCSV();
        fwrite($referenceFile, $stringToFile);
    }

    function hacerPedido()
    {
        $pedido = new Pedido($_POST['productoInput'],$_POST['cantidadInput'],$_POST['idProveedorInput']);
        
        $existeProveedor = validarProveedor($pedido->idProveedor);
        if( $existeProveedor != null ){
            $referenceFile = fopen('data/pedidos.txt',a);

            $stringToFile = $pedido->ToCSV();

            fwrite($referenceFile,$stringToFile);

            echo "Pedido Guardado con exito";

            fclose($referenceFile);
        }
        else{
            echo "No existe proveedor. Abortando.";
        }

    }

    public function validarProveedor($idProveedor)
    {
        $referenceFile = fopen('data/proveedores.txt',r);

        while(!feof($referenceFile))
        {
            $stringProveedor = fgets($referenceFile);
            $arrayDataProveedor = explode(";",$stringProveedor);
            if($arrayDataProveedor[0] == "")
                continue;

            if(strtolower($arrayDataProveedor[1]) == strtolower($nombreConsulta)){
                $provEncontrado = new Proveedor($arrayDataProveedor[1],$arrayDataProveedor[2],$arrayDataProveedor[0]);
                return $provEncontrado;
            }

        }
        fclose($referenceFile);
        return null;
    }

?>