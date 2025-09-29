<HTML>
<HEAD><TITLE> EJ7 Arrays </TITLE></HEAD>
<BODY>
<?php
   $alumnos=array("Mauricio"=>17,"Pablo"=>20,"Patricio"=>18,"José"=>19,"Marta"=>17);

   foreach($alumnos as $clave => $valor)
   {
      echo $clave." ".$valor."<br>";
   }
?>
</BODY>
</HTML>