<!DOCTYPE HTML>  
<HTML>
<HEAD> <TITLE>Comaltacat</TITLE>
</HEAD>
<BODY>
<h1>Dar de alta almacenes</h1>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

Inserte un nuevo almacén:
<input type='text' name='almacen' value='' size=25><br><br><br>
<input type="submit" value="enviar">
<input type="reset" value="borrar">
<?php
include "funciones_bdd.php";
$convertir="";
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $almacen=test_input($_POST['almacen']);

try {
    $conn = conexion();
    $stmt2 = $conn->prepare("SELECT num_almacen FROM almacen");
    $stmt = $conn->prepare("INSERT INTO almacen (num_almacen,localidad) VALUES (:num_alm,:localidad)");
    $stmt->bindParam(':num_alm', $num_alm);
     $stmt->bindParam(':localidad', $localidad);
    $stmt2->execute();

    // set the resulting array to associative
     $stmt2->setFetchMode(PDO::FETCH_ASSOC);
	 $resultado=$stmt2->fetchAll();
     if($resultado!=null)
     {
	 $ultimaClave = array_key_last($resultado);
     $max=$resultado[$ultimaClave]["num_almacen"];
     $max++;
     $num_alm=$max;
     }else
     {
        $num_alm=1;
     }
     $localidad=$almacen;
     $stmt->execute();
     echo "Almacén insertado";
}
catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
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