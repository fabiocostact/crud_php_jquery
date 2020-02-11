<?php
	echo $hash = password_hash(123456, PASSWORD_DEFAULT);
	
	if(password_verify(123456, $hash))
		echo "<br>sim";
?>