<?php

	session_start();
	ini_set('default_charset','UTF-8');
	include "verifica_acesso.php";
	
?>

<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap.min.js"></script>
<script src="js/jquery.slimscroll.min.js"></script>
<script src="js/fastclick.js"></script>
<script src="js/funcoes.js"></script>

<!--<script src="js/demo.js"></script>!-->
<script>
	$(document).ready( function(){
		 
		 $(busca_cliente);
	});
	
	
</script>
<div class="row">
        <div class="col-xs-12">
			<div class="box">
      			<div class="box-header">
        			<h3 class="box-title">Solicitações</h3>
      			</div>
                <!-- /.box-header -->
                <div class="box-body table-responsive" id="lista_usuario">
                  <table id="grid" class="table table-bordered table-striped table-hover">
                    <thead>
                    <tr>
                      <th>Editar</th>
                      <th>Nome</th>
                      <th>Login</th>
                      <th>Ativo</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>
                    </tbody>
                  </table>
                </div>
			</div>
	</div>
</div>