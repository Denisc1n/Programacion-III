<?php

    $fileReference = fopen("myFile.txt", 'w+');

    fwrite($fileReference, "My standard writing on file".PHP_EOL);
    fwrite($fileReference, "My standard writing on file, a different line".PHP_EOL);
    fwrite($fileReference, "A Third line comes along.".PHP_EOL);
    fclose($fileReference);

    $fileReference = fopen("myFile.txt", 'r');

    while(!feof($fileReference))
    {
        $fileArray[] = fgets($fileReference);
    }
    fclose($fileReference);
    
    foreach ($fileArray as $value) 
    {
        echo $value."<BR>";
    }

    $fileArray[0] = "This my MODIFIED FIRST standard writing on file".PHP_EOL;

    foreach ($fileArray as $value) 
    {
        echo $value."<BR>";
    }

    $fileReference = fopen("myFile.txt", 'w');
    foreach ($fileArray as $value) 
    {
       fwrite( $fileReference, $value );
    }
    fclose($fileReference);

?>