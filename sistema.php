<?php

	session_start();
	ini_set('default_charset','UTF-8');
	include "verifica_acesso.php";
	
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>:: Fideliza ::</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/font-awesome.min.css">
  <link rel="stylesheet" href="css/ionicons.min.css">
  <link rel="stylesheet" href="css/AdminLTE.min.css">
  <link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
  <link rel="stylesheet" href="css/select2.min.css">
  <link rel="stylesheet" href="css/all.css">

  <link rel="stylesheet" href="css/skin-green-light.min.css">

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <link rel="shortcut icon" href="img/ico.ico" type="image/x-icon" />

</head>

<body class="hold-transition skin-green-light sidebar-mini fixed">

<div class="wrapper">
  <header class="main-header">

    <a href="sistema" class="logo">
      <span class="logo-mini"><img src="img/logo.png" width="55" height="20"></span>
      <span class="logo-lg"><img src="img/logo.png" width="120" height="43"></span>
    </a>

    <nav class="navbar navbar-static-top" role="navigation">
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <li class="dropdown messages-menu">
          
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="img/user.png" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo $_SESSION["nome"];?></span>
            </a>
            <ul class="dropdown-menu">
              <li class="user-header">
                <img src="img/user.png" class="img-circle" alt="User Image">

                <p>
                  <?php echo $_SESSION["nome"];?>
                </p>
              </li>
              <li class="user-footer">
                <!--<div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Alterar Senha</a>
                </div>!-->
                <div class="pull-right">
                  <a href="sair" class="btn btn-default btn-flat">Sair</a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <aside class="main-sidebar">

    <section class="sidebar">
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">Menu</li>
        <?php if(isset($_SESSION['acessa_usuario']) && $_SESSION['acessa_usuario'] == 'S'){ ?>
        	<li><a href="#" id="link1"><i class="fa fa-users"></i> <span>Cadastrar Usuário</span></a></li>
        <?php }?>
        <li><a href="#" id="link2"><i class="fa fa-list-alt"></i> <span>Solicitações</span></a></li>
      </ul>
    </section>
  </aside>

  <div class="content-wrapper">
    <section class="content container-fluid" id="conteudo">
    </section>
  </div>
  
</div>

<script src="js/jquery.min_2.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/adminlte.min.js"></script>
<script src="js/links.js"></script>
<script src="js/icheck.min.js"></script>
<script src="js/sweetalert.min.js"></script>
<script src="js/sleep.js"></script> 
</body>
</html>