<?php
    require_once "classes/accesodatos.php";
    require_once "classes/dao/alumnoDAO.php";

    function doGet()
    {
        $op = isset($_GET['op']) ? $_GET['op'] : NULL;

        switch($op){
            case 'listaralumnos':
                listarAlumnos();
                break;

            default:
                echo "Invalid";
                break;
        }


    }

    function listarAlumnos(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

        $consulta = $objetoAccesoDato->RetornarConsulta("select * from alumno");
        $consulta->execute();
        
        $consulta->setFetchMode(PDO::FETCH_INTO, new AlumnoDAO);
        foreach ($consulta as $cd) {
            var_dump($cd);
            print_r($cd->MostrarDatos());
            print("
                    ");
        }
    }

    function getArchivos()
    {
        $operationType = $_GET["tipo"];
        $arrayToReturn = array();
        if($operationType === "json")
        {
            $jsonData = file_get_contents("data/myJSONfile.json");
            $stringToFile = "";
            $arrayFromFile = json_decode( $jsonData );
            
            echo "<pre>";
            print_r($arrayFromFile);
            echo "</pre>";
            echo "<br>";
            echo $jsonData;
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
    }


?>