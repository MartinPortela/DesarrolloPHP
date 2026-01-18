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
	<label for="fechIn">Fecha de inicio:</label>
	<select name="fechIn">
		<?php foreach($productos as $producto) : ?>
			<option> <?php echo $producto["orderDate"]; ?> </option>
		<?php endforeach; ?>
	</select>
    <label for="fechEnd">Fecha de final:</label>
	<select name="fechEnd">
		<?php foreach($productos as $producto) : ?>
			<option> <?php echo $producto["orderDate"]; ?> </option>
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
    $fechIn=(isset($_POST['fechIn'])) ? test_input($_POST['fechIn']) : 0;
    $fechEnd=(isset($_POST['fechEnd'])) ? test_input($_POST['fechEnd']) : 0;
    $cant=checkStock($conn,$fechIn,$fechEnd);
    foreach ($cant as $key) 
    {
        echo "Fecha: ".$key['paymentDate'];
        echo "<br>";
        echo "Cantidad: ".$key['amount'];
        echo "<br> <br>";
    }
}

function cantidadProductos($conn)
{
    $stmt2=$conn->prepare("SELECT orderDate FROM orders");
    $stmt2->execute();
    $stmt2->setFetchMode(PDO::FETCH_ASSOC);
    $almacenes=$stmt2->fetchAll();
    for ($i=0; $i < count($almacenes); $i++) 
    {
        if(!isset($check))$check=$almacenes[$i]['orderDate'];
        else
            {
                if($check===$almacenes[$i]['orderDate'])
                    {
                        array_splice($almacenes,$i,1);
                        $i--;
                    }
                else
                    {
                        $check=$almacenes[$i]['orderDate'];
                    }
            }
    }
    return $almacenes;
}

function checkStock($conn,$fechIn,$fechEnd)
{
    if($fechIn!=0 && $fechEnd!=0)
    {
        $stmt2=$conn->prepare("SELECT paymentDate,amount FROM payments
        WHERE customerNumber=:cusNum AND (paymentDate between :fechIn and :fechEnd) ");
        $stmt2->bindParam(":fechIn",$fechIn);
        $stmt2->bindParam(":fechEnd",$fechEnd);
    }
    else {
        $stmt2=$conn->prepare("SELECT paymentDate,amount FROM payments WHERE customerNumber=:cusNum");
    }
    $cusnum=$_COOKIE['usuario'];
    $stmt2->bindParam(":cusNum",$cusnum);
    $stmt2->execute();
    $stmt2->setFetchMode(PDO::FETCH_ASSOC);
    $almacenes=$stmt2->fetchAll();
    return $almacenes;
}
?>
</FORM>

</BODY>