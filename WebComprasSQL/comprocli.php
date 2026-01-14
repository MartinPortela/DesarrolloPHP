<!DOCTYPE HTML>
<?php 
session_start();
include 'funciones_bdd.php';
?>  
<HTML>
<HEAD> <TITLE>Comprocli</TITLE>
</HEAD>
<BODY>
<h1>Comprar productos</h1>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<?php
try {
    //Conexión con la base de datos
    $conn = conexion();
    //Selecciono los productos
    $productos=seleccionarProductos($conn);
    //Preparo el número de productos, ordenados por su ID
    $almacenes=seleccionarCantidad($conn);
}
catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
    echo "Código de error: " . $e->getCode() . "<br>";
}
?>
<div>
	<label for="productos">Producto:</label>
	<select name="productos">
		<?php foreach($productos as $producto) : ?>
			<option value="<?php echo $producto['id_producto']; ?>"> <?php echo $producto["nombre"]; ?> </option>
		<?php endforeach; ?>
	</select>
    <label for="cantidad1">Cantidad:</label>
			<input type="number" name="cantidad">
</div>
</BR>
<div>
<input type="submit" value="Comprar Productos" name="comprar">
<input type="submit" value="Agregar a la Cesta" name="agregar">
<input type="submit" value="Limpiar la Cesta" name="limpiar">
</div>
<?php
echo '</form>';
echo '<form method="post" action="logout.php">
    <input type="submit" value="Cerrar sesión">
</form>';
var_dump($almacenes);
if ($_SERVER["REQUEST_METHOD"] == "POST"){

  // AGREGAR A LA CESTA DE LA COMPRA
	if (isset($_POST['agregar']) && !empty($_POST['agregar'])) {
	  
	    if (!isset($_SESSION['$cesta'])) 
		   $_SESSION['$cesta']=array($_POST['productos'] => $_POST['cantidad']);
		   
	    else if(!isset($_SESSION['$cesta'][$_POST['productos']])) $_SESSION['$cesta'][$_POST['productos']]=$_POST['cantidad'];
            else $_SESSION['$cesta'][$_POST['productos']]+=$_POST['cantidad'];
	}
    //Limpiar la cesta de compra
    if (isset($_POST['limpiar']) && !empty($_POST['limpiar']))
    {
        if(isset($_SESSION['$cesta'])) unset($_SESSION['$cesta']);
    }
    //Comprar
    if (isset($_POST['comprar']) && !empty($_POST['comprar'])) {
	  
	    if (!isset($_SESSION['$cesta'])) 
		   echo "No se han elegido productos";
		else
            {
                foreach ($_SESSION['$cesta'] as $cesta => $valor) 
                {
                   if($valor>$almacenes[$cesta])
                    {
                        echo "No hay productos suficientes";
                        $comp=true;
                    }
                }

                if(!isset($comp))
                {
                    echo "Compra finalizada con éxito";
                    introducirCompra($conn);
                    unset($_SESSION['$cesta']);
                }
            }   
	       
	}
	
if(isset($_SESSION['$cesta'])) var_dump($_SESSION['$cesta']);


}

function seleccionarProductos($conn)
{
    $stmt = $conn->prepare("SELECT id_producto,nombre FROM producto");
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $productos=$stmt->fetchAll();
    return $productos;
}

function seleccionarCantidad($conn)
{
    $stmt2 = $conn->prepare("SELECT id_producto, SUM(cantidad) AS cantidad_total FROM almacena GROUP BY id_producto");
    $stmt2->execute();
    $stmt2->setFetchMode(PDO::FETCH_KEY_PAIR);
    $almacenes=$stmt2->fetchAll();
    return $almacenes;
}

function introducirCompra($conn)
{
    $nif=$_SESSION['nif'];
    $stmt2 = $conn->prepare("INSERT INTO compra (nif,id_producto,fecha_compra,unidades) VALUES (:nif,:id_producto,:fecha,:unidades)");
    $fecha=date("Y/m/d");
    $stmt2->bindParam(':fecha',$fecha);
    $stmt2->bindParam(':nif',$nif);
    try {
    foreach ($_SESSION['$cesta'] as $cesta => $valor) 
    {
        $stmt2->bindParam(':id_producto',$cesta);
        $stmt2->bindParam(':unidades',$valor);
        $stmt2->execute();
    }
    $conn->commit();
    }
    catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
        echo "Código de error: " . $e->getCode() . "<br>";
        $conn->rollBack();
    }
    
}
?>
</FORM>

</BODY>
</HTML>