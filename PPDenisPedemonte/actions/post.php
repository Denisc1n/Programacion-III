<?php 
    include_once('classes/usuario.php');
    include_once('classes/producto.php');
    function doPost(){
        $operation = strtolower($_POST['operacion']);

        switch($operation){
            case 'crearusuario':
                crearUsuario();
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

            case 'eliminarproducto':
                eliminarProducto();
                break;


            default:
                echo "Invalid Operation";
                break;
        }
    }

    function crearUsuario()
    {
        if( validarNombre($_POST['inputNombre']) ){
            $usuario = new Usuario( $_POST['inputNombre'],$_POST['inputClave']);
            $referenceFile = fopen("data/usuarios.txt", 'a');
            $stringToFile = "";
            $stringToFile = $stringToFile . $usuario->ToCSV();
            fwrite($referenceFile, $stringToFile);
            echo "Creacion Completa";
            return;
        }
        echo "Usuario ya existente.";
        return;
    }

    function validarNombre($nombreConsulta)
    {
        $validate = true;
        $referenceFile = fopen("./data/usuarios.txt", "r");
        while(!feof($referenceFile))
        {
            $stringUsuario = fgets($referenceFile);
            $arrayDataUsuarios = explode(";",$stringUsuario);
            if($arrayDataUsuarios[0] == "")
                continue;
            if(strtolower($arrayDataUsuarios[0]) == strtolower($nombreConsulta)){
                 $validate = false;
            }
        }
        fclose($referenceFile);
        return $validate;
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
               fclose($referenceFile);
               return;
            }
        }
        echo "Clave o Usuario Incorrecto.";
        fclose($referenceFile);
        return;
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

/*     function modificarProducto(){
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
 */
    function modificarProducto()
    {
        $idPost = $_POST['inputId'];
        $nombrePost = $_POST['inputNombre'];
        $precioPost = $_POST['inputPrecio'];
        $usuarioPost = $_POST['inputUsuario'];
        $productos =array();
        $referenceFile = fopen("data/productos.txt", "r");
        while(!feof($referenceFile))
        {
            $stringProducto = fgets($referenceFile);
            $arrayDataProducto = explode(";",$stringProducto);
            if($arrayDataProducto[0] == "")
                continue;
            $id = $arrayDataProducto[1];
            $nombre = $arrayDataProducto[0];
            $precio = $arrayDataProducto[2];
            $imagen = $arrayDataProducto[3];
            $usuario = $arrayDataProducto[4];
            $productoNew = new Producto($id,$nombre,$precio,$usuario);
            $productoNew->imagen = $imagen;
            array_push($productos, $productoNew);      
        }
        fclose($referenceFile);
        foreach ($productos as $producto) {
            if ($producto->id  == $idPost) {
                echo "Encontro";
                $producto->nombre = $nombrePost;
                $producto->precio = $precioPost;
                $producto->usuario = $usuarioPost;
                $origen = $_FILES["imageInput"]["tmp_name"];
                $uploadedFileOriginalName = $_FILES["imageInput"]["name"];
                $ext = pathinfo($uploadedFileOriginalName, PATHINFO_EXTENSION);
                $fileDestination = "data/images/".$producto->nombre."_".$producto->id.".".$ext;  
                if (file_exists($fileDestination)) 
                    copy($fileDestination,"data/backup/".$producto->id."_".date("dmyhis").".".$ext);
                break;
            }
        }
        
            $referenceFile = fopen("data/productos.txt", "w");
        
            foreach ($productos as $producto) {
//
               fwrite($referenceFile,$producto->ToCSV());
            }
            fclose($referenceFile);
    }

    function eliminarProducto()
    {
        $idPost = $_POST['inputId'];
        $nombrePost = $_POST['inputNombre'];
        $precioPost = $_POST['inputPrecio'];
        $usuarioPost = $_POST['inputUsuario'];
        $productos =array();
        $referenceFile = fopen("data/productos.txt", "r");
        while(!feof($referenceFile))
        {
            $stringProducto = fgets($referenceFile);
            $arrayDataProducto = explode(";",$stringProducto);
            if($arrayDataProducto[0] == "")
                continue;
            $id = $arrayDataProducto[1];
            $nombre = $arrayDataProducto[0];
            $precio = $arrayDataProducto[2];
            $imagen = $arrayDataProducto[3];
            $usuario = $arrayDataProducto[4];

            if($id == $idPost)
                continue;

            $productoNew = new Producto($id,$nombre,$precio,$usuario);
            $productoNew->imagen = $imagen;
            array_push($productos, $productoNew);      
        }

        fclose($referenceFile);

        $referenceFile = fopen("data/productos.txt", "w");
        foreach ($productos as $producto) {
            fwrite($referenceFile,$producto->ToCSV());
        }
        fclose($referenceFile);
    }
?>
