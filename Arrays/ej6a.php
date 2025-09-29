<HTML>
<HEAD><TITLE> EJ6 Arrays </TITLE></HEAD>
<BODY>
<?php
   $PrimerasAsign=array("Bases Datos", "Entornos Desarrollo", "Programación");
   $SegundasAsign=array("Sistemas Informáticos", "FOL", "Mecanizado");
   $TercerasAsign=array("Desarrollo Web ES", "Desarrollo Web EC", "Despliegue", "Desarrollo Interfaces", "Inglés");
   $merge=array_merge($PrimerasAsign,$SegundasAsign,$TercerasAsign);
   unset($merge[5]);
   $merge=array_reverse($merge);
   print_r($merge);
   
?>
</BODY>
</HTML>