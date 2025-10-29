<?php
function limpiar_campos($data) 
{
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
function rellenar_array(&$array, $nombre)
{
    //El valor será el nombre del PNG, con los casos de J,K y Q en cuenta
    for ($i = 0; $i < 10; $i++) {
        switch ($i) {
            case 7:
                $array[$i+1] = "J".$nombre;
                break;
            case 8:
                $array[$i+1] = "K".$nombre;
                break;
            case 9:
                $array[$i+1] = "Q".$nombre;
                break;
            
            default:
                $array[$i+1] = ($i+1).$nombre;
                break;
        }
        
    }
}
function repartir(&$jugador,$suma,$numcartas,&$cartasC,&$cartasD,&$cartasP,&$cartasT)
{
    for ($i=0; $i < $numcartas; $i++) { 
        $carta=rand(1,4);
        $valor=rand(1,10);
        //Con estos valores random, me aseguro poder repartir de forma completamente aleatoria las cartas
        //Checkeo siempre si será null el valor, pues lo que hago es que si ya ha salido una carta, pongo su posición en null, así es fácil de checkear
        switch ($carta) {
            case 1:
                while($cartasC[$valor]==null)
                {
                    //mientras sea null, sigo generando números
                    $valor=rand(1,10);
                }
                $jugador[$i]=$cartasC[$valor];
                $cartasC[$valor]=null;
                //Las sumas las hago con switch cases para los casos de la  J, Q y K, que valen 0.5
                switch ($valor) {
                    case 8 || 9 || 10:
                        $suma+=0.5;
                    
                    default:
                        $suma+=$valor;
                        break;
                }
                
                break;
            case 2:
                while($cartasD[$valor]==null)
                {
                    $valor=rand(1,10);
                }
                $jugador[$i]=$cartasD[$valor];
                $cartasD[$valor]=null;
                switch ($valor) {
                    case 8 || 9 || 10:
                        $suma+=0.5;
                    
                    default:
                        $suma+=$valor;
                        break;
                }
                break;
            case 3:
                while($cartasP[$valor]==null)
                {
                    $valor=rand(1,10);
                }
                $jugador[$i]=$cartasP[$valor];
                $cartasP[$valor]=null;
                switch ($valor) {
                    case 8 || 9 || 10:
                        $suma+=0.5;
                    
                    default:
                        $suma+=$valor;
                        break;
                }
                break;
            case 4:
                while($cartasT[$valor]==null)
                {
                    $valor=rand(1,10);
                }
                $jugador[$i] = $cartasT[$valor];
                $cartasT[$valor]=null;
                switch ($valor) {
                    case 8 || 9 || 10:
                        $suma+=0.5;
                    
                    default:
                        $suma+=$valor;
                        break;
                }
                break;
            
            default:
                echo "error";
                break;
        }
    }
    return $suma;
}

function hacerTabla($jugador,$numcartas)
{
    for ($i=0; $i < $numcartas; $i++) { 
    echo '<td>';
    echo '<img src="images/'.$jugador[$i].'.PNG" style="width: 80px; height: auto;">';
    echo "</td>";
    }
}

function comprobarGanador($jugadores,$nombre1,$nombre2,$nombre3,$nombre4)
{
    $mayor=0;
    for ($i=1; $i <= 4; $i++) 
    { 
        //Me aseguro que el valor del ganador sea menor o igual que 7.5 y que el máximo encontrado
        if($jugadores[${"nombre$i"}]>$mayor && $jugadores[${"nombre$i"}]<=7.5)
        {
            $mayor=$jugadores[${"nombre$i"}];
        }
    }
    //retorno los nombres de los jugadores que cumplen que su valor sea el indicado
    return array_keys($jugadores, $mayor);
}

function repartirPremio($apuesta, $ganadores, $repartir)
{
    //Devuelvo el premio dado a cada ganador, siguiendo el porcentaje indicado
    $apuesta=$apuesta*$repartir;
    return ($apuesta/$ganadores);
}

function imprimirGanadores($jugadores,$ganadores)
{
    foreach($ganadores as $nombre)
    {
        echo $nombre." ha ganado la partida con una puntuación de ".$jugadores[$nombre]."<br>";
    }
}

function crearFichero($jugadores,$ganadores,$premio)
{
    $fecha = date('m-d-Y_h-i-s');
    $cont=0;
    $fichero=fopen("apuestas_".$fecha.".txt","w+");
    //Si no hay ganadores, todo premio será 0 y no tengo que checkear ganadores
    if(count($ganadores)==0)
    {
        foreach ($jugadores as $nombre => $suma) 
        {
           fwrite($fichero,$nombre."#".$suma."#".$premio."\r\n");
        }
    } else{
    foreach ($jugadores as $nombre => $suma) 
    {
        //Primero me aseguro que el cont no sea igual a la cantidad de ganadores, porque en ese caso estaría accediendo a una parte del array que no existe
        if(count($ganadores)!=$cont && $nombre==$ganadores[$cont])
        {
            $cont++;
            fwrite($fichero,$nombre."#".$suma."#".$premio."\r\n");
        }
        else
        {
           fwrite($fichero,$nombre."#".$suma."#"."0\r\n"); 
        }
    }
}
fwrite($fichero,"TOTALPREMIOS#".count($ganadores)."#".count($ganadores)*$premio);
fclose($fichero);
}

?>