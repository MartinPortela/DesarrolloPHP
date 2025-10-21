<!DOCTYPE HTML>  
<HTML>
<HEAD> <TITLE>fichero1 Martín Portela Seguí</TITLE>
</HEAD>
<BODY>
<h1>Alumnos</h1>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

Nombre:
<input type='text' name='nombre' value='' size=40><br><br><br>
Apellido 1:
<input type='text' name='apellido1' value='' size=40><br><br><br>
Apellido 2:
<input type='text' name='apellido2' value='' size=41><br><br><br>
Fecha de nacimiento:
<input type='date' name='fecha' value='' size=10><br><br><br>
Localidad:
<input type='text' name='localidad' value='' size=27><br><br><br>
<?php
$apellido1=$apellido2=$fecha=$localidad=$nombre="";
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $nombre=test_input($_POST['nombre']);
    $apellido1=test_input($_POST['apellido1']);
    $apellido2=test_input($_POST['apellido2']);
    $fecha=test_input($_POST['fecha']);
    $localidad=test_input($_POST['localidad']);

    $f1=fopen("alumnos1.txt","a+");
    fwrite($f1, str_pad($nombre,40));
    fwrite($f1, str_pad($apellido1,40));
    fwrite($f1, str_pad($apellido2,41));
    fwrite($f1, str_pad($fecha,11));
    fwrite($f1, str_pad($localidad,27)."\n");
    fclose($f1);
}

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
