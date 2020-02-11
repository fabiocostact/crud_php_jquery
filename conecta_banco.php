<?php

		
$dsn = 'mysql:dbname=fideliza;host=localhost';
$user = 'root';
$password = '';

try {
 	$con = new PDO($dsn, $user, $password);
	$con->exec("SET CHARACTER SET utf8");
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}


?>
