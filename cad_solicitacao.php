<?php
	
	
	ini_set('default_charset','UTF-8');
	include "funcoes.php";
	include "verifica_acesso.php";
	

	if(isset($_SESSION['acessa_usuario']) && $_SESSION['acessa_usuario'] == 'N'){   
		header('location:sistema');
		exit;
	}
	
	if(!isset($_REQUEST['cod_solicitacao'])){
		header("Locatio: list_solicitacao");
		exit;
	}
	
	require_once("conecta_banco.php");
	
	$id_cliente = 0;
  	$nome = "";
   	$cpf = "";
    $data_nascimento = "";
  	$rg = "";
  	$data_expedicao = "";
  	$orgao_expedidor = "";
  	$nome_pai = "";
  	$nome_mae = "";
  	$naturalidade = "";
  	$estado_civil = "";
	$sexo = "";
	$cep = "";
	$endereco = "";
	$numero = "";
	$complemento = "";
	$bairro = "";
	$cidade = "";
	$ibge = "";
	$uf = "";
	$telefone = "";
	$celular = "";
	$email = "";
	$id_empregador = "";
	$profissao = "";
	$data_admissao= "";
	$salario = "";
	$matricula = "";
	$status_emprestimo = "";
	$modalidade = "";
	
	$valor_desejado = "";
	$qtde_parcela_desejada = "";
	$valor_aprovado = "";
	$valor_parcelas_aprovada = "";
	$qtde_parcelas_aprovada = "";
	
	$observacoes = "";
	$empregador = "";
	$cnpj_empregador = "";
	$endereco_empregador = "";
	$bairro_empregador = "";
	$cidade_empregador = "";
	$uf_empregador = "";
	$tel_empregador = "";
	
	$status_emprestimo_n = 0;

		
	$rs = $con->prepare("SELECT
							c.id,
							c.nome,
							c.cpf,
							date_format(c.data_nascimento,'%d/%m/%Y') dt_nascimento,
							c.rg,
							date_format(c.data_expedicao,'%d/%m/%Y') dt_expedicao,
							c.orgao_expedidor,
							c.nome_pai,
							c.nome_mae,
							c.naturalidade,
							e.descricao estado_civil,
							s.nome sexo,
							c.cep,
							c.endereco,
							c.numero,
							c.complemento,
							c.bairro,
							c.cidade,
							c.uf, 
							c.telefone,
							c.celular,
							c.email,
							emp.razao_social nome_empregador,
							emp.cnpj cnpj_empregador,
							emp.endereco endereco_empregador,
							emp.bairro bairro_empregador,
							emp.cidade cidade_empregador,
							emp.uf uf_empregador,
							emp.telefone tel_empregador,
							c.profissao,
							date_format(c.data_admissao,'%d/%m/%Y') dt_admissao,
							c.salario,
							c.matricula,
							c.status_emprestimo,
							c.modalidade,
							c.valor_desejado,
							c.qtde_parcela_desejada,
							c.valor_aprovado,
							c.valor_parcelas_aprovada,
							c.qtde_parcelas_aprovada,
							c.observacoes
					FROM
							cliente c
							left join estado_civil e on e.id = c.id_estado_civil
							left join sexo s on s.id = c.id_sexo
							left join empregador emp on emp.id = c.id_empregador
					where  
							c.id = ? and
							c.id_empresa = ".$_SESSION['empresa']);
	$rs->bindParam(1, $_REQUEST['cod_solicitacao']);
	$rs->execute();
	$row = $rs->fetch(PDO::FETCH_OBJ);
	
	if($row->status_emprestimo == '1'){
		$status_class = "label-info";
		$status_emprestimo = "Pendente";
		$status_emprestimo_n = 1;
	}elseif($row->status_emprestimo == '2'){
		$status_class = "label-warning";
		$status_emprestimo = "Em Análise";
		$status_emprestimo_n = 2;
	}elseif($row->status_emprestimo == '3'){
		$status_class = "label-success";
		$status_emprestimo = "Aprovado";
		$status_emprestimo_n = 3;
	}else{
		$status_class = "label-danger";
		$status_emprestimo = "Negado";
		$status_emprestimo_n = 4;
	}
	
	$id_cliente = $row->id;
	$nome = $row->nome;
	$cpf = formatCnpjCpf(str_pad($row->cpf,11,"0",STR_PAD_LEFT));
	$data_nascimento = $row->dt_nascimento;
	$rg = $row->rg;
	$data_expedicao = $row->dt_expedicao;
	$orgao_expedidor = $row->orgao_expedidor;
	$nome_pai = $row->nome_pai;
	$nome_mae = $row->nome_mae;
	$naturalidade = $row->naturalidade;
	$estado_civil = $row->estado_civil;
	$sexo = $row->sexo;
	$cep = $row->cep;
	$endereco = $row->endereco;
	$numero = $row->numero;
	$complemento = $row->complemento;
	$bairro = $row->bairro;
	$cidade = $row->cidade;
	$uf = $row->uf;
	$telefone = $row->telefone;
	$celular = $row->celular;
	$email = $row->email;
	$empregador = $row->nome_empregador;
	$cnpj_empregador = $row->cnpj_empregador;
	$endereco_empregador = $row->endereco_empregador;
	$bairro_empregador = $row->bairro_empregador;
	$cidade_empregador = $row->cidade_empregador;
	$uf_empregador = $row->uf_empregador;
	$tel_empregador = $row->tel_empregador;
	$profissao = $row->profissao;
	$data_admissao= $row->dt_admissao;
	$salario = number_format($row->salario, 2, ',', '.');
	$matricula = $row->matricula;
	$modalidade = $row->modalidade;
	$valor_desejado = number_format($row->valor_desejado, 2, ',', '.');
	$qtde_parcela_desejada = $row->qtde_parcela_desejada;
	$valor_aprovado = number_format($row->valor_aprovado, 2, ',', '.');
	$valor_parcelas_aprovada = number_format($row->valor_parcelas_aprovada, 2, ',', '.');
	$qtde_parcelas_aprovada = $row->qtde_parcelas_aprovada;
	$observacoes = $row->observacoes;
	
	$rs_ref = $con->prepare("select id,
							nome,
							telefone,
							observacao
						from
							referencia
						where
							id_cliente = ?");
	$rs_ref->bindParam(1, $id_cliente);
	$rs_ref->execute();
	
	$rs_conta = $con->prepare("select id,
							tipo,
							banco,
							conta,
							dv_conta,
							agencia,
							dv_agencia,
							date_format(data_abertura,'%d/%m/%Y') dt_abertura,
							observacao
						from
							conta_bancaria
						where
							id_cliente = ?");
	$rs_conta->bindParam(1, $id_cliente);
	$rs_conta->execute();

?>
<style>
.controls {
  text-align: left;
  position: relative;
}

.controls input[type="text"],
.controls input[type="email"],
.controls input[type="number"],
.controls input[type="date"],
.controls input[type="tel"],
.controls textarea,
.controls button,
.controls select {
  padding: 10px;
  border: 1px solid #85c87f;
  width: 100%;
  margin-bottom: 12px;
  margin-top: 12px;
  font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;
  font-weight: 400;
  height: 35px;
  -moz-border-radius: 1px;
  -webkit-border-radius: 1px;
  border-radius: 1px;
  -moz-transition: all 0.3s;
  -o-transition: all 0.3s;
  -webkit-transition: all 0.3s;
  transition: all 0.3s;
}
.controls input[type="text"]:focus, .controls input[type="text"]:hover,
.controls input[type="email"]:focus,
.controls input[type="email"]:hover,
.controls input[type="number"]:focus,
.controls input[type="number"]:hover,
.controls input[type="date"]:focus,
.controls input[type="date"]:hover,
.controls input[type="tel"]:focus,
.controls input[type="tel"]:hover,
.controls textarea:focus,
.controls textarea:hover,
.controls button:focus,
.controls button:hover,
.controls select:focus,
.controls select:hover {
  outline: none;
  border-color: #9FB1C1;
}

.controls input[type="text"]:focus + label, .controls input[type="text"]:hover + label,
.controls input[type="email"]:focus + label,
.controls input[type="email"]:hover + label,
.controls input[type="number"]:focus + label,
.controls input[type="number"]:hover + label,
.controls input[type="date"]:focus + label,
.controls input[type="date"]:hover + label,
.controls input[type="tel"]:focus + label,
.controls input[type="tel"]:hover + label,
.controls textarea:focus + label,
.controls textarea:hover + label,
.controls button:focus + label,
.controls button:hover + label,
.controls select:focus + label,
.controls select:hover + label {
  margin-top: 12px;
  color: #bdcc00;
  cursor: text;
}
.controls .fa-sort {
  position: absolute;
  right: 10px;
  top: 17px;
  color: #999;
}
.controls select {
  -moz-appearance: none;
  -webkit-appearance: none;
  cursor: pointer;
}
.controls label {
  position: absolute;
  left: 25px;
  top: 12px;
  margin-top: 12px;
  width: 60%;
  color: #999;
  font-size: 12px;
  display: inline-block;
  padding: 2px 10px;
  font-weight: 400;
  
  -moz-transition: color 0.3s, top 0.3s, background-color 0.8s;
  -o-transition: color 0.3s, top 0.3s, background-color 0.8s;
  -webkit-transition: color 0.3s, top 0.3s, background-color 0.8s;
  transition: color 0.3s, top 0.3s, background-color 0.8s;
  background-color:#f7f7f7;
}

.controls label.active {
  top: -15px;
  color: #555;
  background-color: white;
  width: auto;
}

.controls textarea {
  resize: none;
  height: 200px;
}

input[type="text"]:disabled {
  background-color: rgb(170, 170, 170,0.3);
  disabled: disabled;
}

fieldset {
    border:1px solid #999;
    padding: 5px; /* aqui vc controla a distancia entre os elementos e a borda */
    margin: 5px; /* essa margem é para alinhar o fieldset com o restante do grid */
	background-color:#f7f7f7;
}
legend {
    display: inline;
    width: auto;
    border: 0;
    margin-bottom: 0px;
	padding: 3px;
	font-size: 15px;
	font-style:oblique;
	font-weight: bold;
}

</style>

<div class="box box-default">
    <div class="box-header with-border col-lg-10">
        <h3 class="box-title">Solicitações</h3>
    </div>
    <div class="box-header with-border col-lg-2">
        <span class='label <?php echo $status_class?>'><?php echo $status_emprestimo?></span>
    </div>

    <ul class="nav nav-tabs" id="myTab" role="tablist" style="padding-top: 50px;">
      <li class="nav-item">
        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home-tab" aria-selected="true">Dados</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#perfil" role="tab" aria-controls="profile-tab" aria-selected="false">Imagens</a>
      </li>
    </ul>
    <div class="tab-content" id="myTabContent">
    	<div class="tab-pane fade active" id="home" role="tabpanel" aria-labelledby="home-tab">
        	<div class="box-body">
            	<fieldset class="scheduler-border">
                	<legend class="scheduler-border"><li class="fa fa-check-square-o"></li> Dados Pessoais</legend>
                    <div class="form-group">
                        <div class="controls col-lg-8">
                            <input type="text" id="nome" class="floatLabel" name="nome" value="<?php echo $nome?>">
                            <label for="nome">Nome</label>
                        </div>
                        <div class="controls col-lg-4">
                            <input type="text" id="cpf" class="floatLabel" name="cpf" value="<?php echo $cpf?>">
                            <label for="cpf">CPF</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="controls col-lg-3">
                            <input type="text" id="rg" class="floatLabel" name="rg" value="<?php echo $rg?>">
                            <label for="rg">RG</label>
                        </div>
                        <div class="controls col-lg-3">
                            <input type="text" id="dt_expedicao" class="floatLabel" name="dt_expedicao" value="<?php echo $data_expedicao?>">
                            <label for="dt_expedicao">Data da Expedição</label>
                        </div>
                        <div class="controls col-lg-3">
                            <input type="text" id="orgao_expeditor" class="floatLabel" name="orgao_expeditor" value="<?php echo $orgao_expedidor?>">
                            <label for="orgao_expeditor">Orgão Expeditor/UF</label>
                        </div>
                        <div class="controls col-lg-3">
                            <input type="text" id="dt_nascimento" class="floatLabel" name="dt_nascimento" value="<?php echo $data_nascimento?>">
                            <label for="dt_nascimento">Data de Nascimento</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="controls col-lg-6">
                            <input type="text" id="nome_pai" class="floatLabel" name="nome_pai" value="<?php echo $nome_pai?>">
                            <label for="nome_pai">Nome do Pai</label>
                        </div>
                        <div class="controls col-lg-6">
                            <input type="text" id="nome_mae" class="floatLabel" name="nome_mae" value="<?php echo $nome_mae?>">
                            <label for="nome_mae">Nome da Mãe</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="controls col-lg-4">
                            <input type="text" id="natural" class="floatLabel" name="natural" value="<?php echo $naturalidade?>">
                            <label for="natural">Naturalidade</label>
                        </div>
                        <div class="controls col-lg-4">
                            <input type="text" id="estado_civil" class="floatLabel" name="estado_civil" value="<?php echo $estado_civil?>">
                            <label for="estado_civil">Estado Civil</label>
                        </div>
                        <div class="controls col-lg-4">
                            <input type="text" id="sexo" class="floatLabel" name="sexo" value="<?php echo $sexo?>">
                            <label for="sexo">Sexo</label>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border"><li class="fa fa-check-square-o"></li> Endereço</legend>
                    <div class="form-group">
                        <div class="controls col-lg-6">
                            <input type="text" id="endereco" class="floatLabel" name="endereco" value="<?php echo $endereco?>">
                            <label for="endereco">Endereço</label>
                        </div>
                        <div class="controls col-lg-2">
                            <input type="text" id="numero" class="floatLabel" name="numero" value="<?php echo $numero?>">
                            <label for="numero">Número</label>
                        </div>
                        <div class="controls col-lg-4">
                            <input type="text" id="complemento" class="floatLabel" name="complemento" value="<?php echo $complemento?>">
                            <label for="complemento">Complemento</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="controls col-lg-6">
                            <input type="text" id="bairro" class="floatLabel" name="bairro" value="<?php echo $bairro?>">
                            <label for="bairro">Bairro</label>
                        </div>
                        <div class="controls col-lg-2">
                            <input type="text" id="cep" class="floatLabel" name="cep" value="<?php echo $cep?>">
                            <label for="cep">CEP</label>
                        </div>
                        <div class="controls col-lg-4">
                            <input type="text" id="cidade_uf" class="floatLabel" name="cidade_uf" value="<?php echo $cidade."/".$uf;?>">
                            <label for="cidade_uf">Cidade/UF</label>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="scheduler-border">
                     <legend class="scheduler-border"><li class="fa fa-check-square-o"></li> Contato</legend>
                    <div class="form-group">
                        <div class="controls col-lg-3">
                            <input type="text" id="telefone" class="floatLabel" name="telefone" value="<?php echo $telefone?>">
                            <label for="telefone">Telefone</label>
                        </div>
                        <div class="controls col-lg-3">
                            <input type="text" id="celular" class="floatLabel" name="celular" value="<?php echo $celular?>">
                            <label for="celular">Celular</label>
                        </div>
                        <div class="controls col-lg-6">
                            <input type="text" id="email" class="floatLabel" name="email" value="<?php echo $email?>">
                            <label for="email">Email</label>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="scheduler-border">
                        <legend class="scheduler-border"><li class="fa fa-check-square-o"></li> Dados Trabalhistas</legend>
                        <div class="form-group">
                            <div class="controls col-lg-3">
                                <input type="text" id="profissao" class="floatLabel" name="profissao" value="<?php echo $profissao?>">
                                <label for="profissao">Profissão/Cargo</label>
                            </div>
                            <div class="controls col-lg-3">
                                <input type="text" id="dt_admissao" class="floatLabel" name="dt_admissao" value="<?php echo $data_admissao?>">
                                <label for="dt_admissao">Data de Admissão</label>
                            </div>
                            <div class="controls col-lg-3">
                                <input type="text" id="salario" class="floatLabel" name="salario" value="<?php echo $salario?>">
                                <label for="salario">Renda/Salário (R$)</label>
                            </div>
                            <div class="controls col-lg-3">
                                <input type="text" id="matricula" class="floatLabel" name="matricula" value="<?php echo $matricula?>">
                                <label for="matricula">Benefício/Matrícula</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="controls col-lg-6">
                                <input type="text" id="empregador" class="floatLabel" name="empregador" value="<?php echo $empregador?>">
                                <label for="empregador">Empregador</label>
                            </div>
                            <div class="controls col-lg-3">
                                <input type="text" id="cnpj_empregador" class="floatLabel" name="cnpj_empregador" value="<?php echo $cnpj_empregador?>">
                                <label for="cnpj_empregador">CNPJ</label>
                            </div>
                            <div class="controls col-lg-3">
                                <input type="text" id="telefone_empregador" class="floatLabel" name="telefone_empregador" value="<?php echo $tel_empregador?>">
                                <label for="telefone_empregador">Telefone</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="controls col-lg-5">
                                <input type="text" id="ende_empregador" class="floatLabel" name="ende_empregador" value="<?php echo $endereco_empregador?>">
                                <label for="ende_empregador">Endereço</label>
                            </div>
                            <div class="controls col-lg-4">
                                <input type="text" id="bairro_empregador" class="floatLabel" name="bairro_empregador" value="<?php echo $bairro_empregador?>">
                                <label for="bairro_empregador">Bairro</label>
                            </div>
                            <div class="controls col-lg-3">
                                <input type="text" id="cidade_uf_empregador" class="floatLabel" name="cidade_uf_empregador" value="<?php echo $cidade_empregador."/".$uf_empregador;?>">
                                <label for="cidade_uf_empregador">Cidade/UF</label>
                            </div>
                        </div>
                </fieldset>
                <?php while($row_ref = $rs_ref->fetch(PDO::FETCH_OBJ)){?>
                <fieldset class="scheduler-border">
                        <legend class="scheduler-border"><li class="fa fa-check-square-o"></li> Referências</legend>
                        <div class="form-group">
                            <div class="controls col-lg-5">
                                <input type="text" id="nome_ref<?php echo $row_ref->id?>" class="floatLabel" name="nome_ref<?php echo $row_ref->id?>" value="<?php echo $row_ref->nome?>">
                                <label for="nome_ref<?php echo $row_ref->id?>">Referência</label>
                            </div>
                            <div class="controls col-lg-4">
                                <input type="text" id="tel_ref<?php echo $row_ref->id?>" class="floatLabel" name="tel_ref<?php echo $row_ref->id?>" value="<?php echo $row_ref->telefone?>">
                                <label for="tel_ref<?php echo $row_ref->id?>">Telefone</label>
                            </div>
                            <div class="controls col-lg-3">
                                <input type="text" id="obs_ref<?php echo $row_ref->id?>" class="floatLabel" name="obs_ref<?php echo $row_ref->id?>" value="<?php echo $row_ref->observacao?>">
                                <label for="obs_ref<?php echo $row_ref->id?>">Observacao</label>
                            </div>
                        </div>
                </fieldset>
                <?php }?>
                <fieldset class="scheduler-border">
                        <legend class="scheduler-border"><li class="fa fa-check-square-o"></li> Dados de Operação</legend>
                        <div class="form-group">
                            <div class="controls col-lg-5">
                                <input type="text" id="modalidade" class="floatLabel" name="modalidade" value="<?php echo $modalidade?>">
                                <label for="modalidade">Modalidade</label>
                            </div>
                            <div class="controls col-lg-4">
                                <input type="text" id="vl_desejado" class="floatLabel" name="vl_desejado" value="<?php echo $valor_desejado?>">
                                <label for="vl_desejado">Vl. Desejado</label>
                            </div>
                            <div class="controls col-lg-3">
                                <input type="text" id="qtd_parcelas_desejado" class="floatLabel" name="qtd_parcelas_desejado" value="<?php echo $qtde_parcela_desejada;?>">
                                <label for="qtd_parcelas_desejado">Qtd. de Parcelas Desejado</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="controls col-lg-12">
                                <input type="text" id="observacao" class="floatLabel" name="observacao" value="<?php echo $observacoes?>">
                                <label for="observacao">Observação</label>
                            </div>
                        </div>
                </fieldset>
                <?php while($row_conta = $rs_conta->fetch(PDO::FETCH_OBJ)){?>
                <fieldset class="scheduler-border">
                        <legend class="scheduler-border"><li class="fa fa-check-square-o"></li> Dados Bancários</legend>
                        <div class="form-group">
                            <div class="controls col-lg-3">
                                <input type="text" id="banco<?php echo $row_conta->id?>" class="floatLabel" name="banco<?php echo $row_conta->id?>" value="<?php echo $row_conta->banco?>">
                                <label for="banco<?php echo $row_conta->id?>">Banco</label>
                            </div>
                            <div class="controls col-lg-2">
                                <input type="text" id="agencia<?php echo $row_conta->id?>" class="floatLabel" name="agencia<?php echo $row_conta->id?>" value="<?php echo $row_conta->agencia?>">
                                <label for="agencia<?php echo $row_conta->id?>">Agência</label>
                            </div>
                            <div class="controls col-lg-1">
                                <input type="text" id="digito_agencia<?php echo $row_conta->id?>" class="floatLabel" name="digito_agencia<?php echo $row_conta->id?>" value="<?php echo $row_conta->dv_agencia?>">
                                <label for="digito_agencia<?php echo $row_conta->id?>">Dígito</label>
                            </div>
                            <div class="controls col-lg-3">
                                <input type="text" id="numero_conta<?php echo $row_conta->id?>" class="floatLabel" name="numero_conta<?php echo $row_conta->id?>" value="<?php echo $row_conta->conta?>">
                                <label for="numero_conta<?php echo $row_conta->id?>">Conta</label>
                            </div>
                            <div class="controls col-lg-1">
                                <input type="text" id="digito_conta<?php echo $row_conta->id?>" class="floatLabel" name="digito_conta<?php echo $row_conta->id?>" value="<?php echo $row_conta->dv_conta?>">
                                <label for="digito_conta<?php echo $row_conta->id?>">Dígito</label>
                            </div>
                            <div class="controls col-lg-2">
                                <input type="text" id="dt_abertura<?php echo $row_conta->id?>" class="floatLabel" name="dt_abertura<?php echo $row_conta->id?>" value="<?php echo $row_conta->dt_abertura?>">
                                <label for="dt_abertura<?php echo $row_conta->id?>">Data de Abertura</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="controls col-lg-4">
                                <input type="text" id="tipo_conta<?php echo $row_conta->id?>" class="floatLabel" name="tipo_conta<?php echo $row_conta->id?>" value="<?php echo $row_conta->tipo?>">
                                <label for="tipo_conta<?php echo $row_conta->id?>">Tipo Conta</label>
                            </div>
                            <div class="controls col-lg-8">
                                <input type="text" id="observacao_conta<?php echo $row_conta->id?>" class="floatLabel" name="observacao_conta<?php echo $row_conta->id?>" value="<?php echo $row_conta->observacao?>">
                                <label for="observacao_conta<?php echo $row_conta->id?>">Observação</label>
                            </div>
                        </div>
                </fieldset>
                <?php }?>
                <fieldset class="scheduler-border">
                      <legend class="scheduler-border"><li class="fa fa-check-square-o"></li> Ocorrências</legend>
                      <div class="form-group col-lg-12">
                          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal" <?php echo ($status_emprestimo_n == 3 ? "disabled='disabled'" : "")?>>Nova Ocorrência</button>
                      </div>
                      
                      <div class="col-xs-12">
                          <div class="box-body table-responsive" id="lista_ocorrencias" style="background-color:#FFF;">
                            <table id="grid" class="table table-bordered table-striped table-hover">
                              <thead>
                              <tr>
                                <th>Descrição</th>
                                <th>Data</th>
                                <th>Usuário</th>
                                <th>Status</th>
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
                        
                </fieldset>
			</div>
         </div>
       	 <div class="tab-pane fade" id="perfil" role="tabpanel" aria-labelledby="profile-tab">
         	
               
               <div class="box-body table-responsive" id="lista_imagens" style="background-color:#FFF;">
                <table id="grid_img" class="table table-bordered table-striped table-hover">
                  <thead>
                  <tr>
                    <th>Descrição</th>
                    <th>Data</th>
                    <th>Usuário</th>
                    <th>Status</th>
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


<!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Inserir Ocorrência</h4>
        </div>
        <div class="modal-body">
          
          <form role="form" name="form" id="form">
              <div class="box-body">
                <div class="form-group col-lg-12 col-xs-12">
                  <label for="nome">Ocorrência*</label>
                  <textarea type="text" class="form-control" name="ocorrencia" id="ocorrencia" maxlength="300" rows="3" minlength="10"></textarea>
                  <input type="hidden" name="cod_cliente" id="cod_cliente" value="<?php echo $id_cliente?>">
                </div>
                <div class="form-group col-lg-3 col-xs-3">
                  <label>Status*</label>
                  <select class="form-control" name="status" id="status">
                  	<option value="0"></option>
                    <option value="2">Em Análise</option>
                    <option value="3">Aprovado</option>
                    <option value="4">Negado</option>
                  </select>
                </div>
                
                <div class="form-group col-lg-3">
                  <label for="valor_aprovado">Valor Aprovado</label>
                  <input type="text" class="form-control" id="valor_aprovado" placeholder="Valor Aprovado" maxlength="13" name="valor_aprovado">
                </div>
                <div class="form-group col-lg-3">
                  <label for="qtd_parcelas">Qtd de Parcelas</label>
                  <input type="text" class="form-control" id="qtd_parcelas" placeholder="Qtd. de Parcelas" maxlength="3" name="qtd_parcelas">
                </div>
                <div class="form-group col-lg-3">
                  <label for="valor_parcela">Valor da Parcela</label>
                  <input type="text" class="form-control" id="valor_parcela" placeholder="Valor da Parcela" maxlength="13" name="valor_parcela">
                </div>
              </div>
           </form>
          
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary"  name="salvar" id="salvar">Salvar</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        </div>
      </div>
      
    </div>
  </div>
<script src="js/funcoes.js"></script>
<script src="js/mask.js"></script>
<script>

$(document).ready( function(){
	
	(function ($) {
	  function floatLabel(inputType) {
		$(inputType).each(function () {
		  var $this = $(this);
		    $this.prop('disabled', true);
			$this.next().addClass("active");
		});
	  }
	  floatLabel(".floatLabel");
	})(jQuery);
	
	$('#valor_aprovado').prop('disabled', true);
	$('#qtd_parcelas').prop('disabled', true);
	$('#valor_parcela').prop('disabled', true);
	
	$("#valor_parcela").mask('#.##0,00', {
	  reverse: true
	});
	
	$("#valor_aprovado").mask('#.##0,00', {
	  reverse: true
	});
	
	
	$('#salvar').click(function() {
		$(gravar_ocorrencia);
	 });
	 
	 $('#status').change(function() {
	   var vstatus = $("option:selected", this).val();
	   if(vstatus == 3){
		   $('#valor_aprovado').prop('disabled', false);
			$('#qtd_parcelas').prop('disabled', false);
			$('#valor_parcela').prop('disabled', false);
	   }else{
		   $('#valor_aprovado').val("");
			$('#qtd_parcelas').val("");
			$('#valor_parcela').val("");
		   
		   $('#valor_aprovado').prop('disabled', true);
			$('#qtd_parcelas').prop('disabled', true);
			$('#valor_parcela').prop('disabled', true);
	   }
   });
   
   $('#myModal').on('hidden.bs.modal', function (e) {
		    $('#valor_aprovado').val("");
			$('#qtd_parcelas').val("");
			$('#valor_parcela').val("");
			$('#ocorrencia').val("");
			$('#status').val(0);
		   
		   $('#valor_aprovado').prop('disabled', true);
			$('#qtd_parcelas').prop('disabled', true);
			$('#valor_parcela').prop('disabled', true);
			
			$(busca_ocorrencia);
	})
	
	$(busca_ocorrencia);
	
	$(busca_imagens);
	
	$("#home-tab").tab('show')

});
</script>