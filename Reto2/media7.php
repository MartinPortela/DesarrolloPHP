<?php
    include "media7fun.php";
    $nombre1=$nombre2=$nombre3=$nombre4=$numcartas=$apuesta="";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nombre1 = limpiar_campos($_POST["nombre1"]);
        $nombre2 = limpiar_campos($_POST["nombre2"]);
        $nombre3 = limpiar_campos($_POST["nombre3"]);
        $nombre4 = limpiar_campos($_POST["nombre4"]);
        $numcartas = limpiar_campos($_POST["numcartas"]);
        $apuesta = limpiar_campos($_POST["apuesta"]);
    }

    $cartasC=[];
    $cartasD=[];
    $cartaP=[];
    $cartasT=[];
    rellenar_array($cartasC, "C");
    rellenar_array($cartasD, "D");
    rellenar_array($cartasP, "P");
    rellenar_array($cartasT, "T");

    $jugador1=[];
    $suma1=0;
    $suma1=repartir($jugador1,$suma1,$numcartas,$cartasC,$cartasD,$cartasP,$cartasT);
    $jugador2=[];
    $suma2=0;
    $suma2=repartir($jugador2,$suma2,$numcartas,$cartasC,$cartasD,$cartasP,$cartasT);
    $jugador3=[];
    $suma3=0;
    $suma3=repartir($jugador3,$suma3,$numcartas,$cartasC,$cartasD,$cartasP,$cartasT);
    $jugador4=[];
    $suma4=0;
    $suma4=repartir($jugador4,$suma4,$numcartas,$cartasC,$cartasD,$cartasP,$cartasT);

    echo '<table border="1">';
    echo "<tr>";
    echo '<td style="text-align: center; vertical-align: middle; padding: 5px;">'.$nombre1."</td>";
    hacerTabla($jugador1,$numcartas);
    echo "</tr>";
    echo "<tr>";
    echo '<td style="text-align: center; vertical-align: middle; padding: 5px;">'.$nombre2."</td>";
    hacerTabla($jugador2,$numcartas);
    echo "</tr>";
    echo "<tr>";
    echo '<td style="text-align: center; vertical-align: middle; padding: 5px;">'.$nombre3."</td>";
    hacerTabla($jugador3,$numcartas);
    echo "</tr>";
    echo "<tr>";
    echo '<td style="text-align: center; vertical-align: middle; padding: 5px;">'.$nombre4."</td>";
    hacerTabla($jugador4,$numcartas);
    echo "</tr>";
    echo "</table>";
    $jugadores=array($nombre1,$nombre2,$nombre3,$nombre4);
    $puntuacion=array($suma1,$suma2,$suma3,$suma4);
        $cont=0;
        if($suma1==7.5)
        {
            echo $nombre1." ha ganado con una puntuación de ".$suma1;
            echo "<br>";
            $cont=1;
        }
        if($suma2==7.5)
        {
            echo $nombre2." ha ganado con una puntuación de ".$suma2;
            echo "<br>";
            $cont=1;
        }
        if($suma3==7.5)
        {
            echo $nombre3." ha ganado con una puntuación de ".$suma3;
            echo "<br>";
            $cont=1;
        }
        if($suma4==7.5)
        {
            echo $nombre4." ha ganado con una puntuación de ".$suma4;
            echo "<br>";
            $cont=1;
        }
    if($cont==0)
    {
        
    }
?>