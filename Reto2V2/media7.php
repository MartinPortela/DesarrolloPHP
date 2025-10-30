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
    

    //Hago un array por tipo de carta, así se es más fácil identificarlos por su tipo y mantener su valor, 
    // pues su índice será su puntuación y su valor será el nombre del PNG
    $cartasC=[];
    $cartasD=[];
    $cartaP=[];
    $cartasT=[];
    rellenar_array($cartasC, "C");
    rellenar_array($cartasD, "D");
    rellenar_array($cartasP, "P");
    rellenar_array($cartasT, "T");
    $cartas=array($cartasC,$cartasD,$cartasP,$cartasT);

    //Le reparto a cada jugador sus cartas, son un array con la mano y la suma será la puntuación total
    $jugador1=[];
    $suma1=0;
    $suma1=repartir($jugador1,$suma1,$numcartas,$cartas);
    $jugador2=[];
    $suma2=0;
    $suma2=repartir($jugador2,$suma2,$numcartas,$cartas);
    $jugador3=[];
    $suma3=0;
    $suma3=repartir($jugador3,$suma3,$numcartas,$cartas);
    $jugador4=[];
    $suma4=0;
    $suma4=repartir($jugador4,$suma4,$numcartas,$cartas);

    //Aquí hago la tabla y la función hacerTabla pondrá la imagen
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
    //Hago un array que contenga todos los jugadores con su puntuación para almacenarlos fácilmente
    $jugadores=array($nombre1 => $suma1 ,$nombre2 => $suma2,$nombre3 => $suma3,$nombre4 => $suma4);
    //array ganadores donde se van a almacenar los nombres de los ganadores
    $ganadores=comprobarGanador($jugadores);
    
    //Reparto el premio según lo indicado en el enunciado
    if(count($ganadores)==0)
    {
        echo "No hay ganadores, el bote es de ".$apuesta;
        $premio=0;
    } else{
    if($jugadores[$ganadores[0]]==7.5)
    {
        $premio=repartirPremio($apuesta, count($ganadores),0.80);
    }
    else{
    $premio=repartirPremio($apuesta, count($ganadores),0.50);
    }
    imprimirGanadores($jugadores,$ganadores);
    echo "Los ganadores han obtenido ".$premio."€ de premio";
    }
    crearFichero($jugadores,$ganadores,$premio);
}
?>