<!DOCTYPE HTML>  
<HTML>
<HEAD> <TITLE>CambioBase</TITLE>
</HEAD>
<BODY>
<h1>CAMBIO DE BASE</h1>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

Numero decimal:
<input type='number' name='decimal' value='' size=15><br><br><br>
<?php
$convertir="";
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $convertir=test_input($_POST['operacion']);
}
echo "Numero binario: <input type='text' value='",decbin($convertir),"'><br><br><br>";
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>
<input type='radio' name="operacion" value="binario">
                                               Binario<br>
<input type='radio' name="operacion" value="octal">
                                              Octal<br>
<input type='radio' name="operacion" value="hexa">
                                              Hexadecimal<br>
<input type='radio' name="operacion" value="todos">
                                              Todos los sistemas<br>   
<input type="submit" value="enviar">
<input type="reset" value="borrar">
</FORM>

</BODY>
</HTML>
