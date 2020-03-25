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

					var retorno = "<thead><tr><th>Nome</th><th>Login</th><th>Tipo</th><th>Status</th><th width='7%' align='center'>Editar</th></tr></thead><tbody>";
						
					$.each(result, function(i, obj){
						if(obj.retorno == 0){
							retorno += "<tr><td colspan='5' align='center'>"+obj.msg+"</td></tr>";
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

							$("#tipo").val(obj.tipo);
							
						}else{
							retorno += "<tr><td>"+obj.nome+"</td><td>"+obj.login+"</td><td>"+obj.tipo+"</td>";
							
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
						  "aoColumns": [null,null,null,null,{"bSortable": false}]
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
							
							retorno += "<td>"+verifica_status(obj.status)+"</td>";
							
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

function verifica_status(status){
	var retorno = "";
	
	if(status == '1'){
		retorno += "<span class='label' style='background-color:#FF69B4'>Conferência</span>";//rosa
	}else if(status == '2'){
		retorno += "<span class='label' style='background-color:#BEBEBE'>Fila Auditoria</span>";
	}else if(status == '3'){
		retorno += "<span class='label' style='background-color:#FFFF00; color:#000000'>Pendente</span>";
	}else if(status == '4'){
		retorno += "<span class='label' style='background-color:#FFA500'>Análise</span>";//laranja
	}else if(status == '5'){
		retorno += "<span class='label label-success'>Aprovado</span>";
	}else if(status == '6'){
		retorno += "<span class='label label-danger'>Negado</span>";
	}else if(status == '7'){
		retorno += "<span class='label label-primary'>Pago</span>";
	}else if(status == '8'){
		retorno += "<span class='label' style='background-color:#000000'>Cancelado</span>";
	}
	
	return retorno;	
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

					var retorno = "<thead><tr><th>Nome</th><th>Dt. Nascimento</th><th>CPF</th><th>Status</th><th>Dt. Status</th><th>Vendedor</th><th width='7%' align='center'>Editar</th></tr></thead><tbody>";
						
					$.each(result, function(i, obj){
						if(obj.retorno == 0){
							retorno += "<tr><td colspan='6' align='center'>"+obj.msg+"</td></tr>";
						}else{
							retorno += "<tr><td>"+obj.nome+"</td><td>"+obj.data_nascimento+"</td><td>"+obj.cpf+"</td>";
							
							retorno += "<td>"+verifica_status(obj.status)+"</td>";
							
							retorno += "<td>"+obj.dt_inclusao+"</td><td>"+obj.vendedor+"</td><td align='center'><a href='#' onclick='link4("+obj.codigo+")'><img src='img/lapis.png' width='auto' height='15px' border='0'/></a></td></tr>";
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
						  "aoColumns": [null,null,null, null, null,null,{"bSortable": false}]
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
					acessa_usuario:vacessa_usuario,
					tipo:$("#tipo").val()
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
		
		if($("#status").val() == 7 && $("#valor_aprovado").val() == ""){ 
			swal({
			  title: "Campo inválido!",
			  text: "Informe o valor aprovado para empréstimo.",
			 // icon: "error",
			  button: "Ok"
		  }).then(function(){
			$("#valor_aprovado").focus();
		  });
		}else if($("#status").val() == 7 && $("#qtd_parcelas").val() == ""){ 
			swal({
			  title: "Campo inválido!",
			  text: "Informe a quantidade de parcelas aprovado para empréstimo.",
			 // icon: "error",
			  button: "Ok"
		  }).then(function(){
			$("#qtd_parcelas").focus();
		  });
		}else if($("#status").val() == 7 && $("#valor_parcela").val() == ""){ 
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
										$("#status_emprestimo_st").val($("#status").val());
										$(busca_ocorrencia);
										$(busca_imagens);
										$("#status_emprestimo").html(verifica_status($("#status").val()));
										
										if($("#status").val() == 7 || $("#status").val() == 8){
											$("#modal_status").attr("disabled", true);
										}
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

function download_img(img){	
		
	var url = 'api/uploads/'+img;
	var fileName = img;
	
	var xhr = new XMLHttpRequest();
    xhr.open("GET", url, true);
    xhr.responseType = "blob";
    xhr.onload = function(){
        var urlCreator = window.URL || window.webkitURL;
        var imageUrl = urlCreator.createObjectURL(this.response);
        var tag = document.createElement('a');
        tag.href = imageUrl;
        tag.download = fileName;
        document.body.appendChild(tag);
        tag.click();
        document.body.removeChild(tag);
    }
    xhr.send();
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

					var retorno = "<thead><tr><th>Nome</th><th>Descrição</th><th align='center'>Ação</th></thead><tbody>";
						
					$.each(result, function(i, obj){
						if(obj.retorno == 0){
							retorno += "<tr><td colspan='3' align='center'>"+obj.msg+"</td></tr>";
						}else{
							retorno += "<tr><td>"+obj.img+"</td><td>"+obj.descricao+"</td><td>"+
									"<button type='button' style='margin-right: 1%' class='btn btn-default' data-toggle='modal' data-target='#modal_img' data-img='"+obj.img+"' data-tipo='"+obj.descricao+"'><i class='fa  fa-picture-o text-primary'></i></button>"+ 
									"<button type='button' onclick=\"download_img('"+obj.img+"')\" style='margin-right: 1%' class='btn btn-default'><i class='fa  fa-download'></i></button>";
									
							if($("#status_emprestimo_st").val() != 3){		
								retorno += "<button type='button' onclick=\"exclui_img('"+obj.img+"', "+obj.id+")\" style='margin-right: 1%' class='btn btn-default'><i class='fa  fa-trash text-danger'></i></button>";
							}
							retorno += "</td></tr>";
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

function exclui_img(img, id){
	
		swal({
      		title: "Excluir imagem!!!",
      		text: "Deseja excluir essa imagem?",
      		icon: "warning",
      		buttons: [
        		'Cancelar',
        		'Excluir'
      		],
      		dangerMode: true,
    	}).then(function(isConfirm){
      		if(isConfirm){
        		
				$.ajax({
				url: 'funcoes',
				method: 'POST',
				dataType: 'JSON',
				data:{acao:'exclui_imagens', cod_imagem:id, nome_imagem:img},
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
						$.each(result, function(i, obj){
							   if(obj.retorno == '0'){
								  swal({
									  title: obj.msg,
									  icon: "error",
									  button: "Ok",
								  });
							   }else if(obj.retorno == '1'){
								   swal({
									  title: obj.msg,
									  icon: "success",
									  button: "Ok",
								  }).then(function(){
										$(busca_imagens);
								  });
							   }
							});										
				},
				error:function(){
					console.log('erro');
				}
				});
				
        	}
			/*} else {
				swal("Cancelled", "Your imaginary file is safe :)", "error");
			}*/
    	})
}


