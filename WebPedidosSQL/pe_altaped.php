<?php 
include 'funciones_bdd.php';
include 'signatureUtils/signature.php';
try {
    //Conexión con la base de datos
    $conn = conexion();
    //Selecciono los productos
    $productos=seleccionarProductos($conn);
    $almacenes=cantidadProductos($conn);
}
catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
    echo "Código de error: " . $e->getCode() . "<br>";
}
//Datos de configuración
$version = "HMAC_SHA512_V2";
$kc = 'sq7HjrUOBfKmC576ILgskD5srU870gJ7'; //Clave recuperada de CANALES

// Valores de entrada que no hemos cmbiado para ningun ejemplo
$fuc = "263100000";
$terminal = "72";
$moneda = "978";
$transactionType = "0";
$signature = "";
$order = str_pad(time(), 12, "0", STR_PAD_LEFT);

$currentUrl = Utils::getCurrentUrl();
$pos = strpos($currentUrl, basename(__FILE__));
$urlOKKO = substr($currentUrl, 0, $pos) . "pruebaOk.php";
$url = ""; // URL para recibir notificaciones del pago

if (isset($_COOKIE['cesta'])) {
    $cestas=unserialize($_COOKIE['cesta']);
    $sumTotal = 0;

    foreach ($cestas as $prod => $cant) {
        $precio = precioProd($conn, $prod);
        $sumTotal += (int)$precio * (int)$cant;
    }

    $amount = $sumTotal * 100;
    //Preparo el número de productos, ordenados por su ID
    
    $data = array(
	"DS_MERCHANT_AMOUNT" => $amount,
	"DS_MERCHANT_ORDER" => $order,
	"DS_MERCHANT_MERCHANTCODE" => $fuc,
	"DS_MERCHANT_CURRENCY" => $moneda,
	"DS_MERCHANT_TRANSACTIONTYPE" => $transactionType,
	"DS_MERCHANT_TERMINAL" => $terminal,
	"DS_MERCHANT_MERCHANTURL" => $url,
	"DS_MERCHANT_URLOK" => $urlOKKO,
	"DS_MERCHANT_URLKO" => $urlOKKO
    );
    var_dump($data);
    $params = Utils::base64_url_encode_safe(json_encode($data));
    var_dump($params);
    $signature = Signature::createMerchantSignature($kc, $params, $order);
    
} else {
    $cestas = [];
}


var_dump($cestas);

if ($_SERVER["REQUEST_METHOD"] == "POST"){

  // AGREGAR A LA CESTA DE LA COMPRA
	if (isset($_POST['agregar']) && !empty($_POST['agregar'])) {
	  
	    $producto = test_input($_POST['productos']);
        $cantidad = test_input((int)$_POST['cantidad']);
        setcookie("numPago", test_input($_POST['numPago']), time() + (86400 * 30), "/");

        // Verifica si el producto ya está en la cesta
        if (array_key_exists($producto, $cestas)) {
            // Si está, incrementa la cantidad
            $cestas[$producto] += $cantidad;
        } else {
            // Si no está, lo agrega con la cantidad seleccionada
            $cestas[$producto] = $cantidad;
        }

        // Actualiza la cookie con la nueva cesta
        setcookie("cesta", serialize($cestas), time() + (86400 * 30), "/");
	}
    //Limpiar la cesta de compra
    if (isset($_POST['limpiar']) && !empty($_POST['limpiar']))
    {
        if(isset($cestas)) setcookie("cesta", "", time() - 3600, "/");
    }
    //Comprar
    /*if (isset($_POST['comprar']) && !empty($_POST['comprar'])) {
	  
	    if (!isset($_COOKIE['cesta'])) 
		   echo "No se han elegido productos";
		else
            {
                $signature = Signature::createMerchantSignature($kc, $params, $order);
                foreach ($cestas as $cesta => $valor) 
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
                    introducirCompra($conn,$cestas);
                    unset($_COOKIE['cesta']);
                    unset($cestas);
                }
            }   
	       
	}*/


}

function seleccionarProductos($conn)
{
    $stmt = $conn->prepare("SELECT productName FROM products WHERE quantityInStock>=0");
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $productos=$stmt->fetchAll();
    return $productos;
}

function cantidadProductos($conn)
{
    $stmt2=$conn->prepare("SELECT productName,quantityInStock FROM products WHERE quantityInStock>=0");
    $stmt2->execute();
    $stmt2->setFetchMode(PDO::FETCH_KEY_PAIR);
    $almacenes=$stmt2->fetchAll();
    return $almacenes;
}

function introducirCompra($conn,$cestas)
{
    $stmt = $conn->prepare("UPDATE products SET quantityInStock=quantityInStock - :val WHERE productName LIKE :cesta");
    
    $stmt2 = $conn->prepare("INSERT INTO payments (customerNumber,checkNumber,paymentDate,amount) VALUES (:cusnum,:num,:fecha,:cantidad)");
    $fecha=date("Y-m-d");
    $stmt2->bindParam(':fecha',$fecha);
    $stmt2->bindParam(':cusnum',$_COOKIE['usuario']);
    $checkNum=test_input($_POST['numPago']);
    $stmt2->bindParam(':num',$checkNum);

    $stmt3=$conn->prepare("INSERT INTO orders (orderNumber,orderDate,requiredDate,status,customerNumber) VALUES (:numAct,:ordDate,:reqDate,'In Process',:cusnum)");
    $num=numeroCheckear($conn);
    $numAct=(int)$num['MAX(orderNumber)']+1;
    echo $numAct;
    $stmt3->bindParam(':cusnum',$_COOKIE['usuario']);
    $stmt3->bindParam(':numAct',$numAct);
    $stmt3->bindParam(':ordDate',$fecha);
    $stmt3->bindParam(':reqDate',$fecha);
    var_dump($_COOKIE['usuario'],$numAct,$fecha);
    try {
        $sumTotal=0;
        foreach ($cestas as $cesta => $value) 
        {
            $stmt->bindParam(':val',$value);
            $stmt->bindParam(':cesta',$cesta);
            $stmt->execute();
            $cantidad=precioProd($conn,$cesta);
            $sumTotal=$sumTotal+(int)$cantidad*(int)$value;
        }
        $stmt2->bindParam(':cantidad',$sumTotal);
        $stmt2->execute();
        $stmt3->execute();
        $conn->commit();
    }
    catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
        echo "Código de error: " . $e->getCode() . "<br>";
        $conn->rollBack();
    }
    
}

function numeroCheckear($conn)
{
    $stmt=$conn->prepare("SELECT MAX(orderNumber) FROM orders");
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $num=$stmt->fetch();
    return $num;
}

function precioProd($conn,$prod)
{
    $stmt=$conn->prepare("SELECT buyPrice FROM products WHERE productName LIKE :prod");
    $stmt->bindParam(':prod',$prod);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $num=$stmt->fetch();
    return $num;
}

/*function checkNumber()
{   
    $check=true;
    $pago=test_input($_POST['numPago']);
    $letras=substr($pago,0,2);
    $numeros=(int)substr($pago,2);
    if(ctype_alpha($letras))
        {
            if(is_numeric($numeros))
                {
                    $check=true;
                }
                else echo "Formato no válido";
        } else echo "Formato no válido";
    
    return $check;
}*/
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
			<option> <?php echo $producto["productName"]; ?> </option>
		<?php endforeach; ?>
	</select>
    <label for="cantidad">Cantidad:</label>
			<input type="number" name="cantidad">
    <label for="numPago">Cantidad:</label>
			<input type="text" name="numPago">
</div>
</BR>
<div>
<input type="submit" value="Agregar a la Cesta" name="agregar">
<input type="submit" value="Limpiar la Cesta" name="limpiar">
</form>
<?php if (isset($params)): ?>
<form name="frm" action="https://sis-t.redsys.es:25443/sis/realizarPago" method="POST" target="_blank">
			Ds_Merchant_SignatureVersion <input type="text" name="Ds_SignatureVersion" value="<?php echo $version; ?>"/></br>
			Ds_Merchant_MerchantParameters <input type="text" name="Ds_MerchantParameters" value="<?php echo $params; ?>"/></br>
			Ds_Merchant_Signature <input type="text" name="Ds_Signature" value="<?php echo $signature; ?>"/></br>
			<input type="submit" value="Enviar" >
</form>

<?php endif; ?>

</div>
<?php
    
    echo '<form method="post" action="logout.php">
    <input type="submit" value="Cerrar sesión">
    </form>';
?>
</FORM>

</BODY>
</HTML>