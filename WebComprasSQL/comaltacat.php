<!DOCTYPE HTML>  
<HTML>
<HEAD> <TITLE>Comaltacat</TITLE>
</HEAD>
<BODY>
<h1>Dar de alta categorías</h1>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

Inserte una nueva categoría:
<input type='text' name='categoria' value='' size=25><br><br><br>
<input type="submit" value="enviar">
<input type="reset" value="borrar">
<?php
$convertir="";
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $categoria=test_input($_POST['categoria']);
    
    $servername = "localhost";
    $username = "root";
    $password = "rootroot";
    $dbname = "comprasweb";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt2 = $conn->prepare("SELECT id_categoria FROM categoria");
    $stmt = $conn->prepare("INSERT INTO categoria (id_categoria,nombre) VALUES (:id_cat,:nombre)");
    $stmt->bindParam(':id_cat', $id_cat);
     $stmt->bindParam(':nombre', $nombre);
    $stmt2->execute();

    // set the resulting array to associative
     $stmt2->setFetchMode(PDO::FETCH_ASSOC);
	 $resultado=$stmt2->fetchAll();
     if($resultado!=null)
     {
	 $ultimaClave = array_key_last($resultado);
     $max=$resultado[$ultimaClave]["id_categoria"];
     $max=substr($max,-3);
     $max++;
     $id_cat='C-'.$max;
     }else
     {
        $id_cat='C-100';
     }
     $nombre=$categoria;
     $stmt->execute();
     echo "Categoría insertada";
}
catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
$conn = null;
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
