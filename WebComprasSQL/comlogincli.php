<!DOCTYPE HTML>  
<HTML>
<HEAD> <TITLE>Login cliente</TITLE>
<BODY>
<h1>Login</h1>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">


Inserte un nombre
<input type='text' name='nombre' value='' size=9><br><br>
Inserte un apellido
<input type='text' name='apellido' value='' size=9><br><br>
<input type="submit" value="enviar">
<input type="reset" value="borrar">
<?php
include 'funciones_bdd.php';
session_start();
$cookie_name = "usuario";
$cookie_name2 = "clave";
$secretpassword = $_COOKIE[$cookie_name2];;
$secretusername = $_COOKIE[$cookie_name];


   $error = null;
   if (!empty($_POST)) {
       $username = empty($_POST['nombre']) ? null : $_POST['nombre'];
       $password = empty($_POST['apellido']) ? null : $_POST['apellido'];

       if ($username == $secretusername && $password == $secretpassword) {
           $_SESSION['authenticated'] = true;
           // Redirect to your secure location
           header('Location: comaltapro.php');
           
           return;
       } else {
           $error = 'Incorrect username or password';
       }
   }
   // Create a login form or something
   echo $error;



?>
</FORM>

</BODY>
</HTML>