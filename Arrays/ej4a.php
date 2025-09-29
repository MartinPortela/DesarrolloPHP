<HTML>
<HEAD><TITLE> EJ4 Arrays </TITLE></HEAD>
<BODY>
<?php
    $numeros=0;;
    $cont=0;
    for ($i=0; $i < 20; $i++) { 
       $numerosArray[$i]=$numeros;
       $numeros++;
    }
    $numerosArray=array_reverse($numerosArray);
     echo "<table>";
     echo "<thead><tr>";
     echo "<th>Índice</th><th>Binario</th><th>Octal</th></tr></thead>";
     foreach ($numerosArray as $numeros) {
        echo "<tr>";
        echo "<td>".$cont."</td>";
        echo "<td>".decbin($numeros)."</td>";
        echo "<td>".decoct($numeros)."</td>";
        echo"</tr>";
        $cont++;
     }
     echo "</table>";
?>
</BODY>
</HTML>