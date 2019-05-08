<?php 
    include_once('classes/usuario.php');
    include_once('classes/producto.php');
    function doPost(){
        $operation = strtolower($_POST['operacion']);

        switch($operation){
            case 'crearusuario':
                crearUsuario();
                echo "Creacion Completa";
                break;
            case 'login':
                login();
                break;

            case 'cargarproducto':
                cargarproducto();
                break;

            case 'modificarproducto':
                modificarProducto();
                break;


            default:
                echo "Invalid Operation";
                break;
        }
    }

    function crearUsuario()
    {
        $usuario = new Usuario( $_POST['inputNombre'],$_POST['inputClave']);
        $referenceFile = fopen("data/usuarios.txt", 'a');
        $stringToFile = "";
        $stringToFile = $stringToFile . $usuario->ToCSV();
        fwrite($referenceFile, $stringToFile);
    }

    function login()
    {
        if(is_null($_POST['inputNombre'])|| is_null($_POST['inputClave'])){
            echo "Datos Insuficientes";
            return;
        }

        $usuario = $_POST['inputNombre'];
        $clave = $_POST['inputClave'];

        if($usuario == "" || $clave == ""){
            echo "Datos Invalidos";
            return;
        }

        $referenceFile = fopen('data/usuarios.txt', 'r');

        while(!feof($referenceFile))
        {
            $stringUsuario = fgets($referenceFile);
            $arrayDataUsuario = explode(";",$stringUsuario);
            if($arrayDataUsuario[0] == "")
                continue;

            if(strtolower($arrayDataUsuario[0]) == strtolower($usuario) && $arrayDataUsuario[1] == $clave){
               echo "True";
            }
            else{
                echo "Clave o Usuario Incorrecto.";
            }
        }
        fclose($referenceFile);
    }

    function cargarproducto()
    {
        $producto = new Producto( $_POST['inputId'],$_POST['inputNombre'],$_POST['inputPrecio'], $_POST['inputUsuario'] );
        $origen = $_FILES["imageInput"]["tmp_name"];
        $uploadedFileOriginalName = $_FILES["imageInput"]["name"];
        $ext = pathinfo($uploadedFileOriginalName, PATHINFO_EXTENSION);
        $fileDestination = "data/images/".$producto->nombre."_".$producto->id.".".$ext;
        $producto->imagen = $fileDestination;
        move_uploaded_file($origen, $fileDestination);

        $referenceFile = fopen("data/productos.txt", 'a');
        $stringToFile = "";
        $stringToFile = $stringToFile . $producto->ToCSV();
        fwrite($referenceFile, $stringToFile);
        fclose($referenceFile);
    }

    function modificarProducto(){
        $idBusqueda = $_POST['inputId'];
        
        $referenceFile = fopen('data/productos.txt', 'w+');
        $arrayDeProductos = array();
        $nuevoArrayDeProductos = array();
        while(!feof($referenceFile))
        {
            $stringProducto = fgets($referenceFile);
            $arrayDataProducto = explode(";",$stringProducto);

            if($arrayDataProducto[0] == "")
                continue;

            if( $idBusqueda == $arrayDataProducto[1] ){
                $arrayDataProducto[0] = $_POST['inputNombre'];
                $arrayDataProducto[2] = $_POST['inputPrecio'];
                $arrayDataProducto[4] = $_POST['inputUsuario'];
                
                $origen = $_FILES["imageInput"]["tmp_name"];
                $uploadedFileOriginalName = $_FILES["imageInput"]["name"];
                $ext = pathinfo($uploadedFileOriginalName, PATHINFO_EXTENSION);
                $fileDestination = "data/images/".$arrayDataProducto[0]."_".$arrayDataProducto[1].".".$ext;
                if (file_exists($fileDestination)) 
                    copy($fileDestination,"backup/".$arrayDataProducto[1]."_".date("dmyhis").".".$ext);    

                $arrayDataProducto[3] = $fileDestination;
                $productoNuevo = new Producto($arrayDataProducto[1],$arrayDataProducto[0],$arrayDataProducto[2],$arrayDataProducto[4]);
                $productoNuevo->imagen = $fileDestination;
                move_uploaded_file($origen, $fileDestination);                  
                array_push($nuevoArrayDeProductos, $productoNuevo);
  
            }
            else{
                $productoNuevo = new Producto($arrayDataProducto[1],$arrayDataProducto[0],$arrayDataProducto[2],$arrayDataProducto[4]);
                $productoNuevo->imagen = $arrayDataProducto[3];
                array_push($nuevoArrayDeProductos, $productoNuevo);
            }
           
        }
        foreach($nuevoArrayDeProductos as $nap){
            fwrite($referenceFile, $nap->ToString());
        }
        fclose($referenceFile);
    }
?>