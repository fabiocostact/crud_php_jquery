<?php
ini_set('default_charset','UTF-8');


?>
<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Fideliza</title>
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/font-awesome.min.css">
<link rel="shortcut icon" href="img/ico.ico" type="image/x-icon" />
<script src="js/jquery.min_1.js"></script>
<script src="js/bootstrap.min.js"></script> 
<script src="js/sweetalert.min.js"></script> 
<script src="js/sleep.js"></script>
<script src="js/funcoes.js"></script>
<style type="text/css">
	.login-form {
		width: 385px;
		margin: 100px auto;
	}
    .login-form  {        
    	margin-bottom: 15px;
        background: #ffffff;
        box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
		border: 1px #CCC solid;
        padding: 30px;
    }
    .login-form h2 {
        margin: 0 0 15px;
    }
    .form-control, .login-btn {
        min-height: 38px;
        border-radius: 2px;
    }
    .input-group-addon .fa {
        font-size: 18px;
    }
    .login-btn {
        font-size: 15px;
        font-weight: bold;
    }
	
	
	body {
	 background-color: #f7f7f7;
	}
	
</style>
<script>
	
	$(document).ready( function(){
		$("#logar").click(function(){
			$(verifica_acesso);
		});
   });
   
    

</script>
</head>
<body>

<div class="login-form">
  <form name="form" id="form" method="post" action="">
        <h2 class="text-center"><img src="img/logo.png" width="200" height="71"></h2>   
        <div class="form-group">
        	<div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                <input type="text" class="form-control" name="usuario" placeholder="Usuário" id="usuario" minlength="4" maxlength="15" value="<?php echo (isset($_COOKIE['cook_lembrar']) && $_COOKIE['cook_lembrar'] == 'S' ? $_COOKIE['cook_user_fideliza'] : '')?>">				
            </div>
        </div>
		<div class="form-group">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                <input type="password" class="form-control" name="senha" placeholder="Senha" id="senha" minlength="4" maxlength="15">				
            </div>
        </div>        
        <div class="form-group">
            <button type="button" name="logar" id="logar" class="btn btn-primary login-btn btn-block">Entrar</button>
        </div>
        <div class="clearfix">
            <label class="pull-left checkbox-inline"><input type="checkbox" id="lembrar" name="lembrar" value="S" <?php echo (isset($_COOKIE['cook_lembrar']) && $_COOKIE['cook_lembrar'] == 'S' ? 'checked' : '')?>>
              Lembrar usuário
            </label>
            <!--<a href="#" class="pull-right">Esqueci a senha</a>!-->
        </div>
</form>
</div>
</body>
</html>                            