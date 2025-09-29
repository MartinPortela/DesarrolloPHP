<HTML>
<HEAD><TITLE> EJ5 Arrays </TITLE></HEAD>
<BODY>
<?php
   $PrimerasAsign=array("Bases Datos", "Entornos Desarrollo", "Programación");
   $SegundasAsign=array("Sistemas Informáticos", "FOL", "Mecanizado");
   $TercerasAsign=array("Desarrollo Web ES", "Desarrollo Web EC", "Despliegue", "Desarrollo Interfaces", "Inglés");
   $ArrayTodos=array("","","","","","","","","","","");
    $cont1=0;
    $cont2=0;
    $cont3=0;
    foreach($ArrayTodos as &$asign){
        if($cont1 < 3) {
        $asign=$PrimerasAsign[$cont1];
        $cont1++;
        } else if($cont2 < 3){
        $asign=$SegundasAsign[$cont2];
        $cont2++; 
        }
        else{
            $asign=$TercerasAsign[$cont3];
            $cont3++;
        }
    }

    foreach($ArrayTodos as $imprimir){
        echo $imprimir . "<br>";
    }

    $merge=array_merge($PrimerasAsign,$SegundasAsign,$TercerasAsign);
    print_r($merge);
    foreach($SegundasAsign as $annadir)
    {
        array_push($PrimerasAsign,$annadir);
    }
    foreach($TercerasAsign as $annadir)
    {
        array_push($PrimerasAsign,$annadir);
    }    
    echo "<br>";
    print_r($PrimerasAsign);
?>
</BODY>
</HTML>