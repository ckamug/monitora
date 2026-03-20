$(document).ready(function(){

	carregaMunicipios(0);
	carregaCargos();
	carregaExecutora($("#hidIdExecutora").val());
	listaResponsaveis($("#hidIdExecutora").val());

	$('#txtCnpj').mask('00.000.000/0000-00');
	$('#txtCep').mask('00000-000');
	$('#txtTelefone').mask('(00)00000-0000');
	$('#txtCpfResponsavel').mask('000.000.000-00');
	$("#txtCep").blur(function() {consultaCep()});

	$("#formExecutora").validate({
		invalidHandler: function() {
			alert('Preencha todos os campos obrigatórios');
		},
		submitHandler: function(){

			alert("Informações registradas com sucesso");
			cadastraExecutora();

		},
		rules:{
		  txtNomeFantasia:{
			required:true,
		  },
		  txtRazaoSocial:{
			required:true,
		  },
		  txtCnpj:{
			required:true,
		  },
		  txtCep:{
			required:true,
		  },
		  txtEndereco:{
			required:true,
		  },
		  txtNumero:{
			required:true,
		  },
		  txtBairro:{
			required:true,
		  },
		  slcMunicipios:{
			required:true,
		  },
		  txtEmail:{
			required:true,
		  },
		  txtTelefone:{
			required:true,
		  },

		},
  
	  }

	);

})

function carregaExecutora(id){
	$.ajax({
		url:'https://portal.seds.sp.gov.br/coed/public/componentes/cadastro-executora/model/carregaExecutora.php',
		dataType: 'JSON',
		type: 'POST',
		data: {'id':id},
		success: function(resultado){

			if(resultado.executora_id>0){
				$("#txtNomeFantasia").val(resultado.executora_nome_fantasia);
				$("#txtRazaoSocial").val(resultado.executora_razao_social);
				$("#txtCnpj").val(resultado.executora_cnpj);
				$("#txtCnae").val(resultado.executora_cnae);
				$("#txtEndereco").val(resultado.executora_endereco);
				$("#txtNumero").val(resultado.executora_numero);
				$("#txtComplemento").val(resultado.executora_complemento);
				$("#txtBairro").val(resultado.executora_bairro);
				setTimeout('carregaMunicipios('+resultado.cidade_id+')','300');
				$("#txtCep").val(resultado.executora_cep);
				$("#txtEmail").val(resultado.executora_email);
				$("#txtTelefone").val(resultado.executora_telefone);
				$("#txtVagas").val(resultado.executora_vagas);

				// TRATA CHECKBOX DE GÊNERO
				if (resultado.executora_generos.includes('Masculino')){
					$("#chkGenero1").prop('checked',true);
				}
				if (resultado.executora_generos.includes('Feminino')){
					$("#chkGenero2").prop('checked',true);
				}
				if (resultado.executora_generos.includes('LGBTQIA+')){
					$("#chkGenero3").prop('checked',true);
				}

				// TRATA CHECKBOX DE SERVIÇOS
				if (resultado.executora_servicos_id.includes('1')){
					$("#chkServico1").prop('checked',true);
				}
				if (resultado.executora_servicos_id.includes('2')){
					$("#chkServico2").prop('checked',true);
				}
				if (resultado.executora_servicos_id.includes('3')){
					$("#chkServico3").prop('checked',true);
				}
				if (resultado.executora_servicos_id.includes('4')){
					$("#chkServico4").prop('checked',true);
				}
				if (resultado.executora_servicos_id.includes('5')){
					$("#chkServico5").prop('checked',true);
				}
				if (resultado.executora_servicos_id.includes('6')){
					$("#chkServico6").prop('checked',true);
				}

				$("#boxBotoes").html('<button type="button" class="btn btn-success" id="btnEditar">Alterar Informações</button>');
				$("#btnEditar").click(function() {editaExecutora(resultado.executora_id)});

			}
			else{
				$("#boxBotoes").html('<button type="submit" class="btn btn-success" id="btnRegistrar">Cadastrar OSC Executora</button>');
			}
		},
		complete: function(){}
	 });
}

function cadastraExecutora(){
	var form = $("#formExecutora").serialize();
	$.ajax({
	  type: "POST",
	  url: "/public/componentes/cadastro-executora/model/cadastraExecutora.php",
	  data: form,
	  success: function (retorno) {
		carregaExecutora(retorno);
	  }
	});
}

function cadastraResponsavelExecutora(){
	
	var id = $("#hidIdExecutora").val();
	var nome = $("#txtResponsavel").val();
	var cpf = $("#txtCpfResponsavel").val();
	var cargo = $("#slcCargos").val();
	
	$.ajax({
	  type: "POST",
	  url: "/public/componentes/cadastro-executora/model/cadastraResponsavel.php",
	  data: {'id':id , 'nome':nome , 'cpf':cpf , 'cargo':cargo},
	  success: function () {
		carregaCargos();
		listaResponsaveis(id);
	  }
	});
}

function editaExecutora(id){
	var form = $("#formExecutora").serialize();
	form += "&id="+id;
	$.ajax({
	  type: "POST",
	  url: "/public/componentes/cadastro-executora/model/editaExecutora.php",
	  data: form,
	  success: function () {
		alert('Informações alteradas com sucesso');
		carregaExecutora();
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
	  url: "/public/componentes/cadastro-executora/model/carregaMunicipios.php",
	  data: {'id':id},
	  success: function (retorno) {
		$("#boxMunicipios").html(retorno);
	  }
	});
}

function carregaCargos(){
	$.ajax({
	  type: "POST",
	  url: "/public/componentes/cadastro-executora/model/carregaCargos.php",
	  success: function (retorno) {
		$("#boxCargos").html(retorno);
	  }
	});
}

function listaResponsaveis(id){
	$.ajax({
		type: "POST",
		url: "/public/componentes/cadastro-executora/model/listaResponsaveis.php",
		data: {"id":id},
		success: function (retorno) {
		  $("#boxListaResponsaveis").html(retorno);
		}
    });
}

function excluiResponsavel(id){
	var executora_id = $("#hidIdExecutora").val();
	$.ajax({
		type: "POST",
		url: "/public/componentes/cadastro-executora/model/excluiResponsavel.php",
		data: {"id":id},
		success: function () {
		  listaResponsaveis(executora_id);
		}
    });
}