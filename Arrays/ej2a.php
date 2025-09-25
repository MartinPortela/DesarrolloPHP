<HTML>
<HEAD><TITLE> EJ1 Arrays </TITLE></HEAD>
<BODY>
<?php
    $impar=1;
    $suma=0;
    $cont=0;
    $mediaPar=0;
    $mediaImpar=0;
    $contPar=0;
    $contImpar=0;
    for ($i=0; $i < 20; $i++) { 
       $numerosImpares[$i]=$impar;
       $impar=$impar+2;
    }

     echo "<table>";
     echo "<thead><tr>";
     echo "<th>Índice</th><th>Valor</th><th>Suma</th></tr></thead>";
     foreach ($numerosImpares as $impares) {
        $suma+=$impares;
        echo "<tr>";
        echo "<td>".$cont."</td>";
        echo "<td>".$impares."</td>";
        echo "<td>".$suma."</td>";
        echo"</tr>";
        if($cont%2===0){
            $mediaPar+=$impares;
            $contPar++;
        }
        else{
            $mediaImpar+=$impares;
            $contImpar++;
        }        
        $cont++;

     }
     echo "</table> <br><br>";

     $mediaPar=$mediaPar/$contPar;
     $mediaImpar=$mediaImpar/$contImpar;

     echo $mediaPar."<br>";
     echo $mediaImpar
?>
</BODY>
</HTML>