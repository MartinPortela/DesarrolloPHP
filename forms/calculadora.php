<?php

$operando1=$operando2=$operacion="";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $operando1 = test_input($_POST["operando1"]);
  $operando2 = test_input($_POST["operando2"]);
  $operacion = test_input($_POST["operacion"]);
  operaciones($operacion,$operando1,$operando2);
}


function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function operaciones($operacion,$operando1,$operando2){
    if($operacion=="suma"){
        $operacion=$operando1+$operando2;
        echo "Resultado operación: ",$operando1," + ",$operando2," = ",$operacion;
    } else if($operacion=="resta"){
        $operacion=$operando1-$operando2;
        echo "Resultado operación: ",$operando1," - ",$operando2," = ",$operacion;
    }else if($operacion=="producto"){
        $operacion=$operando1*$operando2;
        echo "Resultado operación: ",$operando1," * ",$operando2," = ",$operacion;
    }else{
        $operacion=$operando1/$operando2;
        echo "Resultado operación: ",$operando1," / ",$operando2," = ",$operacion;
    }
}

?>