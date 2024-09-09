$(document).ready(function(){

	carregaMunicipios(0);
	carregaCelebrante($("#hidIdCelebrante").val());

	$('#txtCnpj').mask('00.000.000/0000-00');
	$('#txtCep').mask('00000-000');
	$('#txtTelefone').mask('(00)00000-0000');
	$("#txtCep").blur(function() {consultaCep()});

	$("#txtRh , #txtCusteio , #txtServicosTerceiros").maskMoney({
		prefix: "R$ ",
		decimal: ",",
		thousands: "."
	});

	$("#formCelebrante").validate({
		invalidHandler: function() {
			alert('Preencha todos os campos obrigatórios');
		},
		submitHandler: function(){

			alert("Informações registradas com sucesso");
			cadastraCelebrante();

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
		  txtCnae:{
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

function carregaCelebrante(id){
	$.ajax({
		url:'https://portal.seds.sp.gov.br/coed/public/componentes/cadastro-celebrante/model/carregaCelebrante.php',
		dataType: 'JSON',
		type: 'POST',
		data: {'id':id},
		success: function(resultado){
				
			if(resultado.celebrante_id>0){
				$("#txtNomeFantasia").val(resultado.celebrante_nome_fantasia);
				$("#txtRazaoSocial").val(resultado.celebrante_razao_social);
				$("#txtCnpj").val(resultado.celebrante_cnpj);
				$("#txtCnae").val(resultado.celebrante_cnae);
				$("#txtEndereco").val(resultado.celebrante_endereco);
				$("#txtNumero").val(resultado.celebrante_numero);
				$("#txtComplemento").val(resultado.celebrante_complemento);
				$("#txtBairro").val(resultado.celebrante_bairro);
				setTimeout('carregaMunicipios('+resultado.cidade_id+')','300');
				$("#txtCep").val(resultado.celebrante_cep);
				$("#txtEmail").val(resultado.celebrante_email);
				$("#txtTelefone").val(resultado.celebrante_telefone);
				$("#txtRh").val("R$ " + resultado.celebrante_valor_previsto_rh);
				$("#txtCusteio").val("R$ " + resultado.celebrante_valor_previsto_custeio);
				$("#txtServicosTerceiros").val("R$ " + resultado.celebrante_valor_previsto_terceiros);
				$("#boxBotoes").html('<button type="button" class="btn btn-success" id="btnEditar">Alterar Informações</button>');
				$("#btnEditar").click(function() {editaCelebrante(resultado.celebrante_id)});

			}
			else{
				$("#boxBotoes").html('<button type="submit" class="btn btn-success" id="btnRegistrar">Registrar Celebrante</button>');
			}
		},
		complete: function(){}
	 });
}

function cadastraCelebrante(){
	var form = $("#formCelebrante").serialize();
	$.ajax({
	  type: "POST",
	  url: "https://portal.seds.sp.gov.br/coed/public/componentes/cadastro-celebrante/model/cadastraCelebrante.php",
	  data: form,
	  success: function () {
		//location.href='https://portal.seds.sp.gov.br/coed/cadastro-celebrante';
	  }
	});
}

function editaCelebrante(id){
	var form = $("#formCelebrante").serialize();
	form += "&id="+id;
	$.ajax({
	  type: "POST",
	  url: "https://portal.seds.sp.gov.br/coed/public/componentes/cadastro-celebrante/model/editaCelebrante.php",
	  data: form,
	  success: function (retorno) {
		alert('Informações alteradas com sucesso');
		carregaCelebrante();
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
	  url: "https://portal.seds.sp.gov.br/coed/public/componentes/cadastro-celebrante/model/carregaMunicipios.php",
	  data: {'id':id},
	  success: function (retorno) {
		$("#boxMunicipios").html(retorno);
	  }
	});
}