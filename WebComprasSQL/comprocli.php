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
if ($_SERVER["REQUEST_METHOD"] == "POST"){

  // AGREGAR A LA CESTA DE LA COMPRA
	if (isset($_POST['agregar']) && !empty($_POST['agregar'])) {
	  
	    if (!isset($_SESSION['$cesta'])) 
		   $_SESSION['$cesta']=array(array($_POST['productos'],$_POST['cantidad']));
		   
	    else
	       array_push($_SESSION['$cesta'],array($_POST['productos'],$_POST['cantidad']));
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
                foreach ($_SESSION['$cesta'] as $cesta) 
                {
                   if($cesta[1]>$almacenes[$cesta[0]])
                    {
                        echo "No hay productos suficientes";
                        $comp=true;
                    }
                }

                if(!isset($comp))
                {
                    echo "Compra finalizada con éxito";
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
    $stmt2 = $conn->prepare("SELECT id_producto,cantidad FROM almacena");
    $stmt2->execute();
    $stmt2->setFetchMode(PDO::FETCH_KEY_PAIR);
    $almacenes=$stmt2->fetchAll();
    return $almacenes;
}
?>
</FORM>

</BODY>
</HTML>