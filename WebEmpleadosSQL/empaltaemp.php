<!DOCTYPE HTML>  
<HTML>
<HEAD> <TITLE>Comaltapro</TITLE>
</HEAD>
<BODY>
<h1>Dar de alta emepleados</h1>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<?php
$convertir="";
$servername = "localhost";
$username = "root";
$password = "rootroot";
$dbname = "empleados";

try {
    //Conexión con la base de datos
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->beginTransaction();
    //Selecciono las categorías
    $stmt = $conn->prepare("SELECT cod_dpto,nombre_dpto FROM departamento");
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $categorias=$stmt->fetchAll();
}
catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
if(!isset($_POST) || empty($_POST))
{
    echo '<form action="" method="post">';
    

?>
<div>
	<label for="nombre">Departamentos:</label>
	<select name="nombre">
		<?php foreach($categorias as $categoria) : ?>
			<option value="<?php echo $categoria['cod_dpto']; ?>"> <?php echo $categoria["nombre_dpto"]; ?> </option>
		<?php endforeach; ?>
	</select>
	</div>
	</BR>
<div>
    <label for="nombre_emp">Nombre del empleado:</label>
     <input type="text" name="nombre_emp" size="25">
</div>
<br>
<div>
    <label for="apellidos_emp">Apellidos del empleado:</label>
     <input type="text" name="apellidos_emp" size="25">
</div>
<br>
<div>
    <label for="DNI">DNI:</label>
     <input type="text" name="DNI" size="9">
</div>
<div>
    <label for="salario">Salario del empleado:</label>
     <input type="number" name="salario" size="25" step="0.2">
</div>
<div>
    <label for="nacimiento">Fecha de nacimiento del empleado:</label>
     <input type="date" name="nacimiento" size="25">
</div>
<br>
<br><br>
<?php
echo '<div><input type="submit" value="Introducir empleado"></div>
	</form>';
}
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    try{
    $cod=test_input($_POST['nombre']);
    $nombre_emp=test_input($_POST['nombre_emp']);
    $apellidos_emp=test_input($_POST['apellidos_emp']);
    $DNI=test_input($_POST['DNI']);
    $salario=test_input($_POST['salario']);
    $nacimiento=test_input($_POST['nacimiento']);
    
    //Select para recoger el id de los productos
    $stmt2 = $conn->prepare("INSERT INTO emple_depart (DNI,cod_dpto,fecha_ini,fecha_fin) VALUES (:DNI,:cod_dpto,:fecha_ini,:fecha_fin)");
    $stmt2->bindParam(':DNI', $DNI);
    $stmt2->bindParam(':cod_dpto', $cod);
    $stmt2->bindParam(':fecha_ini', $fecha_ini);
    $stmt2->bindParam(':fecha_fin', $fecha_fin);
    $fecha_ini=date("d/m/y");
    $fecha_fin=date("d/m/y");
    //Select para insertar el nuevo producto
    $stmt3 = $conn->prepare("INSERT INTO empleado (DNI,nombre,apellidos,salario,fecha_nac) VALUES (:DNI,:nombre,:apellidos,:salario, :fecha_nac)");
    //Select para recoger el id de la categoría escogida
    $stmt3->bindParam(':DNI', $DNI);
    $stmt3->bindParam(':nombre',  $nombre_emp);
    $stmt3->bindParam(':apellidos', $apellidos_emp);
    $stmt3->bindParam(':salario', $salario);
    $stmt3->bindParam(':fecha_nac', $nacimiento);
    $stmt3->execute();
    $stmt2->execute();
    $conn->commit();
     
    }
    catch(PDOException $e)
    {
        echo "Error: " . $e->getMessage();

        echo "Código de error: " . $e->getCode() . "<br>";

        $conn->rollBack();
    }
    $conn=null;
}
 function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>
</FORM>

</BODY>
</HTML>
