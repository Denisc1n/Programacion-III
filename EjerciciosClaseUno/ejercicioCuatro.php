<?php
    $resultado = 0;

    for ($i=1; $i < 1000; $i++) { 
        if ( $resultado+$i > 1000 ) {
            break;
        }
        $resultado+=$i; 
    }

    echo "Resultado: ".$resultado;
    echo"<br>"."Se Sumaron $i numeros";
?>