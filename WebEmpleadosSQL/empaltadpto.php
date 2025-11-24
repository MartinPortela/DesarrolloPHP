<!DOCTYPE HTML>  
<HTML>
<HEAD> <TITLE>empaltadpto</TITLE>
</HEAD>
<BODY>
<h1>Dar de alta departamentos</h1>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

Inserte un nuevo departamento:
<input type='text' name='nombre' value='' size=25><br><br><br>
<input type="submit" value="enviar">
<input type="reset" value="borrar">
<?php
$convertir="";
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $nombre=test_input($_POST['nombre']);
    
    $servername = "localhost";
    $username = "root";
    $password = "rootroot";
    $dbname = "empleados";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->beginTransaction();
    $stmt2 = $conn->prepare("SELECT MAX(cod_dpto) as max FROM departamento");
    $stmt = $conn->prepare("INSERT INTO departamento (nombre_dpto,cod_dpto) VALUES (:nombre_dpto,:cod_dpto)");
    $stmt->bindParam(':cod_dpto', $cod_dpto);
    $stmt->bindParam(':nombre_dpto', $nombre_dpto);
    $stmt2->execute();

    // set the resulting array to associative
     $stmt2->setFetchMode(PDO::FETCH_ASSOC);
	 $resultado=$stmt2->fetchAll();
     if($resultado!=null)
     {
     $max=$resultado[0]["max"];
     $max=substr($max,-3);
     $max++;
     $max=str_pad($max,3,0,STR_PAD_LEFT);
     $cod_dpto='C'.$max;
     }else
     {
        $cod_dpto='CD001';
     }
     $nombre_dpto=$nombre;
     $stmt->execute();
     echo "Departamento insertado";
     $conn->commit();
}
catch(PDOException $e) {
    echo "Error: " . $e->getMessage();

    echo "Código de error: " . $e->getCode() . "<br>";

    $conn->rollBack();
}
$conn = null;
}
 function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>
</FORM>

</BODY>
</HTML>
