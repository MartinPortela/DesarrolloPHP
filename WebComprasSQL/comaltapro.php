<!DOCTYPE HTML>  
<HTML>
<HEAD> <TITLE>Comaltapro</TITLE>
</HEAD>
<BODY>
<h1>Dar de alta categorías</h1>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

Inserte una nueva categoría:
<input type='text' name='categoria' value='' size=25><br><br><br>
<input type="submit" value="enviar">
<input type="reset" value="borrar">
<?php
$convertir="";
$servername = "localhost";
$username = "root";
$password = "rootroot";
$dbname = "comprasweb";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT nombre FROM categoria");
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $resultado=$stmt->fetchAll();
}
catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
if(!isset($_POST) || empty($_POST))
{
    echo '<form action="" method="post">';
    $categorias=obtenerCategorias($resultado);

?>
<div>
	<label for="categorias">Departamentos:</label>
	<select name="categorias">
		<?php foreach($categorias as $categoria) : ?>
			<option> <?php echo $categoria ?> </option>
		<?php endforeach; ?>
	</select>
	</div>
	</BR>
<?php
echo '<div><input type="submit" value="Introducir producto"></div>
	</form>';
}
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $categoria=test_input($_POST['categoria']);
    

}
 function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function obtenerCategorias($cat)
{
    $categorias=array();
    foreach ($cat as $key => $value) {
        $categorias[]=$value["nombre"];
    }
    return $categorias;
}
?>
</FORM>

</BODY>
</HTML>
