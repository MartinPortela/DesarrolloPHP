<!DOCTYPE HTML>  
<HTML>
<HEAD> <TITLE>Comaltapro</TITLE>
</HEAD>
<BODY>
<h1>Dar de alta productos</h1>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<?php
$convertir="";
$servername = "localhost";
$username = "root";
$password = "rootroot";
$dbname = "comprasweb";

try {
    //Conexión con la base de datos
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //Selecciono las categorías
    $stmt = $conn->prepare("SELECT id_producto,nombre FROM producto");
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $productos=$stmt->fetchAll();
    $stmt2 = $conn->prepare("SELECT num_almacen,localidad FROM almacen");
    $stmt2->execute();
    $stmt2->setFetchMode(PDO::FETCH_ASSOC);
    $almacenes=$stmt2->fetchAll();
}
catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
if(!isset($_POST) || empty($_POST))
{
    echo '<form action="" method="post">';
    

?>
<div>
	<label for="productos">Producto:</label>
	<select name="productos">
		<?php foreach($productos as $producto) : ?>
			<option value="<?php echo $producto['id_producto']; ?>"> <?php echo $producto["nombre"]; ?> </option>
		<?php endforeach; ?>
	</select>
	</div>
	</BR>
<div>
    <label for="producto">Número de producto:</label>
     <input type="number" name="cantidad">
</div>
<br>
<br>
<?php
echo '<div><input type="submit" value="Introducir producto"></div>
	</form>';
}
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $productoNum=test_input($_POST['producto']);
    $almacen=test_input($_POST['almacenes']);
    $producto=test_input($_POST['productos']);
    
    $stmt3=$conn->prepare("INSERT INTO almacena(num_almacen,id_producto,cantidad) VALUES (:num_alm,:id_prod,:cantidad)");
    $stmt3->bindParam(':id_prod', $id_prod);
    $stmt3->bindParam(':num_alm', $num_alm);
    $stmt3->bindParam(':cantidad', $cantidad);
    $id_prod=$producto;
    $num_alm=$almacen;
    $cantidad=$productoNum;
    $stmt3->execute();
    echo "Productos enviados";
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