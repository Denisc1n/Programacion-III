<?php   
    $operador = $_POST["operador"];
    $varUno = $_POST["varUno"];
    $varDos = $_POST["varDos"];
    //var_dump($_POST);
    switch ($operador) {
        case '+':
            $resultado = $varUno+$varDos;
            echo "Resultado SUMA: ".$resultado;
            break;

        case '-':
            $resultado = $varUno-$varDos;
            echo "Resultado RESTA: ".$resultado;
            break;
        
        case '*':
            $resultado = $varUno*$varDos;
            echo "Resultado MULTIPLICACION: ".$resultado;
            break;

        case '/':
            if($varDos != 0)
            {
                $resultado = $varUno/$varDos;
                echo "Resultado DIVISION: ".$resultado;
            }
            else
            echo "Division por Cero";
            break;
        
        
        
        default:
            echo "Opcion Invalida";
            break;
    }


?>