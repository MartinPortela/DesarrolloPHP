<?php 
    include "db/vconfig.php";
?>

<html>
   
 <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login Page - PORTAL RESERVAS</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
 </head>
      
<body>
    

    <div class="container ">
        <!--Aplicacion-->
		<div class="card border-success mb-3" style="max-width: 30rem;">
		<div class="card-header">Acceso Reserva Vuelos</div>
		<div class="card-body">
		
		<form id="" name="" action="" method="post" class="card-body">
		
		<div class="form-group">
			Usuario <input type="text" name="usuario" placeholder="usuario" class="form-control">
        </div>
		<div class="form-group">
			Password <input type="password" name="password" placeholder="password" class="form-control">
        </div>				
        
		<input type="submit" name="submit" value="Login" class="btn btn-warning disabled">
        <?php
        if (!empty($_POST)) {
        $usuario = empty($_POST['usuario']) ? null : test_input($_POST['usuario']);
        $password = empty($_POST['password']) ? null : test_input($_POST['password']);
            try {
                $conn=conexion();
                $usuarios=seleccionarUsuario($conn);
                if(!array_key_exists($usuario,$usuarios)) echo "Usuario no existe";
                else
                    {
                        if($usuarios[$usuario]!=$password) echo "Contraseña incorrecta";
                        else
                            {
                                setcookie("usuario", $usuario, time() + (86400 * 30), "/");
                                setcookie("password", $password, time() + (86400 * 30), "/");
                                header('Location: vinicio.php');
                            }
                    }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();

                echo "Código de error: " . $e->getCode() . "<br>";

                $conn->rollBack();
            } 
        }
            
            function seleccionarUsuario($conn)
            {
                $stmt = $conn->prepare("SELECT email,dni FROM clientes");
                $stmt->execute();
                $stmt->setFetchMode(PDO::FETCH_KEY_PAIR);
                $resultado=$stmt->fetchAll();
                foreach ($resultado as $usuario) {
                    $clave=array_keys($resultado,$usuario)[0];
                    $usuario=substr($usuario,0,4);
                    $resultado[$clave]=$usuario;

                }
                return $resultado;
            }
        ?>
        </form>
		
	    </div>
    </div>
    </div>
    </div>

</body>
</html>