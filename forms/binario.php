<!DOCTYPE HTML>  
<HTML>
<HEAD> <TITLE>BINARIO</TITLE>
</HEAD>
<BODY>
<h1>CONVERSOR BINARIO</h1>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

Numero decimal:
<input type='number' name='decimal' value='' size=15><br><br><br>
<?php
$convertir="";
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $convertir=test_input($_POST['decimal']);
}
echo "Numero binario: <input type='text' value='",decbin($convertir),"'><br><br><br>";
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>
<input type="submit" value="enviar">
<input type="reset" value="borrar">
</FORM>

</BODY>
</HTML>
