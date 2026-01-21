<html>
   <?php 
    include "db/vconfig.php";
	if(!isset($_COOKIE['usuario'])) header('Location: index.php');
	//Para el carrito utilizaré sesiones, pues yo creo que es lo mejor
	session_start();
	?>
 <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
     <title>RESERVAS VUELOS</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
 </head>
   <?php
		try {
			//Conexión con la base de datos
			$conn = conexion();
			//Selecciono los vuelos
			$productos=seleccionarProductos($conn);
			//Preparo el número de asientos disponibles ordenados por ID
			$almacenes=seleccionarCantidad($conn);
		}
		catch(PDOException $e) {
			echo "Error: " . $e->getMessage();
			echo "Código de error: " . $e->getCode() . "<br>";
		}
	?>
 <body>
   

    <div class="container ">
        <!--Aplicacion-->
		<div class="card border-success mb-3" style="max-width: 30rem;">
		<div class="card-header">Reservar Vuelos</div>
		<div class="card-body">
	  	  

	<!-- INICIO DEL FORMULARIO -->
	<form action="" method="post">
	
		<B>Email Cliente:</B><?php echo $_COOKIE['usuario'];?>   <BR>
		<B>Nombre Cliente:</B><?php echo $_COOKIE['password'];?>    <BR>
		<B>Fecha:</B><?php echo date("Y-m-d");?>    <BR><BR>
		
		<B>Vuelos</B><select name="vuelos" class="form-control">
				<?php foreach($productos as $producto) : //El valor será el del id_vuelo pues es la clave primaria?>
				<option value="<?php echo $producto['id_vuelo']; ?>"> <?php echo $producto["origen"]." - ".$producto["destino"]." 
				Salida: ". $producto["fechahorasalida"]." Llegada: ".$producto["fechahorallegada"]; ?> </option>
				<?php endforeach; ?>
			</select>	
		<BR> 
		<B>Número Asientos</B><input type="number" name="asientos" size="3" min="1" max="100" value="1" class="form-control">
		<BR><BR><BR><BR><BR>
		<div>
			<input type="submit" value="Agregar a Cesta" name="agregar" class="btn btn-warning disabled">
			<input type="submit" value="Comprar" name="comprar" class="btn btn-warning disabled">
			<input type="submit" value="Vaciar Cesta" name="vaciar" class="btn btn-warning disabled">
			<input type="submit" value="Volver" name="volver" class="btn btn-warning disabled">
		</div>		
	</form>
	<?php
	if ($_SERVER["REQUEST_METHOD"] == "POST"){

	// AGREGAR A LA CESTA DE LA COMPRA
		if (isset($_POST['agregar']) && !empty($_POST['agregar'])) {
		
			if (!isset($_SESSION['cesta'])) 
			$_SESSION['cesta']=array($_POST['vuelos'] => $_POST['asientos']);
			
			else if(!isset($_SESSION['cesta'][$_POST['vuelos']])) $_SESSION['cesta'][$_POST['vuelos']]=$_POST['asientos'];
				else $_SESSION['cesta'][$_POST['vuelos']]+=$_POST['asientos'];
		}
		//Limpiar la cesta de compra
		if (isset($_POST['vaciar']) && !empty($_POST['vaciar']))
		{
			if(isset($_SESSION['cesta'])) unset($_SESSION['cesta']);
		}
		//Comprar
		if (isset($_POST['comprar']) && !empty($_POST['comprar'])) {
		
			if (!isset($_SESSION['cesta'])) 
			echo "No se han elegido vuelos";
			else
				{
					foreach ($_SESSION['cesta'] as $cesta => $valor) 
					{
					if($valor>$almacenes[$cesta])
						{
							echo "No hay asientos suficientes";
							$comp=true;
						}
					}

					if(!isset($comp))
					{
						echo "Reserva finalizada con éxito";
						introducirCompra($conn);
						unset($_SESSION['cesta']);
					}
				}   
			
		}
		
	if(isset($_SESSION['cesta'])) var_dump($_SESSION['cesta']);


	}

	//Función para sacar los datos de los vuelos relevantes
	function seleccionarProductos($conn)
	{
		$stmt = $conn->prepare("SELECT id_vuelo,origen,destino,fechahorasalida,fechahorallegada FROM vuelos WHERE asientos_disponibles>=0");
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$productos=$stmt->fetchAll();
		return $productos;
	}

	function seleccionarCantidad($conn)
	{
		$stmt = $conn->prepare("SELECT id_vuelo,asientos_disponibles FROM vuelos");
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_KEY_PAIR);
		$almacenes=$stmt->fetchAll();
		return $almacenes;
	}

	//Función de compra, donde se harán todos los cambios necesarios
	function introducirCompra($conn)
	{
		//Saco el DNI del usuario
		$dni=sacarDNI($conn);
		//De esta sentencia SQL sacaré el último ID de reserva
		$stmt = $conn->prepare("SELECT MAX(id_reserva) as max FROM reservas");
		$stmt->execute();
     	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	 	$resultado=$stmt->fetchAll();
		$max=$resultado[0]["max"];
		//Corto la string para quedarme solo con los números, luego lo sumo y añado los 0 que hagan falta
		$max=substr($max,-4);
		$max++;
		$max=str_pad($max,4,0,STR_PAD_LEFT);
		//Por último, hago una variable con la letra
		$id_reserva='R'.$max;
		//Preparo la sentencia de insertar los datos en reservas
		$stmt2 = $conn->prepare("INSERT INTO reservas (id_reserva,id_vuelo,dni_cliente,fecha_reserva,num_asientos,preciototal) 
		VALUES (:id_reserva,:id_vuelo,:dni,:fecha,:num_asientos,:preciototal)");
		//Preparo los datos para actualizar la tabla de vuelos, restando los asientos disponibles a los que el usuario ha comprado
		$stmt3 = $conn->prepare("UPDATE vuelos SET asientos_disponibles=asientos_disponibles - :asientos WHERE id_vuelo LIKE :vuelo");
		$fecha=date("Y/m/d");
		$stmt2->bindParam(':id_reserva',$id_reserva);
		$stmt2->bindParam(':dni',$dni);
		$stmt2->bindParam(':fecha',$fecha);
		try {
		//Foreach para hacer el insert y el update de cada vuelo
		foreach ($_SESSION['cesta'] as $vuelo => $asientos) 
		{
			$stmt2->bindParam(':id_vuelo',$vuelo);
			$stmt2->bindParam(':num_asientos',$asientos);
			$precio=calcularPrecio($conn,$vuelo,$asientos);
			$stmt2->bindParam(':preciototal',$precio);
			$stmt2->execute();
			$stmt3->bindParam(':asientos',$asientos);
			$stmt3->bindParam(':vuelo',$vuelo);
			$stmt3->execute();
		}
		$conn->commit();
		}
		catch(PDOException $e) {
			echo "Error: " . $e->getMessage();
			echo "Código de error: " . $e->getCode() . "<br>";
			$conn->rollBack();
		}
		
	}
	function sacarDNI($conn)
            {
                $stmt = $conn->prepare("SELECT email,dni FROM clientes");
                $stmt->execute();
                $stmt->setFetchMode(PDO::FETCH_KEY_PAIR);
                $resultado=$stmt->fetchAll();
                $dni=$resultado[$_COOKIE['usuario']];
                return $dni;
            }
	
	function calcularPrecio($conn,$vuelo,$asientos)
	{
		$stmt = $conn->prepare("SELECT id_vuelo,precio_asiento FROM vuelos");
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_KEY_PAIR);
		$precios=$stmt->fetchAll();
		$precio=$precios[$vuelo]*$asientos;
		return $precio;

	}
?>
	<!-- FIN DEL FORMULARIO -->
    <a href = "logout.php">Cerrar Sesion</a>
  </body>
   
</html>

