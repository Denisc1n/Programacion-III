<?php 
    include_once('classes/classTwo.php');
    
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
?>