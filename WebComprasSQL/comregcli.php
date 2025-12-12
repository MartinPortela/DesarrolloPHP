<!DOCTYPE HTML>  
<HTML>
<HEAD> <TITLE>Comregcli</TITLE>
</HEAD>
<BODY>
<h1>Dar de alta clientes</h1>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

Inserte un NIF:
<input type='text' name='NIF' value='' size=9><br><br>
Inserte un nombre
<input type='text' name='nombre' value='' size=9><br><br>
Inserte un apellido
<input type='text' name='apellido' value='' size=9><br><br>
Inserte su código postal
<input type='number' name='CP' value='' size=5><br><br>
Inserte una dirección
<input type='text' name='direccion' value='' size=9><br><br>
Inserte una ciudad
<input type='text' name='ciudad' value='' size=9><br><br><br>
<input type="submit" value="enviar">
<input type="reset" value="borrar">
<?php
include 'funciones_bdd.php';
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $NIF=test_input($_POST['NIF']);
    $nombre=test_input($_POST['nombre']);
    $apellido=test_input($_POST['apellido']);
    $CP=test_input($_POST['CP']);
    $direccion=test_input($_POST['direccion']);
    $ciudad=test_input($_POST['ciudad']);

try {
    $conn = conexion();
    $stmt=prepararInsert($conn,$NIF,$nombre,$apellido,$CP,$direccion,$ciudad);
    $stmt->execute();
    echo "Cliente registrado";
    $conn->commit();
}   
catch(PDOException $e) {
    echo "Error: " . $e->getMessage();

    echo "Código de error: " . $e->getCode() . "<br>";

    $conn->rollBack();
}
$conn = null;
}

function prepararInsert($conn,$NIF,$nombre,$apellido,$CP,$direccion,$ciudad)
{
    $clave=strrev($apellido);
    $stmt = $conn->prepare("INSERT INTO cliente (NIF,nombre,apellido,CP,direccion,ciudad,clave) VALUES (:NIF,:nombre,:apellido,:CP,:direccion,:ciudad,:clave)");
    $stmt->bindParam(':NIF', $NIF);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':apellido', $apellido);
    $stmt->bindParam(':CP', $CP);
    $stmt->bindParam(':direccion', $direccion);
    $stmt->bindParam(':ciudad', $ciudad);
    $stmt->bindParam(':clave', $clave);
    return $stmt;
}
?>
</FORM>

</BODY>
</HTML>