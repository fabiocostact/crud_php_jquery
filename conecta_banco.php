<?php

		
$dsn = 'mysql:dbname=fideliza;host=localhost';
$user = 'root';
$password = '';

//http://fizeliza.gmpti.com/

/*
$dsn = 'mysql:dbname=gmptic21_fideliza;host=cpanel.gmpti.com';
$user = 'gmptic21_root';
$password = 'a2dd80_apps';*/

try {
 	$con = new PDO($dsn, $user, $password);
	$con->exec("SET CHARACTER SET utf8");
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}


?>