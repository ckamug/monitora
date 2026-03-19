$(document).ready(function(){

	carregaMunicipios(0);
	carregaMunicipio($("#hidIdMunicipio").val());
	$("#boxBotoes button[type='submit']").text("Cadastrar Porta de Entrada");

	$('#txtCnpj').mask('00.000.000/0000-00');
	$('#txtCep').mask('00000-000');
	$('#txtTelefone').mask('(00)00000-0000');
	$("#txtCep").blur(function() {consultaCep()});

	$("#formMunicipio").validate({
		invalidHandler: function() {
			alert('Preencha todos os campos obrigatórios');
		},
		submitHandler: function(){

			alert("Informações registradas com sucesso");
			cadastraMunicipio();

		},
		rules:{
		  txtOrgaoPublico:{
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
		  txtComplemento:{
			required:true,
		  },
		  txtBairro:{
			required:true,
		  },
		  slcMunicipios:{
			required:true,
		  },
		  txtTecnicoReferencia:{
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

	$("#chkStatus").on('click',function(){

		if($("#chkStatus").is(":checked") == false){
			alteraStatus(0);
			$("#lblStatus").html('Inativo');
			$("#lblStatus").addClass('text-secondary');
			$("#lblStatus").removeClass('text-primary');
		}
		else{
			alteraStatus(1);
			$("#lblStatus").html('Ativo');
			$("#lblStatus").addClass('text-primary');
			$("#lblStatus").removeClass('text-secondary');
		}
		
		
	});

})

function carregaMunicipio(id){
	$.ajax({
		url:'https://portal.seds.sp.gov.br/coed/public/componentes/cadastro-municipio/model/carregaMunicipio.php',
		dataType: 'JSON',
		type: 'POST',
		data: {'id':id},
		success: function(resultado){
				
			if(resultado.municipio_id>0){
				$("#boxStatus").removeClass('d-none');
				$("#txtOrgaoPublico").val(resultado.municipio_orgao_publico);
				$("#txtCnpj").val(resultado.municipio_cnpj);
				$("#txtCep").val(resultado.municipio_cep);
				$("#txtEndereco").val(resultado.municipio_endereco);
				$("#txtNumero").val(resultado.municipio_numero);
				$("#txtComplemento").val(resultado.municipio_complemento);
				$("#txtBairro").val(resultado.municipio_bairro);
				setTimeout('carregaMunicipios('+resultado.cidade_id+')','300');
				$("#txtTecnicoReferencia").val(resultado.municipio_tecnico_referencia);
				$("#txtEmail").val(resultado.municipio_email_institucional);
				$("#txtTelefone").val(resultado.municipio_telefone);
				$("#boxBotoes").html('<button type="button" class="btn btn-success" id="btnEditar">Alterar Informações</button>');
				$("#btnEditar").click(function() {editaMunicipio(resultado.municipio_id)});
				
				if(resultado.municipio_status==0){
					$("#lblStatus").html('Inativo');
					$("#chkStatus").prop('checked',false);
				}
				else{
					$("#lblStatus").html('Ativo');
					$("#chkStatus").prop('checked',true);
				}

			}
			else{
				$("#boxBotoes").html('<button type="submit" class="btn btn-success" id="btnRegistrar">Cadastrar Porta de Entrada</button>');
			}
		},
		complete: function(){}
	 });
}

function cadastraMunicipio(){
	var form = $("#formMunicipio").serialize();
	$.ajax({
	  type: "POST",
	  url: "https://portal.seds.sp.gov.br/coed/public/componentes/cadastro-municipio/model/cadastraMunicipio.php",
	  data: form,
	  success: function () {
		//location.href='https://portal.seds.sp.gov.br/coed/cadastro-Municipio';
	  }
	});
}

function editaMunicipio(id){
	var form = $("#formMunicipio").serialize();
	form += "&id="+id;
	$.ajax({
	  type: "POST",
	  url: "https://portal.seds.sp.gov.br/coed/public/componentes/cadastro-municipio/model/editaMunicipio.php",
	  data: form,
	  success: function (retorno) {
		alert('Informações alteradas com sucesso');
		carregaMunicipio(id);
	  }
	});
}

function alteraStatus(status){
	var id = $("#hidIdMunicipio").val();
	$.ajax({
	  type: "POST",
	  url: "https://portal.seds.sp.gov.br/coed/public/componentes/cadastro-municipio/model/alteraStatus.php",
	  data: {id:id,status:status},
	  success: function () {
		
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
	  url: "https://portal.seds.sp.gov.br/coed/public/componentes/cadastro-municipio/model/carregaMunicipios.php",
	  data: {'id':id},
	  success: function (retorno) {
		$("#boxMunicipios").html(retorno);
	  }
	});
}
