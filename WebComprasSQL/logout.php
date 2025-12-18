<html><body>
<?php
session_start();
session_unset();
session_destroy();
header('Location: comlogincli.php');
?>
</body></html>