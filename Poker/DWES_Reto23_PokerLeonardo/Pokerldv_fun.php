<?php
    function limpiar_campos($data) 
    {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
    }

    function crear_baraja($carta)
    {
        //Cuatro 4s para los 4 tipos de carta que puede tener una baraja
        for ($i=1; $i <= 2; $i++) { 
            $baraja[]="C".$i;
        }
        for ($i=1; $i <= 2; $i++) { 
            $baraja[]="D".$i;
        }
        for ($i=1; $i <= 2; $i++) { 
            $baraja[]="P".$i;
        }
        for ($i=1; $i <= 2; $i++) { 
            $baraja[]="T".$i;
        }

        return $baraja;

    }

    function repartir_mano(&$baraja)
    {
        for ($i=0; $i < 4; $i++) {
            //check se asegura de que no entre en una baraja donde no haya cartas, en el bucle while se demuestra 
            $check=false; 
            $indice=rand(1,4);
            while(!$check)
            {
                $indice=rand(1,4);
                switch ($indice) {
                    case 1:
                        if(count($baraja["1"])!=0)
                        {
                           $check=true; 
                        }
                        break;
                    case 2:
                        if(count($baraja["J"])!=0)
                        {
                           $check=true; 
                        }
                        break;
                    case 3:
                        if(count($baraja["Q"])!=0)
                        {
                           $check=true; 
                        }
                        break;
                    case 4:
                        if(count($baraja["K"])!=0)
                        {
                           $check=true; 
                        }
                        break;
                    
                    default:
                        echo "error";
                        break;
                }
            }

            //El indice que indica el tipo de cara, hago un shuffle para que sea aleatorio y luego elimino el elemento con array splice
            //Así se mantiene el orden numérico
            switch ($indice) {
                    case 1:
                        shuffle($baraja["1"]);
                        $mano[]="1".$baraja["1"][0];
                        array_splice($baraja["1"],0,1);
                        break;
                    case 2:
                            shuffle($baraja["J"]);
                            $mano[]="J".$baraja["J"][0];
                            array_splice($baraja["J"],0,1);
                        
                        break;
                    case 3:
                            shuffle($baraja["Q"]);
                            $mano[]="Q".$baraja["Q"][0];
                            array_splice($baraja["Q"],0,1);
                        break;
                    case 4:
                            shuffle($baraja["K"]);
                            $mano[]="K".$baraja["K"][0];
                            array_splice($baraja["K"],0,1);
                        break;
                    
                    default:
                        echo "error";
                        break;
        }
    }

    return $mano;
}

function calcular_mano($mano)
{
    $cont=0;
    $letra=substr($mano[0],0,1);
    //Compruebo que la cara de la cara se repita
    foreach ($mano as $carta) 
    {
        if(substr($carta,0,1)==$letra)
        {
            $cont++;
        }
    }
    if($cont==4)
    {
        return 4;
    }elseif($cont==3)
    {
        return 3;
    }elseif($cont==2)
    {
        //Busco la doble pareja
        $letra2="";
        $cont2=0;
       foreach ($mano as $carta) 
        {
            if(substr($carta,0,1)!=$letra && $letra2=="")
            {
                $letra2=substr($carta,0,1);
            }
        }
        foreach ($mano as $carta) 
        {
            if(substr($carta,0,1)==$letra2)
            {
                $cont2++;
            }
        }
        
        if($cont2==2)
        {
            return 2.5;
        }
        else{
            return 2;
        }
    }
    else
    {
        //Si no encuentra cara que se repita, sigo buscando con otra
       $cont2=0;
       $letra2="";
       foreach ($mano as $carta) 
        {
            if(substr($carta,0,1)!=$letra && $letra2=="")
            {
                $letra2=substr($carta,0,1);
            }
        }
        foreach ($mano as $carta) 
        {
            if(substr($carta,0,1)==$letra2)
            {
                $cont2++;
            }
        }
        if($cont2==3)
        {
            return 3; 
        }elseif($cont2==2)
        {
            return 2;
        }else
        {
            //Busco en la última cara posible
            $letra3="";
            $cont3=0;
           foreach ($mano as $carta) 
            {
                if(substr($carta,0,1)!=$letra && substr($carta,0,1)!=$letra2 && $letra3=="")
                {
                    $letra3=substr($carta,0,1);
                }
            }
            foreach ($mano as $carta) 
            {
                if(substr($carta,0,1)==$letra3)
                {
                    $cont3++;
                }
            }
            if($cont3==2)
            {
                return 2;
            }else
            {
                return 0;
            }
        }

    }
}

function hacerTabla($nombre,$mano)
{
    echo "<tr>";
    echo '<td style="text-align: center; vertical-align: middle; padding: 5px;">'.$nombre."</td>";
    for ($i=0; $i < 4; $i++) { 
    echo '<td>';
    echo '<img src="images/'.$mano[$i].'.PNG" style="width: 80px; height: auto;">';
    echo "</td>";
    }
    echo "</tr>";
}

function comprobarGanador($jugadores)
{
    $mayor=max($jugadores);
    //retorno los nombres de los jugadores que cumplen que su valor sea el indicado
    return array_keys($jugadores, $mayor);
}

function printGanadores($ganadores,$bote,$max)
{
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

function imprimirGanadores($ganadores, $max)
{
    foreach($ganadores as $nombre)
    {
        echo $nombre." ha ganado la partida con una puntuación de ".$max."<br>";
    }
}

function repartirPremio($apuesta, $ganadores, $repartir)
{
    //Devuelvo el premio dado a cada ganador, siguiendo el porcentaje indicado
    $apuesta=$apuesta*$repartir;
    return ($apuesta/$ganadores);
}

?>