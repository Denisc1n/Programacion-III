<?php
    /*
    echo("Hola PHP\n");
    $minombre="Denis";
    $miLegajo = 103322;
    echo $minombre." ".$miLegajo."<BR>";
    var_dump($minombre);
    var_dump($miLegajo);
    echo("<BR>");

    //Array numerico
    $heroes = array(1,2,3,4);
    $heroes[]=22;
    var_dump($heroes);

    echo("<br>");
    //Array asociativo
    $heroesAsociativo = array("nombre"=>"Batman", "Superpoder"=>"Batimovil");
    $heroesAsociativo[] = array();
    $heroesAsociativo[1]["nombre"] = "Superman";
    $heroesAsociativo[1]["Superpoder"] = "Volar";
    $heroesAsociativo[]= array("nombre"=>"linterna verde", "Superpoder"=>"anillo");

    //var_dump($heroesAsociativo);
    echo("<BR>");
    var_dump($_GET);
    echo("<br>");
    var_dump($_POST);
    echo("<BR>");
    echo("<BR>");
    $numeros = array(1,2,3,4,5,6,7,8,9,10);
    var_dump($numeros);
    echo("<BR>");
    shuffle($numeros);
    echo("<BR>");
    var_dump($numeros);
    sort($numeros);
    echo("<BR>");
    var_dump($numeros);
    echo("<BR>");
    arsort($numeros);
    echo("<BR>");
    var_dump($numeros);
    echo("<BR>");

    if($_GET["order"]=="1"){
        echo("Orden Descendente");
        echo("<BR>");
        arsort($numeros);
        echo("<BR>");
        var_dump($numeros);
    }
    else{
        echo("Orden Ascendente");
        asort($numeros);
        echo("<BR>");
        var_dump($numeros);
    }
*/
    $persona = array("nombre"=>"denis");
    var_dump($persona);
    echo("<BR>");
    echo($persona["nombre"]);
    $personaO = (object)$persona;
    var_dump($personaO);
    $personaO->nombre="DENIS";
    var_dump($personaO);

    $personaSTD = new stdclass();
    $personaSTD->nombre = "Leonel";
    var_dump($personaSTD);

?>