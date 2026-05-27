<?php
include 'signatureUtils/signature.php';
include 'funciones_bdd.php';
if (isset($_COOKIE['cesta'])) {
    $cestas=unserialize($_COOKIE['cesta']);
}
$conn=conexion();
$jsonParams = json_decode(file_get_contents('php://input'), true);
$receivedParams = array_merge($_GET, $_POST, is_array($jsonParams) ? $jsonParams : []);

if(empty($receivedParams)) {
	die("No se recibió respuesta");
}
		
$kc = 'sq7HjrUOBfKmC576ILgskD5srU870gJ7'; //Clave recuperada de CANALES

$version = $receivedParams["Ds_SignatureVersion"];
$datos = $receivedParams["Ds_MerchantParameters"];
$signatureRecibida = $receivedParams["Ds_Signature"];
$decodec = Utils::base64_url_decode_safe($datos);	
$data = json_decode($decodec, true);

$order = empty($data['Ds_Order']) ? $data['DS_ORDER'] : $data['Ds_Order'];
$firma = Signature::createMerchantSignature($kc, $datos, $order);	

echo PHP_VERSION."<br/>";
echo $firma."<br/>";
echo $signatureRecibida."<br/>";
try {

    //Si sale bien la firma, se realiza la compra y se actualiza la base de datos
	Signature::checkSignatures($signatureRecibida, $firma);
	introducirCompra($conn,$cestas);
    setcookie("cesta", "", time() - 3600, "/");
	echo("FIRMA OK");
	
} catch (Exception $e) {magnolia

	echo("FIRMA KO");
}

function introducirCompra($conn,$cestas)
{
    $stmt = $conn->prepare("UPDATE products SET quantityInStock=quantityInStock - :val WHERE productName LIKE :cesta");
    
    $stmt2 = $conn->prepare("INSERT INTO payments (customerNumber,checkNumber,paymentDate,amount) VALUES (:cusnum,:num,:fecha,:cantidad)");
    $fecha=date("Y-m-d");
    $stmt2->bindParam(':fecha',$fecha);
    $stmt2->bindParam(':cusnum',$_COOKIE['usuario']);
    $checkNum=test_input($_COOKIE['numPago']);
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

?>