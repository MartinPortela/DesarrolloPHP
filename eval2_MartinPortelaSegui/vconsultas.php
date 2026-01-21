<html>
   <?php 
    include "db/vconfig.php";
	if(!isset($_COOKIE['usuario'])) header('Location: index.php');
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
			//Selecciono las reservas
			$reservas=seleccionarReserva($conn);
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
		<div class="card-header">Consultar Reservas</div>
		<div class="card-body">
	  	  

	<!-- INICIO DEL FORMULARIO -->
	<form action="" method="post">
	
		<B>Email Cliente:</B><?php echo $_COOKIE['usuario'];?>   <BR>
		<B>Nombre Cliente:</B><?php echo $_COOKIE['password'];?>    <BR>
		<B>Fecha:</B><?php echo date("Y-m-d");?>    <BR><BR>
		
		<B>Numero Reserva</B><select name="reserva" class="form-control">
				<?php foreach($reservas as $reserva) : ?>
				<option value="<?php echo $reserva['id_reserva']; ?>"> <?php echo $reserva["id_reserva"] ?> </option>
				<?php endforeach; ?>
			</select>	
		<BR><BR><BR><BR><BR><BR><BR>
		<div>
			<input type="submit" value="Consultar Reserva" name="consultar" class="btn btn-warning disabled">
			<input type="submit" value="Volver" name="volver" class="btn btn-warning disabled">
		</div>
		<?php
			if ($_SERVER["REQUEST_METHOD"] == "POST")
			{
				$id_reserva=$_POST['reserva'];
				$vuelos=sacarVuelos($conn,$id_reserva);

				//Creo la tabla con sus cabeceras
				echo '<table border="1">';
				echo "<th>Aerolínea</th>";
				echo "<th>Origen</th>";
				echo "<th>Destino</th>";
				echo "<th>Salida</th>";
				echo "<th>Llegada</th>";
				echo "<th>Asientos</th>";
				//Foreach por cada reserva, así imprimo la información de cada vuelo de forma rápida
				foreach ($vuelos as $vuelo) 
				{	
					echo "<tr>";
					echo '<td style="text-align: center; vertical-align: middle; padding: 5px;">'.$vuelo['aerolinea']."</td>";
					echo '<td style="text-align: center; vertical-align: middle; padding: 5px;">'.$vuelo['origen']."</td>";
					echo '<td style="text-align: center; vertical-align: middle; padding: 5px;">'.$vuelo['destino']."</td>";
					echo '<td style="text-align: center; vertical-align: middle; padding: 5px;">'.$vuelo['salida']."</td>";
					echo '<td style="text-align: center; vertical-align: middle; padding: 5px;">'.$vuelo['llegada']."</td>";
					echo '<td style="text-align: center; vertical-align: middle; padding: 5px;">'.$vuelo['asientos']."</td>";
					echo "</tr>";
				}
				echo "</table>";
			}

			//Función hecha para la selección de reservas
			function seleccionarReserva($conn)
			{
				//Solo se mostrarán las reservas bajo el DNI del usuario que ha hecho login
				$stmt = $conn->prepare("SELECT id_reserva FROM reservas WHERE dni_cliente LIKE :dni");
				$dni=sacarDNI($conn);
				$stmt->bindParam(":dni",$dni);
				$stmt->execute();
				$stmt->setFetchMode(PDO::FETCH_ASSOC);
				$reservas=$stmt->fetchAll();
				//Este for está hecho para que no se repitan reservas en la selección, pues solo se necesita que aparezca el id una vez
				for ($i=0; $i < count($reservas); $i++) 
					{
						if(!isset($check))$check=$reservas[$i]['id_reserva'];
						else
							{
								if($check===$reservas[$i]['id_reserva'])
									{
										array_splice($reservas,$i,1);
										$i--;
									}
								else
									{
										$check=$reservas[$i]['id_reserva'];
									}
							}
					}
				return $reservas;
			}

			//Función hecha para sacar el DNI entero del cliente, pues en las cookies solo guardó los 4 primeros números
			function sacarDNI($conn)
            {
                $stmt = $conn->prepare("SELECT email,dni FROM clientes");
                $stmt->execute();
                $stmt->setFetchMode(PDO::FETCH_KEY_PAIR);
                $resultado=$stmt->fetchAll();
                $dni=$resultado[$_COOKIE['usuario']];
                return $dni;
            }

			//Función para sacar todos los vuelos bajo una reserva
			function sacarVuelos($conn,$id)
			{
				//Con esta consulta JOIN me aseguro de sacar los datos adecuados a la reserva pedida
				$stmt = $conn->prepare("SELECT a.nombre_aerolinea AS aerolinea,v.origen AS origen,v.destino AS destino,v.fechahorasalida AS salida,v.fechahorallegada AS llegada,r.num_asientos AS asientos 
				FROM reservas r,vuelos v, aerolineas a 
				WHERE r.id_reserva LIKE :id AND r.id_vuelo=v.id_vuelo AND v.id_aerolinea=a.id_aerolinea
				ORDER BY v.fechahorasalida");
				$stmt->bindParam(":id",$id);
				$stmt->execute();
				$stmt->setFetchMode(PDO::FETCH_ASSOC);
				$vuelos=$stmt->fetchAll();
				return $vuelos;
			}
		?>		
	</form>
	
	<!-- FIN DEL FORMULARIO -->
    <a href = "logout.php">Cerrar Sesion</a>
  </body>
   
</html>

