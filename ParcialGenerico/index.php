<?php
    require_once "actions/post.php";
    require_once "actions/get.php";

    $method = $_SERVER["REQUEST_METHOD"];
    
    switch ($method) {
        case 'POST':
           doPost();
            break;

        case 'GET':
            doGet();
            break;

        
        default:
            echo "Metodo Invalido.";
            break;
    }
?>