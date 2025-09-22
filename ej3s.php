<HTML>
<HEAD><TITLE> EJ2-Direccion Red – Broadcast y Rango</TITLE></HEAD>
<BODY>
<?php
    $ip="192.168.16.100/16";
    //El punto existe para marcar la ruptura, lo almaceno como variable
    $punto=".";
    //pos será mi variable para indicar en qué lugar de la string está el punto
    $pos=strpos($ip,$punto);
    //Las variables IP sirven para almacenar las partes de la IP que convertiré a binario,
    //mientras que las variables despues, son el resto del corte después del punto
    $ip1=substr($ip, 0, $pos);
    $despues=substr($ip,$pos+1);
    $pos=strpos($despues,$punto);
    $ip2=substr($despues, 0, $pos);
    $despues2=substr($despues,$pos+1);
    $pos=strpos($despues2,$punto);
    $ip3=substr($despues2, 0, $pos);
    $despues3=substr($despues2,$pos+1);
    $pos=strpos($despues3,"/");
    $ip4=substr($despues3, 0, $pos);
    $mascaraRed=substr($despues3,$pos+1);
    //Transformación a decimal
    $ip1=str_pad(decbin($ip1),8,"0",STR_PAD_LEFT);
    $ip2=str_pad(decbin($ip2),8,"0",STR_PAD_LEFT);
    $ip3=str_pad(decbin($ip3),8,"0",STR_PAD_LEFT);
    $ip4=str_pad(decbin($ip4),8,"0",STR_PAD_LEFT);
    $ipEntera=$ip1+$ip2+$ip3+$ip4;
    settype($cont, "integer");
    $cont=0;
    $Parte2="";
    $Parte1="";
    $Parte3="";
    for ($i=0; $i < $mascaraRed; $i++) 
    { 
        if ($cont===8) {
            $Parte2+=substr($ipEntera,$i,$i+1);
            $cont++;
        } else if ($cont===16) {
            $Parte3+=substr($ipEntera,$i,$i+1);
        } else {
            $Parte1+=substr($ipEntera,$i,$i+1);
            $cont++;
        }
    }
    $cantidad=32-$mascaraRed;
    echo $cantidad;

?>
</BODY>
</HTML>