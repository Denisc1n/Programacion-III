<?php
    $a =1;
    $b =3;
    $c =5;

    echo "A=$a B=$b C=$c";

    if ( $a > $b && $a < $c || $a < $b && $a > $c) {
        echo "El medio es A ($a)";
    }
    elseif ( $b > $a && $b < $c || $b < $a && $b > $c ) {
        echo "El medio es B ($b)";
    }
    elseif ($c>$a && $c < $b || $c < $a && $c>$b ) {
        echo "El medio es C";
    }
    else {
        echo "no hay medio";
    }
?>