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
        echo "<tr>";
        echo '<td style="text-align: center; vertical-align: middle; padding: 5px;">'.$nombre1."</td>";
        hacerTabla($mano1);
        echo "</tr>";
        echo "<tr>";
        echo '<td style="text-align: center; vertical-align: middle; padding: 5px;">'.$nombre2."</td>";
        hacerTabla($mano2);
        echo "</tr>";
        echo "<tr>";
        echo '<td style="text-align: center; vertical-align: middle; padding: 5px;">'.$nombre3."</td>";
        hacerTabla($mano3);
        echo "</tr>";
        echo "<tr>";
        echo '<td style="text-align: center; vertical-align: middle; padding: 5px;">'.$nombre4."</td>";
        hacerTabla($mano4);
        echo "</tr>";
        echo "</table>";

        $jugadores=array($nombre1 => $tipoMano1, $nombre2 => $tipoMano2, $nombre3 => $tipoMano3, $nombre4 => $tipoMano4);
        $ganadores=comprobarGanador($jugadores);
        $max=max($jugadores);
        if(count($ganadores)==0)
        {
            echo "No hay ganadores, el bote es de ".$bote;
            $premio=0;
        } else
        {
            switch ($max) 
            {
                case 2:
                    echo "La jugada ganadores es pareja, no hay premio, el bote es de ".$bote."<br>";
                    $premio=0;
                    break;
                case 2.5:
                    $premio=repartirPremio($bote,count($ganadores),0.50);
                    break;
                case 3:
                    $premio=repartirPremio($bote,count($ganadores),0.70);
                    break;
                case 4:
                    $premio=repartirPremio($bote,count($ganadores),1);
                    break;
                
                default:
                    echo "Error";
                    break;
            }

            imprimirGanadores($ganadores,$max);
            echo "Los ganadores han obtenido ".$premio."€ de premio";

        }
    }
?>