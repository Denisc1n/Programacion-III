<?php
    require "alumno.php";
    //$operacionTipo = $_GET["tipo"];
    $alumno = new alumno($_POST['nameInput'],$_POST['lastNameInput'],$_POST['idInput'],$_POST['fileIdNumber']);
    $alumnoDos = new alumno($_POST['nameInputDos'],$_POST['lastNameInputDos'],$_POST['idInputDos'],$_POST['fileIdNumberDos']);
    $arrayAlumnos = array($alumno, $alumnoDos);

    

    $referenceFile = fopen("myJSONfile.json", 'w');

    $stringToFile = json_encode($arrayAlumnos);

    fwrite($referenceFile, $stringToFile);
    fclose($referenceFile);
    
?>