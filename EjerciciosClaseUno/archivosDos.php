<?php

    $fileReference = fopen("myFileWithExplode.txt", 'w+');

    fwrite($fileReference, "denis;pedemonte;103322;34712488;alvaro;sanchez;121223;22321123;clementina;bertoldi;102121;35500000".PHP_EOL);

    fclose($fileReference);

    $fileReference = fopen("myFileWithExplode.txt",'r');

    $reading = fread($fileReference, filesize("myFileWithExplode.txt"));
    //$array =array();
    $array = explode(";", $reading);

    $fileReference = fopen("myFileWithExplode.txt", 'a+');
    
    foreach ($array as $value) {
        fwrite($fileReference,$value.PHP_EOL);
    }
    fclose($fileReference);
?>