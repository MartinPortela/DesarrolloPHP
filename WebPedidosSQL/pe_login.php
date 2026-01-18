<!DOCTYPE HTML>  
<HTML>
<HEAD> <TITLE>Login cliente</TITLE>
<BODY>
<h1>Login</h1>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">


Inserte el usuario
<input type='number' name='nombre' value='' size=3><br><br>
Inserte el apellido
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
    if(!array_key_exists($cookie_name,$stmt)) echo "Usuario no existe";
    else
        {
            if($stmt[$cookie_name]!=$cookie_name2) echo "Contraseña incorrecta";
            else
                {
                    setcookie("usuario", $cookie_name, time() + (86400 * 30), "/");
                    setcookie("password", $cookie_name2, time() + (86400 * 30), "/");
                    header('Location: pe_inicio.php');
                }
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
    $resultado=$stmt->fetchAll();
    return $resultado;
   }


?>
</FORM>

</BODY>
</HTML>