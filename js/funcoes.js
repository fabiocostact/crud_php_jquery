//<span class='glyphicon glyphicon-pencil' style='font-size:15px;color:#65ac5f'></span>
//ctrl+f5
/*window.onload = function() {
	if(!window.location.hash) {
		window.location = window.location + '#loaded';
		window.location.reload(true);
	}
}*/

function verifica_acesso(){
	if($("#usuario").val().length < $("#usuario").attr("minlength") || $("#usuario").val().length > $("#usuario").attr("maxlength")){
		swal({
		  title: "Campo inválido!",
		  text: "O campo [usuário] deve conter de "+$("#usuario").attr("minlength")+" a "+$("#usuario").attr("maxlength")+" caracteres!",
		 // icon: "error",
		  button: "Ok"
	  }).then(function(){
    	$("#usuario").focus();
  	  });
	}else if($("#senha").val().length < $("#senha").attr("minlength") || $("#senha").val().length > $("#senha").attr("maxlength")){ 
		swal({
		  title: "Campo inválido!",
		  text: "O campo [senha] deve conter de "+$("#senha").attr("minlength")+" a "+$("#senha").attr("maxlength")+" caracteres!",
		 // icon: "error",
		  button: "Ok"
	  }).then(function(){
    	$("#senha").focus();
  	  });
	}else{
		
		var lembrar = $("input[name='lembrar']:checked").val();
		
		$.ajax({
				url: 'funcoes',
				method: 'POST',
				dataType: 'JSON',
				data:{acao:'verifica_acesso',
					usuario:$("#usuario").val(),
					senha:$("#senha").val(),
					lembrar:lembrar
				},
				beforeSend: function(){
					 $.blockUI({
						message: '<img src="img/loading1.gif" height=100px width=100px/>', 
					  css: {
						backgroundColor: 'transparent',
						border: '0'
						}
					  });
				},
				complete: function(){
					$.unblockUI();
				},
				success:function(result){
					$.each(result, function(i, obj){
					   if (obj.retorno == '0'){
						  swal({
							  title: obj.msg,
							  //text: "You clicked the button!",
							  icon: "error",
							  button: "Ok",
						  });
					   }else{
						 $(location).attr('href', 'sistema');
					   };
					});
				},
				error:function(){
					console.log('erro');
				}
		});
	}
}

function link3(cod_usuario){

	$.blockUI({
	  message: '<img src="img/loading1.gif" height=100px width=100px/>', 
	css: {
	  backgroundColor: 'transparent',
	  border: '0'
	  }
	});
	$('#conteudo').load("cad_usuario?cod_usuario="+cod_usuario);
	$.unblockUI();
}

function link4(cod_solicitacao){
	
	$.blockUI({
	  message: '<img src="img/loading1.gif" height=100px width=100px/>', 
	css: {
	  backgroundColor: 'transparent',
	  border: '0'
	  }
	});
	$('#conteudo').load("cad_solicitacao?cod_solicitacao="+cod_solicitacao);
	$.unblockUI();
}


function busca_usuario(cod_usuario){
	
		if(typeof cod_usuario == 'undefined'){
			cod_usuario = 0;
		}
		 
		$.ajax({
				url: 'funcoes',
				method: 'POST',
				dataType: 'JSON',
				data:{acao:'busca_usuario', cod_usuario:cod_usuario},
				beforeSend: function(){
					 $.blockUI({
						message: '<img src="img/loading1.gif" height=100px width=100px/>', 
					  css: {
						backgroundColor: 'transparent',
						border: '0'
						}
					  });
				},
				complete: function(){
					$.unblockUI();
				},
				success: function(result){

					var retorno = "<thead><tr><th>Nome</th><th>Login</th><th>Status</th><th width='7%' align='center'>Editar</th></tr></thead><tbody>";
						
					$.each(result, function(i, obj){
						if(obj.retorno == 0){
							retorno += "<tr><td colspan='4' align='center'>"+obj.msg+"</td></tr>";
						}else if(obj.retorno == 2){
							$("#nome").val(obj.nome);
							$("#login").val(obj.login);

							if(obj.status == 'Ativo'){
								$("#statusA").iCheck('check');
							}else{
								$("#statusI").iCheck('check');
							}
							
							if(obj.acessa_usuario == 'S'){
								$("#acessa_usuario").iCheck('check');
							}
							
						}else{
							retorno += "<tr><td>"+obj.nome+"</td><td>"+obj.login+"</td>";
							
							if(obj.status == 'Ativo'){
								retorno += "<td><span class='label label-success'>"+obj.status+"</span></td>";
							}else{
								retorno += "<td><span class='label label-danger'>"+obj.status+"</span></td>";
							}
							
							retorno += "<td align='center'><a href='#' onclick='link3("+obj.codigo+")'><img src='img/lapis.png' width='auto' height='15px' border='0'/></a></td></tr>";
						}
					});
					
					retorno += "</tbody>";
					
					$('#grid').html(retorno);
					
					$('#grid').DataTable({
						  'destroy'     : true,
						  'paging'      : true,
						  'lengthChange': true,
						  'searching'   : true,
						  //'ordering'    : true,
						  'info'        : true,
						  'autoWidth'   : true,
						  "aoColumns": [null,null,null,{"bSortable": false}]
					});
				},
				error:function(){
					console.log('erro');
				}
		});
}

function busca_ocorrencia(){
		 $.ajax({
				url: 'funcoes',
				method: 'POST',
				dataType: 'JSON',
				data:{acao:'busca_ocorrencia', cod_cliente:$("#cod_cliente").val()},
				beforeSend: function(){
					 $.blockUI({
						message: '<img src="img/loading1.gif" height=100px width=100px/>', 
					  css: {
						backgroundColor: 'transparent',
						border: '0'
						}
					  });
				},
				complete: function(){
					$.unblockUI();
				},
				success: function(result){

					var retorno = "<thead><tr><th>Descrição</th><th>Status</th><th>Dt. Inserção</th><th>Usuário</th></thead><tbody>";
						
					$.each(result, function(i, obj){
						if(obj.retorno == 0){
							retorno += "<tr><td colspan='4' align='center'>"+obj.msg+"</td></tr>";
						}else{
							retorno += "<tr><td>"+obj.descricao+"</td>";
							
							if(obj.status == '1'){
								retorno += "<td><span class='label label-info'>Pendente</span></td>";
							}else if(obj.status == '2'){
								retorno += "<td><span class='label label-warning'>Em Análise</span></td>";
							}else if(obj.status == '3'){
								retorno += "<td><span class='label label-success'>Aprovado</span></td>";
							}else{
								retorno += "<td><span class='label label-danger'>Negado</span></td>";
							}
							
							retorno += "<td>"+obj.dt_inclusao+"</td><td>"+obj.usuario+"</td></tr>";
						}
					});
					
					retorno += "</tbody>";
					
					$('#grid').html(retorno);
					
					$('#grid').DataTable({
							  'destroy'     : true,
							  'paging'      : true,
							  'lengthChange': false,
							  'searching'   : false,
							  'ordering'    : false,
							  'info'        : true,
							  'autoWidth'   : true
					});
					
				},
				error:function(){
					console.log('erro');
				}
		});
}

function busca_cliente(){
		 
		$.ajax({
				url: 'funcoes',
				method: 'POST',
				dataType: 'JSON',
				data:{acao:'busca_cliente'},
				beforeSend: function(){
					 $.blockUI({
						message: '<img src="img/loading1.gif" height=100px width=100px/>', 
					  css: {
						backgroundColor: 'transparent',
						border: '0'
						}
					  });
				},
				complete: function(){
					$.unblockUI();
				},
			    success: function(result){

					var retorno = "<thead><tr><th>Nome</th><th>Dt. Nascimento</th><th>CPF</th><th>Status</th><th>Dt. Status</th><th width='7%' align='center'>Editar</th></tr></thead><tbody>";
						
					$.each(result, function(i, obj){
						if(obj.retorno == 0){
							retorno += "<tr><td colspan='6' align='center'>"+obj.msg+"</td></tr>";
						}else{
							retorno += "<tr><td>"+obj.nome+"</td><td>"+obj.data_nascimento+"</td><td>"+obj.cpf+"</td>";
							
							if(obj.status == '1'){
								retorno += "<td><span class='label label-info'>Pendente</span></td>";
							}else if(obj.status == '2'){
								retorno += "<td><span class='label label-warning'>Em Análise</span></td>";
							}else if(obj.status == '3'){
								retorno += "<td><span class='label label-success'>Aprovado</span></td>";
							}else{
								retorno += "<td><span class='label label-danger'>Negado</span></td>";
							}
							
							retorno += "<td>"+obj.dt_inclusao+"</td><td align='center'><a href='#' onclick='link4("+obj.codigo+")'><img src='img/lapis.png' width='auto' height='15px' border='0'/></a></td></tr>";
						}
					});
					
					retorno += "</tbody>";
					
					$('#grid').html(retorno);
					
					$('#grid').DataTable({
						  'destroy'     : true,
						  'paging'      : true,
						  'lengthChange': true,
						  'searching'   : true,
						  //'ordering'    : true,
						  'info'        : true,
						  'autoWidth'   : true,
						  "aoColumns": [null,null,null, null, null,{"bSortable": false}]
					});
				},
				error:function(){
					console.log('erro');
				}
		});
}

function gravar_user(){
	if($("#nome").val().length < $("#nome").attr("minlength") || $("#nome").val().length > $("#nome").attr("maxlength")){ 
		swal({
		  title: "Campo inválido!",
		  text: "O campo [nome] deve conter de "+$("#nome").attr("minlength")+" a "+$("#nome").attr("maxlength")+" caracteres!",
		 // icon: "error",
		  button: "Ok"
	  }).then(function(){
    	$("#nome").focus();
  	  });
	}else if($("#login").val().length < $("#login").attr("minlength") || $("#login").val().length > $("#login").attr("maxlength")){ 
		swal({
		  title: "Campo inválido!",
		  text: "O campo [login] deve conter de "+$("#login").attr("minlength")+" a "+$("#login").attr("maxlength")+" caracteres!",
		 // icon: "error",
		  button: "Ok"
	  }).then(function(){
    	$("#login").focus();
  	  });
	}else if($("#cod_usuario").val() <= 0 && $("#senha").val().length < $("#senha").attr("minlength") || $("#senha").val().length > $("#senha").attr("maxlength")){ 
		swal({
		  title: "Campo inválido!",
		  text: "O campo [senha] deve conter de "+$("#senha").attr("minlength")+" a "+$("#senha").attr("maxlength")+" caracteres!",
		 // icon: "error",
		  button: "Ok"
	  }).then(function(){
    	$("#senha").focus();
  	  });
	}else{
		var vstatus = $("input[name='status']:checked").val();

		if($("#acessa_usuario").prop("checked") == true){
			var vacessa_usuario = 'S';
		}else{
			var vacessa_usuario = 'N';
		}
		
		var serializeDados = $('#form').serialize();
		
		$.ajax({
				url: 'funcoes',
				method: 'POST',
				dataType: 'JSON',
				data:{acao:'grava_usuario',
					cod_usuario:$("#cod_usuario").val(),
					nome:$("#nome").val(),
					login:$("#login").val(),
					senha:$("#senha").val(),
					status:vstatus,
					acessa_usuario:vacessa_usuario
				},
				beforeSend: function(){
					 $.blockUI({
						message: '<img src="img/loading1.gif" height=100px width=100px/>', 
					  css: {
						backgroundColor: 'transparent',
						border: '0'
						}
					  });
				},
				complete: function(){
					$.unblockUI();
				},
				success:function(result){
					$.each(result, function(i, obj){
					   if(obj.retorno == '0'){
						  swal({
							  title: obj.msg,
							  //text: "You clicked the button!",
							  icon: "error",
							  button: "Ok",
						  });
					   }else if(obj.retorno == '1'){
						   swal({
							  title: obj.msg,
							  //text: "You clicked the button!",
							  icon: "success",
							  button: "Ok",
						  });
					   }
					});
				},
				error: function(){
					console.log('erro');
				}
			});
	}
	
}


function gravar_ocorrencia(){
	if($("#ocorrencia").val().length < $("#ocorrencia").attr("minlength") || $("#ocorrencia").val().length > $("#ocorrencia").attr("maxlength")){ 
		swal({
		  title: "Campo inválido!",
		  text: "O campo [ocorrencia] deve conter de "+$("#ocorrencia").attr("minlength")+" a "+$("#ocorrencia").attr("maxlength")+" caracteres!",
		 // icon: "error",
		  button: "Ok"
	  }).then(function(){
    	$("#ocorrencia").focus();
  	  });
	}else if($("#status").val() <= 0){ 
		swal({
		  title: "Campo inválido!",
		  text: "Selecione um status.",
		 // icon: "error",
		  button: "Ok"
	  }).then(function(){
    	$("#status").focus();
  	  });
	}else{
		
		if($("#status").val() == 3 && $("#valor_aprovado").val() == ""){ 
			swal({
			  title: "Campo inválido!",
			  text: "Informe o valor aprovado para empréstimo.",
			 // icon: "error",
			  button: "Ok"
		  }).then(function(){
			$("#valor_aprovado").focus();
		  });
		}else if($("#status").val() == 3 && $("#qtd_parcelas").val() == ""){ 
			swal({
			  title: "Campo inválido!",
			  text: "Informe a quantidade de parcelas aprovado para empréstimo.",
			 // icon: "error",
			  button: "Ok"
		  }).then(function(){
			$("#qtd_parcelas").focus();
		  });
		}else if($("#status").val() == 3 && $("#valor_parcela").val() == ""){ 
			swal({
			  title: "Campo inválido!",
			  text: "Informe o valor aprovado por parcela para empréstimo.",
			 // icon: "error",
			  button: "Ok"
		  }).then(function(){
			$("#valor_parcela").focus();
		  });
		}else{
		
				$.ajax({
						url: 'funcoes',
						method: 'POST',
						dataType: 'JSON',
						data:{acao:'grava_ocorrencia',
							cod_cliente:$("#cod_cliente").val(),
							status:$("#status").val(),
							valor_aprovado:$("#valor_aprovado").val(),
							valor_parcela:$("#valor_parcela").val(),
							qtd_parcelas:$("#qtd_parcelas").val(),
							ocorrencia:$("#ocorrencia").val()	
						},
						beforeSend: function(){
							 $.blockUI({
								message: '<img src="img/loading1.gif" height=100px width=100px/>', 
							  css: {
								backgroundColor: 'transparent',
								border: '0'
								}
							  });
						},
						complete: function(){
							$.unblockUI();
						},
						success:function(result){
							$.each(result, function(i, obj){
							   if(obj.retorno == '0'){
								  swal({
									  title: obj.msg,
									  //text: "You clicked the button!",
									  icon: "error",
									  button: "Ok",
								  });
							   }else if(obj.retorno == '1'){
								   swal({
									  title: obj.msg,
									  //text: "You clicked the button!",
									  icon: "success",
									  button: "Ok",
								  }).then(function(){
										$('#myModal').modal('hide');
								  });
							   }
							});
						},
						error: function(){
							console.log('erro');
						}
						
				});
			}
	}
}

function busca_imagens(){
	
	$.ajax({
				url: 'funcoes',
				method: 'POST',
				dataType: 'JSON',
				data:{acao:'busca_imagens', cod_cliente:$("#cod_cliente").val()},
				beforeSend: function(){
					 $.blockUI({
						message: '<img src="img/loading1.gif" height=100px width=100px/>', 
					  css: {
						backgroundColor: 'transparent',
						border: '0'
						}
					  });
				},
				complete: function(){
					$.unblockUI();
				},
				success: function(result){

					var retorno = "<thead><tr><th>Nome</th><th>Descrição</th><th>Ação</th></thead><tbody>";
						
					$.each(result, function(i, obj){
						if(obj.retorno == 0){
							retorno += "<tr><td colspan='3' align='center'>"+obj.msg+"</td></tr>";
						}else{
							retorno += "<tr><td>"+obj.img+"</td><td>"+obj.descricao+"</td><td>Ação</td></tr>";
						}
					});
					
					retorno += "</tbody>";
					
					$('#grid_img').html(retorno);
					
					$('#grid_img').DataTable({
							  'destroy'     : true,
							  'paging'      : false,
							  'lengthChange': false,
							  'searching'   : false,
							  'ordering'    : false,
							  'info'        : false,
							  'autoWidth'   : true
					});
					
				},
				error:function(){
					console.log('erro');
				}
		});
}