<HTML>
<HEAD><TITLE> EJ1-Conversion IP Decimal a Binario </TITLE></HEAD>
<BODY>
<?php
    $ip="192.18.16.204";
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

    //Transformación a decimal
    $ip1=str_pad(decbin($ip1),8,"0",STR_PAD_LEFT);
    $ip2=str_pad(decbin($ip2),8,"0",STR_PAD_LEFT);
    $ip3=str_pad(decbin($ip3),8,"0",STR_PAD_LEFT);
    $despues3=str_pad(decbin($despues3),8,"0",STR_PAD_LEFT);
    echo "La IP $ip en decimal es $ip1.$ip2.$ip3.$despues3 <br>";
?>
</BODY>
</HTML>