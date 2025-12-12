<!DOCTYPE HTML>  
<HTML>
<HEAD> <TITLE>Comaltapro</TITLE>
</HEAD>
<BODY>
<h1>Dar de alta productos</h1>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<?php
session_start();
echo $_SESSION['username'];
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
    $stmt = $conn->prepare("SELECT id_categoria,nombre FROM categoria");
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $categorias=$stmt->fetchAll();
}
catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
if(!isset($_POST) || empty($_POST))
{
    echo '<form action="" method="post">';
    

?>
<div>
	<label for="categorias">Categorías:</label>
	<select name="categorias">
		<?php foreach($categorias as $categoria) : ?>
			<option value="<?php echo $categoria['id_categoria']; ?>"> <?php echo $categoria["nombre"]; ?> </option>
		<?php endforeach; ?>
	</select>
	</div>
	</BR>
<div>
    <label for="producto">Producto:</label>
     <input type="text" name="producto" size="25">
</div>
<br>
<div>
    <label for="precio">Precio:</label>
     <input type="number" name="precio" size="20" step=".01">
</div>
<br><br>
<?php
echo '<div><input type="submit" value="Introducir producto"></div>
	</form>';
}
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $producto=test_input($_POST['producto']);
    $precio=test_input($_POST['precio']);
    $categoria=test_input($_POST['categorias']);
    
    //Select para recoger el id de los productos
    $stmt2 = $conn->prepare("SELECT MAX(id_producto) AS prod FROM producto");
    //Select para insertar el nuevo producto
    $stmt3 = $conn->prepare("INSERT INTO producto (id_producto,nombre,precio,id_categoria) VALUES (:id_prod,:nombre,:precio, :categoria)");
    //Select para recoger el id de la categoría escogida
    $stmt3->bindParam(':id_prod', $id_prod);
    $stmt3->bindParam(':nombre', $nombre);
    $stmt3->bindParam(':precio', $precio);
    $stmt3->bindParam(':categoria', $categoria);
    $stmt2->execute();

    // set the resulting array to associative
     $stmt2->setFetchMode(PDO::FETCH_ASSOC);
	 $resultado=$stmt2->fetchAll();
     if($resultado!=null)
     {
     $max=$resultado[0]["prod"];
     $max=substr($max,-3);
     $max++;
     $max=str_pad($max,3,0,STR_PAD_LEFT);
     $id_prod='P'.$max;
     }else
     {
        $id_prod='P001';
     }
     $nombre=$producto;
     $stmt3->execute();
     echo "Producto insertado";
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
