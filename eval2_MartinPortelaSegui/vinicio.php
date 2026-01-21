<html>
   <?php 
	if(!isset($_COOKIE['usuario'])) header('Location: index.php');
   ?>
 <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
     <title>RESERVAS VUELOS</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
 </head>
   
 <body>
   
    <div class="container ">
        <!--Aplicacion-->
		<div class="card border-success mb-3" style="max-width: 30rem;">
		<div class="card-header">Menú Usuario </div>
		<div class="card-body">

		<B>Email Cliente:</B><?php echo $_COOKIE['usuario'];?>   <BR>
		<B>Nombre Cliente:</B><?php echo $_COOKIE['password'];?>    <BR>
		<B>Fecha:</B><?php echo date("Y-m-d");?>    <BR><BR>
	  
		<!--Formulario con enlaces -->
		<div>
			<form id="" name="" action="" method="post" class="card-body">
			<input type="submit" value="Reservar Vuelos" name="reservar" class="btn btn-warning disabled">
			<input type="submit" value="Consultar Reserva" name="consultar" class="btn btn-warning disabled">
			<input type="submit" value="Salir" name="salir" class="btn btn-warning disabled">
			</form>
		</div>	
		<?php 
			if (isset($_POST['reservar']) && !empty($_POST['reservar']))
				{
					header('Location: vreservas.php');
				}
				elseif (isset($_POST['consultar']) && !empty($_POST['consultar'])) {
					header('Location: vconsultas.php');
				}
		?>
		
		
		  
	</div>  
	  
	  
     
   </body>
   
</html>


