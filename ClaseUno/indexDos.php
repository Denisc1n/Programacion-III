<?php
    require_once "classes/alumno.php";

    var_dump($_POST);

    $alumno = new Alumno($_POST['nombre'],$_POST['apellido'],$_POST['dni'], $_POST['legajo']); 
    echo "<BR>";
    echo"Salida";
    echo "<BR>";

    echo $alumno->ToJSON();
?>