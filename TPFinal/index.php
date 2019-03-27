<?php
    require_once "classes/alumno.php";
    $operationType = $_GET["tipo"];
    $alumno = new alumno($_POST['nameInput'],$_POST['lastNameInput'],$_POST['idInput'],$_POST['fileIdNumber']);
    $alumnoDos = new alumno($_POST['nameInputDos'],$_POST['lastNameInputDos'],$_POST['idInputDos'],$_POST['fileIdNumberDos']);
    $arrayAlumnos = array($alumno, $alumnoDos);
    $method = $_SERVER["REQUEST_METHOD"];
    

    if($operationType === "json")
    {
        $referenceFile = fopen("data/myJSONfile.json", 'w');
        $stringToFile = json_encode($arrayAlumnos);
    }

    elseif ( $operationType === "csv") 
    {
        $referenceFile = fopen("data/myCSVfile.txt", 'w');
        $stringToFile = "";
        $last_key = key($arrayAlumnos);
        foreach ($arrayAlumnos as $key => $value) 
        {
            $stringToFile = $stringToFile . $value->ToCSV();
        }
    }
    else
    {
        echo "Operacion Invalida";
    }
    $string = rtrim($stringToFile,";");
    fwrite($referenceFile, $string);
    fclose($referenceFile);
    
?>