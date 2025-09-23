<HTML>
<HEAD><TITLE> EJ2-Direccion Red – Broadcast y Rango</TITLE></HEAD>
<BODY>
<?php
    $ip="10.33.15.100/8";
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
    $ipEntera=$ip1.$ip2.$ip3.$ip4;
    settype($cont, "integer");
    $cont=0;
    $Parte2="";
    $Parte1="";
    $Parte3="";
    $Parte4="";

    //Bucle para rellenar las distintas partes de la IP, si ya está llena una, se va a la otra
    for ($i=0; $i < $mascaraRed; $i++) 
    { 
        if ($cont>=8) {
            $Parte2.=substr($ipEntera,$i,1);
            $cont++;
        } else if ($cont>=16) {
            $Parte3.=substr($ipEntera,$i,1);
            $cont++;
        }else if ($cont>=24){
            $Parte4.=substr($ipEntera,$i,1);
        } else {
            $Parte1.=substr($ipEntera,$i,1);
            $cont++;
        }
    }

    //Relleno la IP en binario para luego poder crear la Broadcast y la de Red
    $ipBinario=$Parte1.$Parte2.$Parte3.$Parte4;
    //Broadcast se rellena con 1 y luego la Red con 0 hasta que tengan 32 dígitos
    $ipBroadcast=str_pad($ipBinario,32,"1",STR_PAD_RIGHT);
    $ipRed=str_pad($ipBinario,32,"0",STR_PAD_RIGHT);
    //Pongo los puntos correspondientes a cada IP, así es más fácil cortarlos en partes y transformalos de nuevo a
    $ipBroadcast = substr_replace($ipBroadcast,".",8,0);
    $ipBroadcast = substr_replace($ipBroadcast,".",17,0);
    $ipBroadcast = substr_replace($ipBroadcast,".",26,0);
    $ipRed = substr_replace($ipRed,".",8,0);
    $ipRed = substr_replace($ipRed,".",17,0);
    $ipRed = substr_replace($ipRed,".",26,0);
    
    //Aquí abajo se hace el proceso de cambiar la IP a decimal de nuevo
    $pos=strpos($ipBroadcast,$punto);
    $ip1=substr($ipBroadcast, 0, $pos);
    $despues=substr($ipBroadcast,$pos+1);
    $pos=strpos($despues,$punto);
    $ip2=substr($despues, 0, $pos);
    $despues2=substr($despues,$pos+1);
    $pos=strpos($despues2,$punto);
    $ip3=substr($despues2, 0, $pos);
    $despues3=substr($despues2,$pos+1);
    $ip1=bindec($ip1);
    $ip2=bindec($ip2);
    $ip3=bindec($ip3);
    $despues3=bindec($despues3);

    $ipBroadcast=$ip1.".".$ip2.".".$ip3.".".$despues3;

    $ip1Red=substr($ipRed, 0, $pos);
    $despues=substr($ipRed,$pos+1);
    $pos=strpos($despues,$punto);
    $ip2Red=substr($despues, 0, $pos);
    $despues2=substr($despues,$pos+1);
    $pos=strpos($despues2,$punto);
    $ip3Red=substr($despues2, 0, $pos);
    $despues3Red=substr($despues2,$pos+1);
    $ip1Red=bindec($ip1Red);
    $ip2Red=bindec($ip2Red);
    $ip3Red=bindec($ip3Red);
    $despues3Red=bindec($despues3Red);

    $ipRed=$ip1Red.".".$ip2Red.".".$ip3Red.".".$despues3Red;

    //Esta es la última parte de las direcciones de broadcast y red, a las que les resto y sumo 1 de forma respectiva para hacer los rangos
    $despues3Red++;
    $despues3--;

    $RangoMin=$ip1Red.".".$ip2Red.".".$ip3Red.".".$despues3Red;
    $RangoMax=$ip1.".".$ip2.".".$ip3.".".$despues3;

    printf("IP: $ip <br>");
    printf("Máscara: $mascaraRed <br>");
    printf("Dirección Red: $ipRed <br>");
    printf("Dirección Broadcast: $ipBroadcast <br>");
    printf("Rango: $RangoMin a $RangoMax");


?>
</BODY>
</HTML>