<!DOCTYPE HTML>  
<HTML>
<HEAD> <TITLE>Login cliente</TITLE>
<BODY>
<h1>Login</h1>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">


Inserte un nombre
<input type='text' name='nombre' value='' size=9><br><br>
Inserte la clave
<input type='text' name='apellido' value='' size=9><br><br>
<input type="submit" value="enviar">
<input type="reset" value="borrar">
<?php
include 'funciones_bdd.php';
$error = null;
   if (!empty($_POST)) {
        $cookie_name = empty($_POST['nombre']) ? null : test_input($_POST['nombre']);
        $cookie_name2 = empty($_POST['apellido']) ? null : test_input($_POST['apellido']);

    try {
    $conn=conexion();
    $stmt=prepararSelect($conn);
    $secretusername = $username;
    $secretpassword = $stmt['clave'];
    $nif=$stmt['nif'];
    if ($username == $secretusername && $password == $secretpassword) {
            session_start();
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

   function prepararSelect($conn)
   {
    $stmt = $conn->prepare("SELECT CustomerNumber,ContactLastName FROM Customers");
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_KEY_PAIR);
    return $stmt;
   }


?>
</FORM>

</BODY>
</HTML>