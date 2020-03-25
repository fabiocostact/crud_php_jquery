<?php
	
	session_start();
	ini_set('default_charset','UTF-8');
	include "verifica_acesso.php";

	if(isset($_SESSION['acessa_usuario']) && $_SESSION['acessa_usuario'] == 'N'){   
		header('location:sistema');
		exit;
	}
	
	if(isset($_REQUEST['cod_usuario']) && $_REQUEST['cod_usuario'] >= 1){
		$vcod_usuario = $_REQUEST['cod_usuario'];
	}else{
		$vcod_usuario = 0;
	}

?>
<script src="js/funcoes.js"></script>
<script>
	$(document).ready(function(){
		$("#login").keyup(function(){
			$(this).val($(this).val().toUpperCase());
		});
		$("#nome").keyup(function(){
			$(this).val($(this).val().toUpperCase());
		});
		
		$('#gravar').click(function() {
			$(gravar_user);
	 	});
		
		$('#voltar').click(function() {
			 $.blockUI({
			  message: '<img src="img/loading1.gif" height=100px width=100px/>', 
			css: {
			  backgroundColor: 'transparent',
			  border: '0'
			  }
			});
			$('#conteudo').load("list_usuario");
			$.unblockUI();
	 	});
		
		busca_usuario($("#cod_usuario").val()); 
	});
</script>
<div class="box box-default">
<div class="box-header with-border">
  <h3 class="box-title">Cadastro de Usuário</h3>
</div>
<form role="form" name="form" id="form">
  <div class="box-body">
    <div class="form-group col-lg-10 col-xs-10">
      <label for="nome">Nome*</label>
      <input type="text" class="form-control" name="nome" id="nome" placeholder="Digite o nome do usuário" maxlength="100" minlength="1" value="">
      <input type="hidden" name="cod_usuario" id="cod_usuario" value="<?php echo $vcod_usuario?>">
    </div>
    <div class="box-body col-lg-2 col-xs-2">
      <label for="status">Status</label>
      <div class="form-group">
          <input type="radio" name="status" id="statusA" class="flat-red" value="A"> Ativo
          <input type="radio" name="status" id="statusI" class="flat-red" value="C"> Cancelado
      </div>
    </div>
    <div class="form-group col-lg-4">
      <label for="login">Login*</label>
      <input type="text" class="form-control" id="login" placeholder="Login" maxlength="20" minlength="1" value="" name="login">
    </div>
    <div class="form-group col-lg-4">
      <label for="Senha"><?php echo ($vcod_usuario <= 0 ? 'Senha*' : 'Senha')?></label>
      <input type="password" class="form-control" id="senha" value="" minlength="4" maxlength="10" name="senha">
    </div>
    <div class="form-group col-lg-2">
     <label for="usuario">Acessa Usuário</label><br>
     <input type="checkbox" class="flat-red" name="acessa_usuario" id="acessa_usuario" value="S">
    </div>
  	<div class="form-group col-lg-2">
                  <label>Tipo</label>
                  <select class="form-control" id="tipo" name="tipo">
                    <option value="G">Gerência</option>
                    <option value="A">Administrativo</option>
                    <option value="C">Consultor</option>
                  </select>
    </div>
  </div>
  <div class="box-footer">
    <div class="form-group col-lg-6">
    	<button type="button" class="btn btn-primary" name="gravar" id="gravar">Gravar</button>
    </div>
    <div class="form-group col-lg-6" align="right">
    	<button type="button" class="btn btn-default" name="voltar" id="voltar">Voltar</button>
    </div>
  </div>
</form>
</div>

<script>
	$('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass   : 'iradio_minimal-blue'
    })
    //Red color scheme for iCheck
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
      checkboxClass: 'icheckbox_minimal-red',
      radioClass   : 'iradio_minimal-red'
    })
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass   : 'iradio_flat-green'
    })
</script>