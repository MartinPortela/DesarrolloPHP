<!DOCTYPE HTML>  
<HTML>
<HEAD> <TITLE>fichero3 Martín Portela Seguí</TITLE>
</HEAD>
<BODY>
<h1>Alumnos</h1>

<?php
    $f1=fopen("alumnos1.txt","r");
    echo "<table>";
    echo "<tr>";
    while(!feof($f1)){
        if(fgetc($f1)==""){

        }
    }
    echo "</tr>";
    echo "</table>";
    fclose($f1);
?>

</BODY>
</HTML>