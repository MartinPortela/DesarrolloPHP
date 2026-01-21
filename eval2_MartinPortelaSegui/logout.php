<?php
setcookie("usuario", "", time() - 3600, "/");
setcookie("password", "", time() - 3600, "/");
header('Location: index.php');
?>