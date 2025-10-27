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
        switch ($carta) {
            case 1:
                while($cartasC[$valor]==null)
                {
                    $valor=rand(1,10);
                }
                $jugador[$i]=$cartasC[$valor];
                $cartasC[$valor]=null;
                switch ($valor) {
                    case 8:
                        $suma+=0.5;
                        break;
                    case 9:
                        $suma+=0.5;
                        break;
                    case 10:
                        $suma+=0.5;
                        break;
                    
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
                    case 8:
                        $suma+=0.5;
                        break;
                    case 9:
                        $suma+=0.5;
                        break;
                    case 10:
                        $suma+=0.5;
                        break;
                    
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
                    case 8:
                        $suma+=0.5;
                        break;
                    case 9:
                        $suma+=0.5;
                        break;
                    case 10:
                        $suma+=0.5;
                        break;
                    
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
                    case 8:
                        $suma+=0.5;
                        break;
                    case 9:
                        $suma+=0.5;
                        break;
                    case 10:
                        $suma+=0.5;
                        break;
                    
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

function comprobarGanador($jugadores, $puntuacion)
{
    
}


?>