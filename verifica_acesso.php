<?php
	if(!isset($_SESSION['id_usuario'])){   
		session_destroy();
		header('location:index');
	}
	
?>