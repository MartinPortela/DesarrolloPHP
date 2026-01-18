<?php 
    include 'funciones_bdd.php';
    if(!isset($_COOKIE['usuario'])) header('Location: pe_login.php');
    $conn=conexion();
    $productos=cantidadProductos($conn);
?>

<!DOCTYPE HTML>
<HTML>
<HEAD> <TITLE>Comprocli</TITLE>
</HEAD>
<BODY>
<h1>Comprar productos</h1>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<div>
	<label for="productos">Producto:</label>
	<select name="productos">
		<?php foreach($productos as $producto) : ?>
			<option> <?php echo $producto["productLine"]; ?> </option>
		<?php endforeach; ?>
	</select>
</div>
</BR>
<div>
<input type="submit" value="Revisar stock">
</div>
<?php
    echo '</form>';
    echo '<form method="post" action="logout.php">
    <input type="submit" value="Cerrar sesión">
    </form>';

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $nombre=$_POST['productos'];
    $cant=checkStock($conn,$nombre);
    echo "Hay ".$cant['cantidad']." unidades de la línea del producto en stock";
}

function cantidadProductos($conn)
{
    $stmt2=$conn->prepare("SELECT productLine FROM products");
    $stmt2->execute();
    $stmt2->setFetchMode(PDO::FETCH_ASSOC);
    $almacenes=$stmt2->fetchAll();
    for ($i=0; $i < count($almacenes); $i++) 
    {
        if(!isset($check))$check=$almacenes[$i]['productLine'];
        else
            {
                if($check===$almacenes[$i]['productLine'])
                    {
                        array_splice($almacenes,$i,1);
                        $i--;
                    }
                else
                    {
                        $check=$almacenes[$i]['productLine'];
                    }
            }
    }
    return $almacenes;
}

function checkStock($conn,$nombre)
{
    $stmt2=$conn->prepare("SELECT SUM(quantityInStock) AS cantidad FROM products WHERE productLine LIKE :prodName GROUP BY productLine");
    $stmt2->bindParam(":prodName",$nombre);
    $stmt2->execute();
    $stmt2->setFetchMode(PDO::FETCH_ASSOC);
    $almacenes=$stmt2->fetch();
    return $almacenes;
}
?>
</FORM>

</BODY>