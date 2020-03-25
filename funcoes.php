<?php

session_start(); 
require_once("conecta_banco.php");

if(isset($_REQUEST['acao']) && $_REQUEST['acao'] == 'verifica_acesso'){
	
	$resultado = array();
	
	if(isset($_REQUEST['usuario']) && !empty($_REQUEST['usuario']) && isset($_REQUEST['senha']) && !empty($_REQUEST['senha'])){
	
		$rs = $con->prepare("SELECT id, 
									nome,
									login,
									senha,
									acessa_usuario,
									id_empresa,
									tipo
								FROM 
									usuario
								WHERE 
									BINARY  login = :login and
									status = 'A'");
		$rs->bindParam(':login', $_REQUEST['usuario']);
		$rs->execute();

		if($row = $rs->fetch(PDO::FETCH_OBJ)){
		    if(password_verify($_REQUEST['senha'], $row->senha)){
				$_SESSION["id_usuario"] = $row->id;
				$_SESSION["nome"] = $row->nome;
				$_SESSION["login"] = $row->login;
				$_SESSION['empresa'] = $row->id_empresa;
				$_SESSION["acessa_usuario"] = $row->acessa_usuario;
				$_SESSION["tipo_usuario"] = $row->tipo;
				array_push($resultado, array("retorno" => 1));
				
				if(isset($_REQUEST['lembrar']) && $_REQUEST['lembrar'] == 'S'){
					setcookie("cook_lembrar", 'S');
					setcookie("cook_user_fideliza", $_SESSION["login"]);
				}else{
					setcookie("cook_lembrar", 'N');
					setcookie("cook_user_fideliza", '');
				}
				
				echo json_encode($resultado);
			}else{
				session_destroy();
				array_push($resultado, array("retorno" => 0,  "msg" => "O usuário ou senha inválidos!"));
				echo json_encode($resultado);
			}
		}else{
			session_destroy();
			array_push($resultado, array("retorno" => 0,  "msg" => "O usuário ou senha inválidos!"));
			echo json_encode($resultado);
		}	
	}else{
		session_destroy();
		array_push($resultado, array("retorno" => 0, "msg" => "O usuário e senha devem ser informados!"));
		echo json_encode($resultado);
	}
}

if(isset($_REQUEST['acao']) && $_REQUEST['acao'] == 'busca_usuario'){
	
	$resultado = array();
	
	$sql = "SELECT 
				  id,
				  nome,
				  login,
				  if(status = 'A','Ativo','Cancelado') vstatus,
				  acessa_usuario,
				  tipo,
				  case when tipo = 'G' then 'Gerência'
				  	   when tipo = 'A' then 'Administrativo'
					   when tipo = 'C' then 'Consultor'
				  end as vtipo
			  FROM
				  usuario 
			  where
				  id_empresa = ".$_SESSION['empresa'];
	if($_REQUEST['cod_usuario'] >= 1)
		$sql .= " and id = ".$_REQUEST['cod_usuario'];
	
	$rs = $con->prepare($sql);
	$rs->execute();
	if($row = $rs->fetch(PDO::FETCH_OBJ)){
		do {
			if($_REQUEST['cod_usuario'] >= 1){
				array_push($resultado, array("retorno"=>2,
											"nome"=>$row->nome,
											"login"=>$row->login,
											"status"=>$row->vstatus,
											"tipo"=>$row->tipo,
											"acessa_usuario"=>$row->acessa_usuario));
			}else{
				array_push($resultado, array("retorno"=>1,
											"codigo"=>$row->id,
											"nome"=>$row->nome,
											"login"=>$row->login,
											"tipo"=>$row->vtipo,
											"status"=>$row->vstatus));
			}
		}while($row = $rs->fetch(PDO::FETCH_OBJ));
	}else{
		array_push($resultado, array("retorno"=>0, "msg"=>"Dados não encontratos"));
	}
		
	echo json_encode($resultado);
}

if(isset($_REQUEST['acao']) && $_REQUEST['acao'] == 'busca_cliente'){
	
	$resultado = array();
	
	$rs = $con->prepare("SELECT
							c.id,
							c.nome,
							c.cpf,
							date_format(c.data_nascimento,'%d/%m/%Y') dt_nasc,
							c.status_emprestimo,
							(select 
								COALESCE(date_format( max(o.data_inclusao),'%d/%m/%Y %H:%i'),null,'') 
							from
								ocorrencia o
							where
								o.id_cliente = c.id) dt_inclusao,
							vendedor
						FROM
							cliente c
						where
							c.id_empresa = ".$_SESSION['empresa']."
						order by c.status_emprestimo, dt_inclusao ");
	$rs->execute();
	if($row = $rs->fetch(PDO::FETCH_OBJ)){
		do {
			
			$array_vend = json_decode($row->vendedor);
			
			array_push($resultado, array("retorno"=>1,
										"codigo"=>$row->id, 
										"nome"=>$row->nome,
										"data_nascimento"=>$row->dt_nasc,
										"cpf"=>$row->cpf,
										"status"=>$row->status_emprestimo,
										"dt_inclusao"=>$row->dt_inclusao,
										"vendedor"=>$array_vend->{'vendedor'}));
		}while($row = $rs->fetch(PDO::FETCH_OBJ));
	}else{
		array_push($resultado, array("retorno"=>0, "msg"=>"Dados não encontratos"));
	}
		
	echo json_encode($resultado);
}


if(isset($_REQUEST['acao']) && $_REQUEST['acao'] == 'busca_ocorrencia'){
	
	$resultado = array();
	
	$rs = $con->prepare("select
					o.descricao,
					date_format(o.data_inclusao,'%d/%m/%Y %H:%i') dt_inclusao,
					o.status_emprestimo,
					COALESCE(s.login,null,'') l_login
  				from
					ocorrencia o 
					left join usuario s on s.id = o.id_usuario
  				where
					o.id_cliente = :id_cliente
				order by dt_inclusao desc");
	$rs->bindParam(':id_cliente', $_REQUEST['cod_cliente']);
	$rs->execute();
	if($row = $rs->fetch(PDO::FETCH_OBJ)){
		do {
			
			array_push($resultado, array("retorno"=>1,
										"descricao"=>$row->descricao, 
										"dt_inclusao"=>$row->dt_inclusao,
										"status"=>$row->status_emprestimo,
										"usuario"=>$row->l_login));
		}while($row = $rs->fetch(PDO::FETCH_OBJ));
	}else{
		array_push($resultado, array("retorno"=>0, "msg"=>"Dados não encontratos"));
	}
		
	echo json_encode($resultado);
}


if(isset($_REQUEST['acao']) && $_REQUEST['acao'] == 'grava_usuario'){
	
	$resultado = array();
	
	$vnome = strtoupper(trim($_REQUEST['nome']));
	$vlogin = strtoupper(trim($_REQUEST['login']));
	
	if(!empty($_REQUEST['senha'])){
		$vsenha = password_hash(trim($_REQUEST['senha']), PASSWORD_DEFAULT);
	}else{
		$rs = $con->prepare("SELECT
								senha
							FROM
								usuario
							WHERE
								id = ?");
		$rs->bindParam(1, $_REQUEST['cod_usuario']);
		$rs->execute();
		$row = $rs->fetch(PDO::FETCH_OBJ);
		$vsenha = $row->senha;
	}
		
	if(isset($_REQUEST['cod_usuario']) && $_REQUEST['cod_usuario'] <= 0){
		
		$rs = $con->prepare("SELECT
								count(1) qtd
							FROM
								usuario
							where
								login = ?");
		$rs->bindParam(1, $vlogin);
		$rs->execute();
		$row = $rs->fetch(PDO::FETCH_OBJ);
		if($row->qtd >= 1){
			array_push($resultado, array("retorno"=>0, "msg"=>"O Login não atende os requisitos mínimos, por favor, digite-o novamente!"));
		}else{	
			$rs = $con->prepare("insert into 
									usuario
										(nome,
										login,
										senha,
										status,
										acessa_usuario,
										id_empresa,
										tipo)
									values
										(?, ?, ?, ?, ?, ?, ?)");
			$rs->bindParam(1, $vnome);
			$rs->bindParam(2, $vlogin);
			$rs->bindParam(3, $vsenha);
			$rs->bindParam(4, $_REQUEST['status']);
			$rs->bindParam(5, $_REQUEST['acessa_usuario']);
			$rs->bindParam(6, $_SESSION['empresa']);
			$rs->bindParam(7, $_REQUEST['tipo']);
			
			//echo $rs->queryString; // saída sem parâmetros
			//$patterns = array('?','?','?','?','?');
			//$replacements = array($vnome, $vlogin, $vsenha, $_REQUEST['status'], $_REQUEST['acessa_usuario']);
			//echo preg_replace($patterns, $replacements, $rs->queryString);
			//echo $vnome." - ".$vlogin." - ".$vsenha." - ".$_REQUEST['status']." - ".$_REQUEST['acessa_usuario'];

			if($rs->execute()){
				array_push($resultado, array("retorno"=>1, "msg"=>"Inserido com sucesso!"));			
			}else{
				array_push($resultado, array("retorno"=>0, "msg"=>"Erro ao gravar!"));
			}
		}
	}else{
		
		$rs = $con->prepare("update usuario set
									nome = ?,
									login = ?,
									senha = ?,
									status = ?,
									acessa_usuario = ?,
									tipo = ?
								where
									id = ?");
		$rs->bindParam(1, $vnome);
		$rs->bindParam(2, $vlogin);
		$rs->bindParam(3, $vsenha);
		$rs->bindParam(4, $_REQUEST['status']);
		$rs->bindParam(5, $_REQUEST['acessa_usuario']);
		$rs->bindParam(6, $_REQUEST['tipo']);
		$rs->bindParam(7, $_REQUEST['cod_usuario']);
	    if($rs->execute()){
			array_push($resultado, array("retorno"=>1, "msg"=>"Alterado com sucesso!"));			
		}else{
			array_push($resultado, array("retorno"=>0, "msg"=>"Erro ao alterar!"));
		}
	}
		
	echo json_encode($resultado);
}


function formatCnpjCpf($value){

  $cnpj_cpf = preg_replace("/\D/", '', $value);
  
  if (strlen($cnpj_cpf) === 11) {
    return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cnpj_cpf);
  } 
  
  return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $cnpj_cpf);
}



if(isset($_REQUEST['acao']) && $_REQUEST['acao'] == 'grava_ocorrencia'){
	
	$resultado = array();
	
	$vocorrencia = strtoupper(trim($_REQUEST['ocorrencia']));
	$vstatus = $_REQUEST['status'];
	$vvalor_aprovado = $_REQUEST['valor_aprovado'];
	$vvalor_parcela = $_REQUEST['valor_parcela'];
	$vqtd_parcelas = $_REQUEST['qtd_parcelas'];
	$vcliente = $_REQUEST['cod_cliente'];
	
	try{
		$con->setAttribute(PDO::ATTR_AUTOCOMMIT,0);
		
		$con->beginTransaction();
		
		$rs = $con->prepare("insert into 
										ocorrencia
											(id_cliente,
											descricao,
											id_usuario,
											data_inclusao,
											status_emprestimo)
										values
											(:id_cliente, :descricao, :id_usuario, SYSDATE(), :status)");
		  $rs->bindParam(':id_cliente', $vcliente);
		  $rs->bindParam(':descricao', $vocorrencia);
		  $rs->bindParam(':id_usuario', $_SESSION['id_usuario']);
		  $rs->bindParam(':status', $vstatus);
		  if(!$rs->execute())
		  	throw new Exception();
		  
		  $rs2 = $con->prepare("update cliente set
		  							valor_aprovado = :valor_aprovado,
									qtde_parcelas_aprovada = :qtd_parcelas_aprovadas,
									valor_parcelas_aprovada = :valor_parcelas_aprovadas,
									status_emprestimo = :status
								where 
									id = :id_cliente and
									id_empresa = ".$_SESSION['empresa']);
		  $rs2->bindParam(':id_cliente', $vcliente);
		  $rs2->bindParam(':valor_aprovado', $vvalor_aprovado);
		  $rs2->bindParam(':qtd_parcelas_aprovadas', $vqtd_parcelas);
		  $rs2->bindParam(':valor_parcelas_aprovadas', $vvalor_parcela);
		  $rs2->bindParam(':status', $vstatus);
		  if(!$rs2->execute())
		  	throw new Exception();
		  
		  if($con->commit()){
		  		array_push($resultado, array("retorno"=>1, "msg"=>"Inserido com sucesso!"));
		  }else{
			  array_push($resultado, array("retorno"=>0, "msg"=>"Erro ao gravar!"));
		  }
	}catch(Exception $e){
		$con->rollBack();
		array_push($resultado, array("retorno"=>0, "msg"=>"Erro ao gravar!"));
	}
	
	echo json_encode($resultado);
}


if(isset($_REQUEST['acao']) && $_REQUEST['acao'] == 'busca_imagens'){
	
	$resultado = array();
	
	$rs = $con->prepare("select
							i.id,
							i.nome img,
							d.nome descricao
						from
							imagem i 
							left join tipo_docto d 
								on d.id = i.id_tipo_docto
						where
							i.id_cliente = :id_cliente
						ORDER
							by d.nome");
	$rs->bindParam(':id_cliente', $_REQUEST['cod_cliente']);
	$rs->execute();
	if($row = $rs->fetch(PDO::FETCH_OBJ)){
		do {
			
			array_push($resultado, array("retorno"=>1,
										"img"=>$row->img, 
										"descricao"=>$row->descricao,
										"id"=>$row->id));
		}while($row = $rs->fetch(PDO::FETCH_OBJ));
	}else{
		array_push($resultado, array("retorno"=>0, "msg"=>"Dados não encontratos"));
	}
		
	echo json_encode($resultado);
}

if(isset($_REQUEST['acao']) && $_REQUEST['acao'] == 'exclui_imagens'){
	
	$resultado = array();
	
	try{
		$con->setAttribute(PDO::ATTR_AUTOCOMMIT,0);
		
		$con->beginTransaction();
		
		$rs = $con->prepare("delete from imagem where id = :id_img");
		$rs->bindParam(':id_img', $_REQUEST['cod_imagem']);
		if(!$rs->execute())
			throw new Exception();
			
		if(!unlink("api/uploads/".$_REQUEST['nome_imagem']))
			throw new Exception();
		
		if($con->commit()){
			array_push($resultado, array("retorno"=>1, "msg"=>"Excluido com sucesso!"));
		}else{
			array_push($resultado, array("retorno"=>0, "msg"=>"Erro ao excluir!"));
		}
		
	}catch(Exception $e){
		$con->rollBack();
		array_push($resultado, array("retorno"=>0, "msg"=>"Erro ao excluir!"));
	}
	
	echo json_encode($resultado);
}

?>
