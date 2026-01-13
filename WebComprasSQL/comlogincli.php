<!DOCTYPE HTML>  
<HTML>
<HEAD> <TITLE>Login cliente</TITLE>
<BODY>
<h1>Login</h1>
<form method="post" action="<?php session_start(); echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">


Inserte un nombre
<input type='text' name='nombre' value='' size=9><br><br>
Inserte la clave
<input type='text' name='apellido' value='' size=9><br><br>
<input type="submit" value="enviar">
<input type="reset" value="borrar">
<?php
include 'funciones_bdd.php';
$cookie_name = "usuario";
$cookie_name2 = "clave";
$error = null;
   if (!empty($_POST)) {
       $username = empty($_POST['nombre']) ? null : test_input($_POST['nombre']);
       $password = empty($_POST['apellido']) ? null : test_input($_POST['apellido']);

    try {
    $conn=conexion();
    $stmt=prepararSelect($conn,$username);
    $secretusername = $username;
    $secretpassword = $stmt['clave'];
    $nif=$stmt['nif'];
    if ($username == $secretusername && $password == $secretpassword) {
           $_SESSION['authenticated'] = true;
           $_SESSION['nif'] = $nif;
           header('Location: defecto.php');
           return;
       } else {
           $error = 'Usuario o contraseña incorrectos';
       }
    } 
    catch (PDOException $e) {
        echo "Error: " . $e->getMessage();

        echo "Código de error: " . $e->getCode() . "<br>";

        $conn->rollBack();
    } 
       
   }

   function prepararSelect($conn,$username)
   {
    $stmt = $conn->prepare("SELECT nombre,clave,nif FROM cliente WHERE nombre= :nombre");
    $stmt->bindParam(':nombre', $username);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
	$resultado=$stmt->fetch();
    return $resultado;
   }


?>
</FORM>

</BODY>
</HTML>