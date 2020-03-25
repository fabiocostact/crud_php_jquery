<?php

	session_start();
	ini_set('default_charset','UTF-8');
	include "verifica_acesso.php";
	//sleep(5);
?>

<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap.min.js"></script>
<script src="js/jquery.slimscroll.min.js"></script>
<script src="js/fastclick.js"></script>
<script src="js/funcoes.js"></script>

<!--<script src="js/demo.js"></script>!-->
<script>
	$(document).ready( function(){
		 
		 $(busca_usuario);
		 
		 $('#novo').click(function() {
			 $.blockUI({
			  message: '<img src="img/loading1.gif" height=100px width=100px/>', 
			css: {
			  backgroundColor: 'transparent',
			  border: '0'
			  }
			});
			$('#conteudo').load("cad_usuario");
			$.unblockUI();
		 });
	});	
	
</script>
<div class="row">
        <div class="col-xs-12">
			<div class="box">
      			<div class="box-header">
        			<h3 class="box-title">Pesquisa de Usuários</h3>
      			</div>
                <div class="col-lg-12" align="right">
                    <button type="button" class="btn btn-primary" name="novo" id="novo">Novo</button>
                 </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive" id="lista_usuario">
                  <table id="grid" class="table table-bordered table-striped table-hover">
                    
                  </table>
                </div>
			</div>
	</div>
</div>