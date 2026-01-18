<?php 
include 'funciones_bdd.php';
if ($_SERVER["REQUEST_METHOD"] == "POST"){
try {
    //Conexión con la base de datos
    $conn = conexion();
    //Selecciono los productos
    $numCus=test_input($_POST['numCus']);
    seleccionarProductos($conn,$numCus);
}
catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
    echo "Código de error: " . $e->getCode() . "<br>";
}
}

function seleccionarProductos($conn,$cusNum)
{
    $stmt = $conn->prepare("SELECT 
    o.orderNumber,
    o.orderDate,
    o.status,
    od.orderLineNumber,
    p.productName,
    od.quantityOrdered,
    od.priceEach
    FROM orderdetails od
    JOIN orders o ON od.orderNumber = o.orderNumber
    JOIN products p ON od.productCode=p.productCode
    WHERE o.customerNumber = :cusNum
    ORDER BY o.orderNumber, od.orderLineNumber");
    $stmt->bindParam(":cusNum",$cusNum);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $productos=$stmt->fetchAll();
    foreach ($productos as $key => $value) 
    {
        var_dump($value);
        echo "<br>";
    }
}

?>

<!DOCTYPE HTML>
<HTML>
<HEAD> <TITLE>Consped</TITLE>
</HEAD>
<BODY>
<h1>Consped</h1>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<div>
    <label for="numCus">Número de cliente:</label>
			<input type="text" name="numCus">
</div>
</BR>
<div>
<input type="submit" value="Checkear">
</div>
<?php
    echo '</form>';
    echo '<form method="post" action="logout.php">
    <input type="submit" value="Cerrar sesión">
    </form>';
?>
</FORM>

</BODY>
</HTML>