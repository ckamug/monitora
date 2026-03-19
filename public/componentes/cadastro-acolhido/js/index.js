var NIS_TAMANHO = 11;

$(document).ready(function(){

	carregaMunicipios(0);
	carregaAcolhido($("#hidIdAcolhido").val());
	listaContatosReferencia();

	$("#txtCep").blur(function() {consultaCep()});
	$('#txtDataNascimento').mask('00/00/0000');
	$('#txtNis').mask('00000000000');
	$('#txtCpf').mask('000.000.000-00');
	normalizaCampoNis("#txtNis");

	//linha que chama a função de consulta do cpf ao sair do campo
	$(document).on("blur", "#txtCpf", function() {consultaCpfAcolhido()});
	$(document).on("input", "#txtNis", function() {
		normalizaCampoNis(this);
		if(nisValidoOuVazio($(this).val())){
			limpaErroNis(this);
		}
	});
	$(document).on("blur", "#txtNis", function() {
		if(validarCampoNis(this)){
			consultaCpfAcolhido();
		}
	});

	$('#txtTelefonePessoal').mask('(00)00000-0000');
	$('#txtTelefoneResidencial').mask('(00)0000-0000');
	$('#txtTelefoneReferencia').mask('(00)00000-0000');
	$('#txtCep').mask('00000-000');

	$("#txtValorRecebido").maskMoney({
		prefix: "R$ ",
		decimal: ",",
		thousands: "."
	});

	$("#formAcolhido").validate({
		invalidHandler: function() {
			alert('Preencha todos os campos obrigatórios');
		},
		submitHandler: function(){
			if(!validarCampoNis("#txtNis", true)){
				return;
			}

			var idAcolhidoAtual = getIdAcolhidoAtual();

			if(idAcolhidoAtual !== null){
				editaAcolhido(idAcolhidoAtual);
				return;
			}

			alert("Informações registradas com sucesso");
			cadastraAcolhido();
		},
		rules:{
			txtNomeCompleto:{
			  required:true,
			},
			txtDataNascimento:{
			  required:true,
			},
			
		  },
  
	  }

	);

	/* #imagem é o id do input, ao alterar o conteudo do input execurará a função baixo */
	$('#documentos , #avaliacoes , #exames').on('change',function(){

		switch(this.name){
			case 'documentos':
				$("#visualizar_documentos").html('<button class="btn btn-primary" type="button" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Carregando...</button>');
				var form = $("#formDocumentos")[0];
			break;
			case 'avaliacoes':
				$("#visualizar_avaliacoes").html('<button class="btn btn-primary" type="button" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Carregando...</button>');
				var form = $("#formAvaliacoes")[0];
			break;
			case 'exames':
				$("#visualizar_exames").html('<button class="btn btn-primary" type="button" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Carregando...</button>');
				var form = $("#formExames")[0];
			break;
		}
		
		var file = this.files[0];
	
		var data = new FormData(form);
		data.append(this.name,file);
		data.append('id',$("#hidIdAcolhido").val());
		data.append('quadro',this.name);
	
		$.ajax({
		  type: "POST",
		  enctype: 'multipart/form-data',
		  url: "https://portal.seds.sp.gov.br/coed/public/componentes/cadastro-acolhido/model/upload.php",
		  data: data,
		  processData: false,
		  cache: false,
		  contentType: false,
		  success: function () {
			$("#arquivo_"+this.name).val('');
			listaArquivos($("#hidIdAcolhido").val());
		  }
		});

	});

	$('#chkDocPossuo1 , #chkDocPossuo2 , #chkDocPossuo3 , #chkDocPossuo4 , #chkDocPossuo5 , #chkDocPossuo6 , #chkDocPossuo7 , #chkDocPossuo8 , #chkDocPossuo9 , #chkDocPossuo10').on('click',function(){

		switch(this.value){
			case 'RG':
				if(this.checked==true){
					$("#chkDocNecessaria1").prop("disabled",true);
				}
				else{
					$("#chkDocNecessaria1").prop("disabled",false);
				}
			break;
			case 'CPF':
				if(this.checked==true){
					$("#chkDocNecessaria2").prop("disabled",true);
				}
				else{
					$("#chkDocNecessaria2").prop("disabled",false);
				}
			break;
			case 'CNH':
				if(this.checked==true){
					$("#chkDocNecessaria3").prop("disabled",true);
				}
				else{
					$("#chkDocNecessaria3").prop("disabled",false);
				}
			break;
			case 'CTPS':
				if(this.checked==true){
					$("#chkDocNecessaria4").prop("disabled",true);
				}
				else{
					$("#chkDocNecessaria4").prop("disabled",false);
				}
			break;
			case 'Certidão de Nascimento':
				if(this.checked==true){
					$("#chkDocNecessaria5").prop("disabled",true);
				}
				else{
					$("#chkDocNecessaria5").prop("disabled",false);
				}
			break;
			case 'Título de Eleitor':
				if(this.checked==true){
					$("#chkDocNecessaria6").prop("disabled",true);
				}
				else{
					$("#chkDocNecessaria6").prop("disabled",false);
				}
			break;
			case 'Cartão SUS':
				if(this.checked==true){
					$("#chkDocNecessaria7").prop("disabled",true);
				}
				else{
					$("#chkDocNecessaria7").prop("disabled",false);
				}
			break;
			case 'Reservista':
				if(this.checked==true){
					$("#chkDocNecessaria8").prop("disabled",true);
				}
				else{
					$("#chkDocNecessaria8").prop("disabled",false);
				}
			break;
			case 'CadUnico':
				if(this.checked==true){
					$("#chkDocNecessaria9").prop("disabled",true);
				}
				else{
					$("#chkDocNecessaria9").prop("disabled",false);
				}
			break;
			case 'Outros':
				if(this.checked==true){
					$("#boxOutrosDocPossuo").removeClass("d-none");
				}
				else{
					$("#boxOutrosDocPossuo").addClass("d-none");
					$("#txtOutrosDocPossuo").val("");
				}
			break;

		}

	});

	$('#chkDocPossuo1 , #chkDocPossuo2 , #chkDocPossuo3 , #chkDocPossuo4 , #chkDocPossuo5 , #chkDocPossuo6 , #chkDocPossuo7 , #chkDocPossuo8 , #chkDocPossuo9 , #chkDocPossuo10').on('click',function(){

		switch(this.value){
			case 'RG':
				if(this.checked==true){
					$("#chkDocNecessaria1").prop("disabled",true);
				}
				else{
					$("#chkDocNecessaria1").prop("disabled",false);
				}
			break;
			case 'CPF':
				if(this.checked==true){
					$("#chkDocNecessaria2").prop("disabled",true);
				}
				else{
					$("#chkDocNecessaria2").prop("disabled",false);
				}
			break;
			case 'CNH':
				if(this.checked==true){
					$("#chkDocNecessaria3").prop("disabled",true);
				}
				else{
					$("#chkDocNecessaria3").prop("disabled",false);
				}
			break;
			case 'CTPS':
				if(this.checked==true){
					$("#chkDocNecessaria4").prop("disabled",true);
				}
				else{
					$("#chkDocNecessaria4").prop("disabled",false);
				}
			break;
			case 'Certidão de Nascimento':
				if(this.checked==true){
					$("#chkDocNecessaria5").prop("disabled",true);
				}
				else{
					$("#chkDocNecessaria5").prop("disabled",false);
				}
			break;
			case 'Título de Eleitor':
				if(this.checked==true){
					$("#chkDocNecessaria6").prop("disabled",true);
				}
				else{
					$("#chkDocNecessaria6").prop("disabled",false);
				}
			break;
			case 'Cartão SUS':
				if(this.checked==true){
					$("#chkDocNecessaria7").prop("disabled",true);
				}
				else{
					$("#chkDocNecessaria7").prop("disabled",false);
				}
			break;
			case 'Reservista':
				if(this.checked==true){
					$("#chkDocNecessaria8").prop("disabled",true);
				}
				else{
					$("#chkDocNecessaria8").prop("disabled",false);
				}
			break;
			case 'CadUnico':
				if(this.checked==true){
					$("#chkDocNecessaria9").prop("disabled",true);
				}
				else{
					$("#chkDocNecessaria9").prop("disabled",false);
				}
			break;
			case 'Outros':
				if(this.checked==true){
					$("#boxOutrosDocPossuo").removeClass("d-none");
				}
				else{
					$("#boxOutrosDocPossuo").addClass("d-none");
					$("#txtOutrosDocPossuo").val("");
				}
			break;

		}

	});


	$('#chkDocNecessaria10').on('click',function(){

		if(this.checked==true){
			$("#boxOutrosDocNecessaria").removeClass("d-none");
		}
		else{
			$("#boxOutrosDocNecessaria").addClass("d-none");
			$("#txtOutroDocNecessaria").val("");
		}

	});

	$('#chkBeneficio1 , #chkBeneficio2').on('click',function(){

		if(this.value=="SIM"){
			$("#boxTipoBeneficio").removeClass("d-none");


			$('#chkTipoBeneficio4').on('click',function(){

				if(this.checked==true){
					$("#boxOutroTipoBeneficio").removeClass("d-none");
				}
				else{
					$("#boxOutroTipoBeneficio").addClass("d-none");
					$("#txtOutroTipoBeneficio").val("");
				}
		
			});

		}
		else{
			$("#boxTipoBeneficio").addClass("d-none");
			$("#boxOutroTipoBeneficio").addClass("d-none");
			$("#txtOutroTipoBeneficio").val("");
			$("#chkTipoBeneficio1").prop("checked",false);
			$("#chkTipoBeneficio2").prop("checked",false);
			$("#chkTipoBeneficio3").prop("checked",false);
			$("#chkTipoBeneficio4").prop("checked",false);
			$("#chkTipoBeneficio5").prop("checked",false);
			$("#chkTipoBeneficio6").prop("checked",false);
			$("#chkTipoBeneficio7").prop("checked",false);
		}

	});

	$('#btnCadastraAcolhimento').on('click',function(){

		CadastraAcolhimento();

	});

	$('#chkSubstanciaUtilizou1 , #chkSubstanciaUtilizou2 , #chkSubstanciaUtilizou3 , #chkSubstanciaUtilizou4 , #chkSubstanciaUtilizou5 , #chkSubstanciaUtilizou6 , #chkSubstanciaUtilizou7 , #chkSubstanciaUtilizou8').on('click',function(){

		switch(this.value){
			case 'Álcool':
				if(this.checked==true){
					$("#chkSubstanciaPreferencia1").prop("disabled",false);
				}
				else{
					$("#chkSubstanciaPreferencia1").prop("disabled",true);
				}
			break;
			case 'Maconha':
				if(this.checked==true){
					$("#chkSubstanciaPreferencia2").prop("disabled",false);
				}
				else{
					$("#chkSubstanciaPreferencia2").prop("disabled",true);
				}
			break;
			case 'Cocaína':
				if(this.checked==true){
					$("#chkSubstanciaPreferencia3").prop("disabled",false);
				}
				else{
					$("#chkSubstanciaPreferencia3").prop("disabled",true);
				}
			break;
			case 'Crack':
				if(this.checked==true){
					$("#chkSubstanciaPreferencia4").prop("disabled",false);
				}
				else{
					$("#chkSubstanciaPreferencia4").prop("disabled",true);
				}
			break;
			case 'Êxtase':
				if(this.checked==true){
					$("#chkSubstanciaPreferencia5").prop("disabled",false);
				}
				else{
					$("#chkSubstanciaPreferencia5").prop("disabled",true);
				}
			break;
			case 'Anfetaminas':
				if(this.checked==true){
					$("#chkSubstanciaPreferencia6").prop("disabled",false);
				}
				else{
					$("#chkSubstanciaPreferencia6").prop("disabled",true);
				}
			break;
			case 'LSD':
				if(this.checked==true){
					$("#chkSubstanciaPreferencia7").prop("disabled",false);
				}
				else{
					$("#chkSubstanciaPreferencia7").prop("disabled",true);
				}
			break;
			case 'K':
				if(this.checked==true){
					$("#chkSubstanciaPreferencia8").prop("disabled",false);
				}
				else{
					$("#chkSubstanciaPreferencia8").prop("disabled",true);
				}
			break;

		}

	});

	$('#chkComorbidade11').on('click', function(){
		if(this.checked){
			$("#boxTipoAcompanhamento").show();
		}
		else{
			$("#boxTipoAcompanhamento").hide();
			$("#txtOutraComorbidade").val("");
		}
	});

	$('#chkSubstanciaPreferencia12').on('click', function(){
		if(this.checked){
			$("#boxOutraSubstanciaPreferencia").show();
		}
		else{
			$("#boxOutraSubstanciaPreferencia").hide();
			$("#txtOutraSubstanciaPreferencia").val("");
		}
	});

})

function normalizarNis(valor){
	return String(valor || "").replace(/\D/g, "");
}

function nisValidoOuVazio(valor){
	var nis = normalizarNis(valor);
	return !nis || nis.length === NIS_TAMANHO;
}

function normalizaCampoNis(campo){
	var $campo = $(campo);
	var nis = normalizarNis($campo.val());

	$campo.val(nis);

	return nis;
}

function limpaErroNis(campo){
	var $campo = $(campo);

	$campo.removeClass("is-invalid");

	if($campo.length && $campo[0].setCustomValidity){
		$campo[0].setCustomValidity("");
	}
}

function validarCampoNis(campo, exibirAlerta){
	var $campo = $(campo);
	var nis = normalizaCampoNis($campo);

	if(nisValidoOuVazio(nis)){
		limpaErroNis($campo);
		return true;
	}

	$campo.addClass("is-invalid");

	if($campo.length && $campo[0].setCustomValidity){
		$campo[0].setCustomValidity("Informe o NIS com 11 digitos");
	}

	if(exibirAlerta){
		alert("Informe o NIS com 11 digitos");
	}

	return false;
}

function listaArquivos(id){
	$.ajax({
		type: "POST",
		dataType: 'JSON',
		url: "https://portal.seds.sp.gov.br/coed/public/componentes/cadastro-acolhido/model/listaArquivos.php",
		data: {'id':id},
		success: function (retorno){

			$("#visualizar_documentos").html(retorno.documentos);
			$("#visualizar_avaliacoes").html(retorno.avaliacoes);
			$("#visualizar_exames").html(retorno.exames);

		}
	});
}

function listaSolicitacoesVagas(id){
	$.ajax({
		type: "POST",
		url: "https://portal.seds.sp.gov.br/coed/public/componentes/cadastro-acolhido/model/carregaSolicitacoesVagas.php",
		data: {'id':id},
		success: function (retorno){
			$("#boxStatusVaga").html(retorno);
		}
	});
}


function consultaCpfAcolhido(){
	// var cpf = $("#txtCpf").val();

	// if(!cpf){
	// 	return;
	// }
	var cpf = $("#txtCpf").val().trim();

	if(!validarCampoNis("#txtNis")){
		return;
	}

	var nis = normalizaCampoNis("#txtNis");

	if(!cpf && !nis){
	return;
	}


	$.ajax({
		url: "https://portal.seds.sp.gov.br/coed/public/componentes/cadastro-acolhido/model/consultaCpf.php",
		type: "POST",
		dataType: "JSON",
		//data: {'cpf':cpf},
		data: {
			cpf: cpf,
			nis: nis,
			id: ($("#hidIdAcolhido").val() || "").trim(),
			id_atual: getIdAcolhidoAtual()
		},
		success: function(retorno){
			if(retorno.usuario_existe && retorno.acolhido && retorno.acolhido.acolhido_id){
				var idAtual = getIdAcolhidoAtual();
				var idEncontrado = parseInt(retorno.acolhido.acolhido_id, 10);

				// Em edicao: se o CPF/NIS encontrado for do proprio registro, nao redireciona.
				if(idAtual !== null && idEncontrado === idAtual){
					return;
				}

				alert("Pessoa ja cadastrada");
				var acolhidoIdB64 = btoa(retorno.acolhido.acolhido_id.toString());
				location.href = "/coed/cadastro-acolhido/" + acolhidoIdB64;
			}
		}
	});

}

function getIdAcolhidoAtual(){
	var idRaw = ($("#hidIdAcolhido").val() || "").trim();

	if(!idRaw){
		return null;
	}

	try{
		idRaw = decodeURIComponent(idRaw);
	}
	catch(e){}

	if(/^\d+$/.test(idRaw)){
		return parseInt(idRaw, 10);
	}

	try{
		var decodificado = atob(idRaw);
		if(/^\d+$/.test(decodificado)){
			return parseInt(decodificado, 10);
		}
	}
	catch(e){
		return null;
	}

	return null;
}

function getIdContatoReferenciaAtual(){
	var idAcolhido = ($("#hidIdAcolhido").val() || "").trim();

	if(idAcolhido){
		return idAcolhido;
	}

	return ($("#hidContatoReferenciaTempId").val() || "").trim();
}

function carregaAcolhido(id){
	carregaAcolhimento(id);
	listaArquivos(id);
	listaSolicitacoesVagas(id);
	$.ajax({
		url:'https://portal.seds.sp.gov.br/coed/public/componentes/cadastro-acolhido/model/carregaAcolhido.php',
		dataType: 'JSON',
		type: 'POST',
		data: {'id':id},
		success: function(resultado){

			if(jQuery.isEmptyObject(resultado)==false){

				$("#abaDocumentos").removeClass("d-none");

				var statusVagaAtual = parseInt(resultado.id_status_vaga, 10);
				var podeExibirAcolhimento = (statusVagaAtual === 2) || (statusVagaAtual === 3 && resultado.tem_entrada_ativa === true);
				
				if((resultado.perfil_logado==1 || resultado.local_logado == resultado.executora_id) && podeExibirAcolhimento){
				    $("#abaAcolhimento").removeClass("d-none");
				}
				else{
				    $("#abaAcolhimento").addClass("d-none");
				}
				
				if((resultado.perfil_logado==1 || (resultado.perfil_logado==4 && resultado.local_logado == resultado.executora_id)) && podeExibirAcolhimento){
				    $("#tabAcolhimento").removeClass("d-none");
				    $("#hTipoServico").html(resultado.servico);
				}
				else{
				    $("#tabAcolhimento").addClass("d-none");
				}
				if(resultado.solicitacao_vaga_id > 0){
					$("#status-tab").removeClass("d-none");
					$("#abaStatus").removeClass("d-none");
				}

				if(resultado.solicitacao_ativa === true){
					$("#boxSolicitarVaga").html("");
				}
				else if(resultado.id_status_vaga == 3){
					if(resultado.tem_entrada_ativa === true){
						$("#boxSolicitarVaga").html("");
					}
					else{
						if(resultado.perfil_logado==1 || resultado.perfil_logado == 3){
							$("#boxSolicitarVaga").html('<button type="button" class="btn btn-success btn-lg mb-3" id="btnSolicitarVaga" data-bs-toggle="modal" data-bs-target="#mdlSolicitarVaga"><i class="bi bi-house"></i> Solicitar Vaga</button>');
							$("#btnSolicitarVaga").click(function() {carregaServicos()});
							$("#btnConfirmaSolicitacaoVaga").click(function() {solicitarVaga(resultado.acolhido)});
						}
					}
				}
				else{
					if(resultado.perfil_logado==1 || resultado.perfil_logado == 3){
						$("#boxSolicitarVaga").html('<button type="button" class="btn btn-success btn-lg mb-3" id="btnSolicitarVaga" data-bs-toggle="modal" data-bs-target="#mdlSolicitarVaga"><i class="bi bi-house"></i> Solicitar Vaga</button>');
						$("#btnSolicitarVaga").click(function() {carregaServicos()});
						$("#btnConfirmaSolicitacaoVaga").click(function() {solicitarVaga(resultado.acolhido)});
					}
				}

				$("#txtNomeCompleto").val(resultado.acolhido_nome_completo);
				$("#txtDataNascimento").val(resultado.acolhido_data_nascimento);
				$("#slcSexo").val(resultado.acolhido_sexo);
				$("#txtNomeSocial").val(resultado.acolhido_nome_social);
				$("#slcIdentidadeGenero").val(resultado.acolhido_identidade_genero);
				$("#slcOrientacaoSexual").val(resultado.acolhido_orientacao_sexual);
				$("#txtFiliacao1").val(resultado.acolhido_filiacao1);
				$("#txtFiliacao2").val(resultado.acolhido_filiacao2);
				$("#txtFiliacao3").val(resultado.acolhido_filiacao3);
				$("#slcEstadoCivil").val(resultado.acolhido_estado_civil);
				$("#txtNis").val(normalizarNis(resultado.acolhido_nis));
				$("#txtCpf").val(resultado.acolhido_cpf);
				$("#txtRg").val(resultado.acolhido_rg);
				$("#txtTelefonePessoal").val(resultado.acolhido_telefone_pessoal);
				$("#txtTelefoneResidencial").val(resultado.acolhido_telefone_residencial);
				
				if(resultado.acolhido_primeiro_acolhimento=="SIM"){
					$("#boxQuantasVezes").hide();
					$("#txtReincidencia").val("");
					$("#radAcolhimento1").prop('checked',true);
				}
				else{
					$("#boxQuantasVezes").show();
					$("#radAcolhimento2").prop('checked',true);
					$("#txtReincidencia").val(resultado.acolhido_reincidencia);
				}

				if(resultado.acolhido_endereco_fixo=="SIM"){
					$("#boxEndereco").show();
					$("#radEndereco1").prop('checked',true);
				}
				else{
					$("#boxSituacaoRua").show();
					$("#radEndereco2").prop('checked',true);
				}

				$("#radEndereco").val(resultado.acolhido_endereco_fixo);
				$("#txtEndereco").val(resultado.acolhido_endereco);
				$("#txtNumero").val(resultado.acolhido_numero);
				$("#txtComplemento").val(resultado.acolhido_complemento);
				$("#txtBairro").val(resultado.acolhido_bairro);
				setTimeout('carregaMunicipios('+resultado.cidade_id+')','300');
				$("#txtCep").val(resultado.acolhido_cep);
				$("#slcTempoSituacaoRua").val(resultado.acolhido_tempo_situacao_rua);
				
				// TRATA CHECKBOX DE COMORBIDADES
				if (resultado.acolhido_comorbidade.includes('Pressão Alta')){
					$("#chkComorbidade1").prop('checked',true);
				}
				if (resultado.acolhido_comorbidade.includes('Colesterol')){
					$("#chkComorbidade2").prop('checked',true);
				}
				if (resultado.acolhido_comorbidade.includes('Tuberculose')){
					$("#chkComorbidade3").prop('checked',true);
				}
				if (resultado.acolhido_comorbidade.includes('Sífilis')){
					$("#chkComorbidade4").prop('checked',true);
				}
				if (resultado.acolhido_comorbidade.includes('Doenças Cardiovasculares')){
					$("#chkComorbidade5").prop('checked',true);
				}
				if (resultado.acolhido_comorbidade.includes('Epilepsia')){
					$("#chkComorbidade6").prop('checked',true);
				}
				if (resultado.acolhido_comorbidade.includes('Diabetes')){
					$("#chkComorbidade7").prop('checked',true);
				}
				if (resultado.acolhido_comorbidade.includes('Hepatite (B/C)')){
					$("#chkComorbidade8").prop('checked',true);
				}
				if (resultado.acolhido_comorbidade.includes('HIV')){
					$("#chkComorbidade9").prop('checked',true);
				}
				if (resultado.acolhido_comorbidade.includes('Cirrose')){
					$("#chkComorbidade10").prop('checked',true);
				}
				if (resultado.acolhido_comorbidade.includes('Outra')){
					$("#chkComorbidade11").prop('checked',true);
					$("#boxTipoAcompanhamento").show();
					$("#txtOutraComorbidade").val(resultado.acolhido_outra_comorbidade);
				}
				else{
					$("#boxTipoAcompanhamento").hide();
					$("#txtOutraComorbidade").val("");
				}
				if (resultado.acolhido_comorbidade.includes('Não')){
					$("#chkComorbidade12").prop('checked',true);
				}

				$("#radDeficiencia").val(resultado.acolhido_deficiencia);

				if(resultado.acolhido_deficiencia=="SIM"){
					$("#boxDeficiencia").show();
					$("#radDeficiencia1").prop('checked',true);

					// TRATA CHECKBOX DE DEFICIÊNCIA FÍSICA
					if (resultado.acolhido_deficiencia_fisica.includes('Cegueira')){
						$("#chkDeficiencia1").prop('checked',true);
					}
					if (resultado.acolhido_deficiencia_fisica.includes('Baixa visão')){
						$("#chkDeficiencia2").prop('checked',true);
					}
					if (resultado.acolhido_deficiencia_fisica.includes('Surdez Severa/profunda')){
						$("#chkDeficiencia3").prop('checked',true);
					}
					if (resultado.acolhido_deficiencia_fisica.includes('Surdez leve/moderada')){
						$("#chkDeficiencia4").prop('checked',true);
					}
					if (resultado.acolhido_deficiencia_fisica.includes('Deficiência Física')){
						$("#chkDeficiencia5").prop('checked',true);
					}
					if (resultado.acolhido_deficiencia_fisica.includes('Síndrome de Down')){
						$("#chkDeficiencia6").prop('checked',true);
					}
					if (resultado.acolhido_deficiencia_fisica.includes('Transtorno/doença mental')){
						$("#chkDeficiencia7").prop('checked',true);
					}
					if (resultado.acolhido_deficiencia_fisica.includes('Deficiência Mental ou intelectual')){
						$("#chkDeficiencia8").prop('checked',true);
					}
				}
				else{
					$("#radDeficiencia2").prop('checked',true);
				}

				// TRATA CHECKBOX DE SUBTÂNCIAS QUE JÁ UTILIZOU
				if (resultado.acolhido_deficiencia_cuidados.includes('Não')){
					$("#chkCuidadosTerceiros1").prop('checked',true);
				}
				if (resultado.acolhido_deficiencia_cuidados.includes('Sim, de alguém da família')){
					$("#chkCuidadosTerceiros2").prop('checked',true);
				}
				if (resultado.acolhido_deficiencia_cuidados.includes('Sim, de cuidador especializado')){
					$("#chkCuidadosTerceiros3").prop('checked',true);
				}
				if (resultado.acolhido_deficiencia_cuidados.includes('Sim, de vizinho')){
					$("#chkCuidadosTerceiros4").prop('checked',true);
				}
				if (resultado.acolhido_deficiencia_cuidados.includes('Sim, de instituição da rede')){
					$("#chkCuidadosTerceiros5").prop('checked',true);
				}
				if (resultado.acolhido_deficiencia_cuidados.includes('Sim, de outra forma')){
					$("#chkCuidadosTerceiros6").prop('checked',true);
				}


				// TRATA CHECKBOX DE SUBTÂNCIAS DE PREFERÊNCIA
				if (resultado.acolhido_substancia_preferencia.includes('Álcool')){
					$("#chkSubstanciaPreferencia1").prop('checked',true);
				}
				if (resultado.acolhido_substancia_preferencia.includes('Maconha')){
					$("#chkSubstanciaPreferencia2").prop('checked',true);
				}
				if (resultado.acolhido_substancia_preferencia.includes('Cocaína')){
					$("#chkSubstanciaPreferencia3").prop('checked',true);
				}
				if (resultado.acolhido_substancia_preferencia.includes('Crack')){
					$("#chkSubstanciaPreferencia4").prop('checked',true);
				}
				if (resultado.acolhido_substancia_preferencia.includes('Êxtase')){
					$("#chkSubstanciaPreferencia5").prop('checked',true);
				}
				if (resultado.acolhido_substancia_preferencia.includes('Anfetaminas')){
					$("#chkSubstanciaPreferencia6").prop('checked',true);
				}
				if (resultado.acolhido_substancia_preferencia.includes('LSD')){
					$("#chkSubstanciaPreferencia7").prop('checked',true);
				}
				if (resultado.acolhido_substancia_preferencia.includes('K;Spice')){
					$("#chkSubstanciaPreferencia8").prop('checked',true);
				}
				if (resultado.acolhido_substancia_preferencia.includes('Heroína')){
					$("#chkSubstanciaPreferencia9").prop('checked',true);
				}
				if (resultado.acolhido_substancia_preferencia.includes('Metanfetamina')){
					$("#chkSubstanciaPreferencia10").prop('checked',true);
				}
				if (resultado.acolhido_substancia_preferencia.includes('Medicação Psicotrópica')){
					$("#chkSubstanciaPreferencia11").prop('checked',true);
				}
				if (resultado.acolhido_substancia_preferencia.includes('Outra')){
					$("#chkSubstanciaPreferencia12").prop('checked',true);
					$("#boxOutraSubstanciaPreferencia").show();
					$("#txtOutraSubstanciaPreferencia").val(resultado.acolhido_outra_substancia_preferencia);
				}
				else{
					$("#boxOutraSubstanciaPreferencia").hide();
					$("#txtOutraSubstanciaPreferencia").val("");
				}

				$("#slcTempoUtilizaSubstancia").val(resultado.acolhido_tempo_utiliza_substancias);

				if(resultado.acolhido_unidade_hospitalar=="SIM"){
					$("#radUnidadeHospitalar1").prop('checked',true);
					$("#radUnidadeHospitalar2").prop('checked',false);
					$("#boxUnidadeHospitalar").show();
					var unidadeHospitalar = (resultado.acolhido_qual_unidade_hospitalar || "").trim();
					switch(unidadeHospitalar){
						case "Bairral":
							unidadeHospitalar = "Instituto Bairral de Psiquiatria";
						break;
						case "Bezerra":
						case ">Bezerra":
							unidadeHospitalar = "Intituto Bezerra de Menezes";
						break;
						case "Helvetia":
							unidadeHospitalar = "Unidade Recomeço Helvétia";
						break;
						case "IPer":
							unidadeHospitalar = "Instituto Perdizes HCFMUSP";
						break;
						case "Lacan":
							unidadeHospitalar = "Hospital Lacan";
						break;
						case "Pinel":
						case "Pinel ":
							unidadeHospitalar = "CAISM Philippe Pinel";
						break;
					}
					$("#slcUnidadeHospitalar").val(unidadeHospitalar);

					if(resultado.acolhido_qual_unidade_hospitalar=="Outra"){
						$("#boxOutraUnidadeHospitalar").removeClass('d-none');
						$("#txtOutraUnidadeHospitalar").val(resultado.acolhido_outra_unidade_hospitalar);
					}
					else{
						$("#boxOutraUnidadeHospitalar").addClass('d-none');
						$("#txtOutraUnidadeHospitalar").val("");
					}
				}
				else{
					$("#radUnidadeHospitalar1").prop('checked',false);
					$("#radUnidadeHospitalar2").prop('checked',true);
					$("#boxUnidadeHospitalar").hide();
					$("#slcUnidadeHospitalar").val("0");
					$("#boxOutraUnidadeHospitalar").addClass('d-none');
					$("#txtOutraUnidadeHospitalar").val("");
				}

				$("#txtHistorico").val(resultado.acolhido_historico);
				
				$("#boxBotoes").html('<button type="button" class="btn btn-success" id="btnEditar">Alterar Informações</button>');
				$("#btnEditar").click(function() {editaAcolhido(resultado.acolhido)});

			}
			else{
				$("#boxBotoes").html('<button type="submit" class="btn btn-success" id="btnRegistrar">Cadastrar Usuário</button>');
				$("#tabArquivos").addClass("d-none");
				$("#tabStatus").addClass("d-none");
				$("#tabAcolhimento").addClass("d-none");
			}
		},
		complete: function(){}
	 });
}

function cadastraAcolhido(){
	if(!validarCampoNis("#txtNis", true)){
		return;
	}

	var form = $("#formAcolhido").serialize();
	$.ajax({
	  type: "POST",
	  url: "https://portal.seds.sp.gov.br/coed/public/componentes/cadastro-acolhido/model/cadastraAcolhido.php",
	  data: form,
	  success: function (retorno) {
		location.href = "cadastro-acolhido/" + retorno;
	  },
	  error: function (xhr) {
		alert(xhr.responseText || "Nao foi possivel salvar o cadastro");
	  }
	});
}

function editaAcolhido(id){
	if(!$("#formAcolhido").valid()){
		alert('Preencha todos os campos obrigatórios');
		return;
	}

	if(!validarCampoNis("#txtNis", true)){
		return;
	}

	var form = $("#formAcolhido").serialize();
	form += "&id="+id;
	$.ajax({
	  type: "POST",
	  url: "https://portal.seds.sp.gov.br/coed/public/componentes/cadastro-acolhido/model/editaAcolhido.php",
	  data: form,
	  success: function (retorno) {
		alert('Informações alteradas com sucesso');
		carregaAcolhido(id);
	  },
	  error: function (xhr) {
		alert(xhr.responseText || "Nao foi possivel salvar as alteracoes");
	  }
	});
}

function carregaAcolhimento(id){
	$.ajax({
		url:'https://portal.seds.sp.gov.br/coed/public/componentes/cadastro-acolhido/model/carregaAcolhimento.php',
		dataType: 'JSON',
		type: 'POST',
		data: {'id':id},
		success: function(resultado){

			if(resultado.acolhido_entrada_id>0){

				// TRATA CHECKBOX DE DOCUMENTAÇÃO QUE POSSUI
				if (resultado.doc_possui.includes('RG')){
					$("#chkDocPossuo1").prop('checked',true);
				}
				if (resultado.doc_possui.includes('CPF')){
					$("#chkDocPossuo2").prop('checked',true);
				}
				if (resultado.doc_possui.includes('CNH')){
					$("#chkDocPossuo3").prop('checked',true);
				}
				if (resultado.doc_possui.includes('CTPS')){
					$("#chkDocPossuo4").prop('checked',true);
				}
				if (resultado.doc_possui.includes('Certidão de Nascimento')){
					$("#chkDocPossuo5").prop('checked',true);
				}
				if (resultado.doc_possui.includes('Título de Eleitor')){
					$("#chkDocPossuo6").prop('checked',true);
				}
				if (resultado.doc_possui.includes('Cartão SUS')){
					$("#chkDocPossuo7").prop('checked',true);
				}
				if (resultado.doc_possui.includes('Reservista')){
					$("#chkDocPossuo8").prop('checked',true);
				}
				if (resultado.doc_possui.includes('CadUnico')){
					$("#chkDocPossuo9").prop('checked',true);
				}
				if (resultado.doc_possui.includes('Outros')){
					$("#chkDocPossuo10").prop('checked',true);
					$("#boxOutrosDocPossuo").removeClass("d-none");
					$("#txtOutroDocPossuo").val(resultado.outros_doc_possui);
				}

				// TRATA CHECKBOX DE DOCUMENTAÇÃO QUE NECESSITA PROVIDENCIAR
				if (resultado.doc_necessaria.includes('RG')){
					$("#chkDocNecessaria1").prop('checked',true);
				}
				if (resultado.doc_necessaria.includes('CPF')){
					$("#chkDocNecessaria2").prop('checked',true);
				}
				if (resultado.doc_necessaria.includes('CNH')){
					$("#chkDocNecessaria3").prop('checked',true);
				}
				if (resultado.doc_necessaria.includes('CTPS')){
					$("#chkDocNecessaria4").prop('checked',true);
				}
				if (resultado.doc_necessaria.includes('Certidão de Nascimento')){
					$("#chkDocNecessaria5").prop('checked',true);
				}
				if (resultado.doc_necessaria.includes('Título de Eleitor')){
					$("#chkDocNecessaria6").prop('checked',true);
				}
				if (resultado.doc_necessaria.includes('Cartão SUS')){
					$("#chkDocNecessaria7").prop('checked',true);
				}
				if (resultado.doc_necessaria.includes('Reservista')){
					$("#chkDocNecessaria8").prop('checked',true);
				}
				if (resultado.doc_necessaria.includes('CadUnico')){
					$("#chkDocNecessaria9").prop('checked',true);
				}
				if (resultado.doc_necessaria.includes('Outros')){
					$("#chkDocNecessaria10").prop('checked',true);
					$("#boxOutrosDocNecessaria").removeClass("d-none");
					$("#txtOutroDocNecessaria").val(resultado.outros_doc_necessaria);
				}

				$("#slcEscolaridade").val( $('option:contains("' + resultado.acolhido_escolaridade + '")').val() );
				
				if(resultado.acolhido_beneficio=="SIM"){
					$("#boxTipoBeneficio").removeClass("d-none");
					$("#chkBeneficio1").prop('checked',true);

					if(resultado.acolhido_tipo_beneficio.includes('BPC')){
						$("#chkTipoBeneficio1").prop('checked',true);
					}
					if(resultado.acolhido_tipo_beneficio.includes('Ação Jovem')){
						$("#chkTipoBeneficio2").prop('checked',true);
					}
					if(resultado.acolhido_tipo_beneficio.includes('Renda Cidadã')){
						$("#chkTipoBeneficio3").prop('checked',true);
					}
					if(resultado.acolhido_tipo_beneficio.includes('Outros')){
						
						$("#chkTipoBeneficio4").prop('checked',true);

						$("#boxOutroTipoBeneficio").removeClass("d-none");
						$("#txtOutroTipoBeneficio").val(resultado.outro_tipo_beneficio);

					}
					if(resultado.acolhido_tipo_beneficio.includes('Bolsa Família')){
						$("#chkTipoBeneficio5").prop('checked',true);
					}
					if(resultado.acolhido_tipo_beneficio.includes('PETI')){
						$("#chkTipoBeneficio6").prop('checked',true);
					}
					if(resultado.acolhido_tipo_beneficio.includes('POT - Programa Operação Trabalho')){
						$("#chkTipoBeneficio7").prop('checked',true);
					}

				}
				else{
					$("#boxTipoBeneficio").addClass("d-none");
					$("#chkBeneficio2").prop('checked',true);
				}

				$("#txtValorRecebido").val(resultado.acolhido_valor_recebido);
				
				$("#boxBotaoAcolhimento").html('<button type="button" class="btn btn-success mt-5 mb-3 mx-0" id="btnEditarAcolhimento">Alterar Informações</button>');
				$("#btnEditarAcolhimento").click(function() {editaAcolhimento(resultado.acolhido_entrada_id)});

			}
			else{
				$("#boxBotaoAcolhimento").html('<button class="btn btn-success mt-5 mb-3 mx-0" onclick="cadastraAcolhimento()">Confirmar Acolhimento</button>');
			}
		},
		complete: function(){}
	 });
}

function cadastraAcolhimento(){
	var acolhido = $("#hidIdAcolhido").val();
	var docPossui = $('input[name="chkDocPossuo"]:checked').toArray().map(function(check) { return $(check).val(); });
	var docNecessaria = $('input[name="chkDocNecessaria"]:checked').toArray().map(function(check) { return $(check).val(); });
	var outroDocPossui = $("#txtOutroDocPossuo").val();
	var outroDocNecessaria = $("#txtOutroDocNecessaria").val();
	var escolaridade = $("#slcEscolaridade").val();
	var beneficios = $('input[name="chkBeneficio"]:checked').val();
	var tipoBeneficios = $('input[name="chkTipoBeneficio"]:checked').toArray().map(function(check) { return $(check).val(); });
	var valorRecebido = $("#txtValorRecebido").val();
	var outroTipoBeneficio = $("#txtOutroTipoBeneficio").val();

	$.ajax({
	  type: "POST",
	  url: "https://portal.seds.sp.gov.br/coed/public/componentes/cadastro-acolhido/model/cadastraAcolhimento.php",
	  data: {acolhido:acolhido,docPossui:docPossui,docNecessaria:docNecessaria,outroDocPossui:outroDocPossui,outroDocNecessaria:outroDocNecessaria,escolaridade:escolaridade,beneficios:beneficios,tipoBeneficios:tipoBeneficios,valorRecebido:valorRecebido,outroTipoBeneficio:outroTipoBeneficio},
	  success: function (retorno) {
		carregaAcolhimento(retorno);
		alert('Acolhimento confirmado');
	  }
	});
}

function editaAcolhimento(id){
	var acolhido = $("#hidIdAcolhido").val();
	var docPossui = $('input[name="chkDocPossuo"]:checked').toArray().map(function(check) { return $(check).val(); });
	var docNecessaria = $('input[name="chkDocNecessaria"]:checked').toArray().map(function(check) { return $(check).val(); });
	var outroDocPossui = $("#txtOutroDocPossuo").val();
	var outroDocNecessaria = $("#txtOutroDocNecessaria").val();
	var escolaridade = $("#slcEscolaridade").val();
	var beneficios = $('input[name="chkBeneficio"]:checked').val();
	var tipoBeneficios = $('input[name="chkTipoBeneficio"]:checked').toArray().map(function(check) { return $(check).val(); });
	var valorRecebido = $("#txtValorRecebido").val();
	var outroTipoBeneficio = $("#txtOutroTipoBeneficio").val();

	$.ajax({
	  type: "POST",
	  url: "https://portal.seds.sp.gov.br/coed/public/componentes/cadastro-acolhido/model/editaAcolhimento.php",
	  data: {id:id,acolhido:acolhido,docPossui:docPossui,docNecessaria:docNecessaria,outroDocPossui:outroDocPossui,outroDocNecessaria:outroDocNecessaria,escolaridade:escolaridade,beneficios:beneficios,tipoBeneficios:tipoBeneficios,valorRecebido:valorRecebido,outroTipoBeneficio:outroTipoBeneficio},
	  success: function (retorno) {
		carregaAcolhimento(retorno);
		alert('Alterações efetuadas');
	  }
	});
}

function consultaCep(){
	
	var cep = $("#txtCep").val();
	cep = cep.replace('-','');
	
	$.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {

		if (!("erro" in dados)) {
			$("#txtEndereco").val(dados.logradouro);
			$("#txtBairro").val(dados.bairro);
			$("#slcMunicipios").val( $('option:contains('+dados.localidade+')').val() );
		}

	});	

}

function carregaMunicipios(id){
	$.ajax({
	  type: "POST",
	  url: "https://portal.seds.sp.gov.br/coed/public/componentes/cadastro-acolhido/model/carregaMunicipios.php",
	  data: {'id':id},
	  success: function (retorno) {
		$("#boxMunicipios").html(retorno);
	  }
	});
}

function abreBox(box,acao){

	if(box=='boxEndereco'){
		if(acao==0){
			$("#boxEndereco").hide();
		}
		else{
			$("#boxEndereco").show();
			$("#boxSituacaoRua").hide();
		}
	}
	else if(box=='boxSituacaoRua'){
		if(acao==0){
			$("#boxSituacaoRua").hide();
		}
		else{
			$("#boxSituacaoRua").show();
			$("#boxEndereco").hide();
		}
	}
	else if(box=='boxQuantasVezes'){
		if(acao==0){
			$("#boxQuantasVezes").hide();
			$("#txtReincidencia").val("");
		}
		else{
			$("#boxQuantasVezes").show();
		}
	}
	else{

		if(acao==0){
			$("#"+box).hide();
			if(box=='boxUnidadeHospitalar'){
				$("#slcUnidadeHospitalar").val("0");
				$("#boxOutraUnidadeHospitalar").addClass('d-none');
				$("#txtOutraUnidadeHospitalar").val("");
			}
		}
		else{
			$("#"+box).show();
		}

	}

}

function carregaServicos(){
	$.ajax({
	  type: "POST",
	  url: "https://portal.seds.sp.gov.br/coed/public/componentes/cadastro-acolhido/model/carregaServicos.php",
	  success: function (retorno) {
		$("#boxServicos").html(retorno);
		$("#boxGenero").addClass('d-none');
		$("#slcGenero").val('0');
	  }
	});
}

function carregaOscsExecutoras(){
	$("#boxGenero").removeClass('d-none');
}

function carregaDetalhesOsc(id){
	return;
}

function solicitarVaga(){
	var acolhido = $("#hidIdAcolhido").val();
	var servico = $("#slcServicos").val();
	var genero = $("#slcGenero").val();

	if(servico==0 || genero==0 || servico==null || genero==null){
		alert('Selecione o referenciamento do servico e o genero');
	}
	else{

		$.ajax({
			type: "POST",
			url: "https://portal.seds.sp.gov.br/coed/public/componentes/cadastro-acolhido/model/solicitarVaga.php",
			data: {'acolhido':acolhido,'servico_id':servico,'genero_solicitado':genero},
			success: function () {
				alert('Vaga Solicitada');
				$("#mdlSolicitarVaga").modal('hide');
				carregaAcolhido(acolhido);
			}
		});
	
	}
}

function cadastraContatoReferencia(){

	var id = getIdContatoReferenciaAtual();
	var nomeContato = $("#txtNomeReferencia").val();
	var telefoneReferencia = $("#txtTelefoneReferencia").val();
	var parentesco = $("#slcGrauParentesco").val();

	if(nomeContato=="" || telefoneReferencia=="" || parentesco == 0){
		alert("Preencha todos os campos do contato de referência");
	}
	else{

		$.ajax({
			type: "POST",
			url: "https://portal.seds.sp.gov.br/coed/public/componentes/cadastro-acolhido/model/cadastraContatoReferencia.php",
			data: {'id':id,'nomeContato':nomeContato,'telefoneReferencia':telefoneReferencia,'parentesco':parentesco},
			success: function (retorno) {
				$("#txtNomeReferencia").val('');
				$("#txtTelefoneReferencia").val('');
				$("#slcGrauParentesco").val('');
				listaContatosReferencia();
			}
		});

	}
}

function listaContatosReferencia(){
	var id = getIdContatoReferenciaAtual();
	$.ajax({
		type: "POST",
		url: "https://portal.seds.sp.gov.br/coed/public/componentes/cadastro-acolhido/model/listaContatosReferencia.php",
		data: {'id':id},
		success: function (retorno) {
			$("#boxContatosReferencia").html(retorno);
		}
	  });
}

function excluirContatoReferencia(id){
	$.ajax({
	  type: "POST",
	  url: "https://portal.seds.sp.gov.br/coed/public/componentes/cadastro-acolhido/model/excluiContatoReferencia.php",
	  data: {'id':id},
	  success: function (retorno) {
		listaContatosReferencia();
	  }
	});
}

function trataUnidadeHospitalar(unidade){
	if(unidade=='Outra'){
		$("#boxOutraUnidadeHospitalar").removeClass('d-none');
	}
	else{
		$("#boxOutraUnidadeHospitalar").addClass('d-none');
		$("#txtOutraUnidadeHospitalar").val("");
	}
}



