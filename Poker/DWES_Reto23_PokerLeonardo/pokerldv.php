<?php
    include "Pokerldv_fun.php";
    $nombre1=$nombre2=$nombre3=$nombre4=$bote="";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nombre1 = limpiar_campos($_POST["nombre1"]);
        $nombre2 = limpiar_campos($_POST["nombre2"]);
        $nombre3 = limpiar_campos($_POST["nombre3"]);
        $nombre4 = limpiar_campos($_POST["nombre4"]);
        $bote = limpiar_campos($_POST["bote"]);

        $baraja=[];
        $baraja["1"]=crear_baraja("1");
        $baraja["J"]=crear_baraja("J");
        $baraja["K"]=crear_baraja("K");
        $baraja["Q"]=crear_baraja("Q");

        $mano1=repartir_mano($baraja);
        $mano2=repartir_mano($baraja);
        $mano3=repartir_mano($baraja);
        $mano4=repartir_mano($baraja);
        
        $tipoMano1=calcular_mano($mano1);
        $tipoMano2=calcular_mano($mano2);
        $tipoMano3=calcular_mano($mano3);
        $tipoMano4=calcular_mano($mano4);
        echo '<table border="1">';
        for ($i=1; $i <=4 ; $i++) 
        { 
            hacerTabla(${"nombre$i"}, ${"mano$i"});
        }
        echo "</table>";

        $jugadores=array($nombre1 => $tipoMano1, $nombre2 => $tipoMano2, $nombre3 => $tipoMano3, $nombre4 => $tipoMano4);
        $ganadores=comprobarGanador($jugadores);
        $max=max($jugadores);
        printGanadores($ganadores,$bote,$max);
    }
?>