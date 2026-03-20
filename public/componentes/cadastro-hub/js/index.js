$(document).ready(function(){

	carregaAcolhido($("#hidIdAcolhido").val());

	$("#formAcolhido").validate({
		invalidHandler: function() {
			alert('Preencha todos os campos obrigatórios');
		},
		submitHandler: function(){
			alert("Informações registradas com sucesso");
			cadastraAcolhido();
		},
		rules:{
			txtNomeCompleto:{
			  required:true,
			},
  
		  },
  
	  }

	);

})

function carregaAcolhido(id){

	$.ajax({
		url:'https://portal.seds.sp.gov.br/coed/public/componentes/cadastro-hub/model/carregaAcolhido.php',
		dataType: 'JSON',
		type: 'POST',
		data: {'id':id},
		success: function(resultado){

			if(resultado.acolhido_hub_id>0){

				$("#txtNomeCompleto").val(resultado.acolhido_nome);
				$("#txtDataNascimento").val(resultado.data_nascimento);
				$("#txtDataEntrada").val(resultado.data_entrada);
				$("#txtDataSaida").val(resultado.data_saida);
				$("#slcTipoDesligamento").val(resultado.tipo_desligamento);
				$("#slcAntesHub").val(resultado.local_antes_acolhimento);

				if(resultado.local_situacao_rua=='Situação de Rua'){
					$("#slcLocalSituacaoRua").val(resultado.local_situacao_rua);
				}

				$("#slcAposDesligamento").val(resultado.local_apos_desligamento);
				
				// TRATA CHECKBOX DE SUBTÂNCIAS QUE JÁ UTILIZOU
				if (resultado.tipo_droga.includes('Álcool')){
					$("#chkSubstanciaConsumia1").prop('checked',true);
				}
				if (resultado.tipo_droga.includes('Maconha')){
					$("#chkSubstanciaConsumia2").prop('checked',true);
				}
				if (resultado.tipo_droga.includes('Cocaína')){
					$("#chkSubstanciaConsumia3").prop('checked',true);
				}
				if (resultado.tipo_droga.includes('Crack')){
					$("#chkSubstanciaConsumia4").prop('checked',true);
				}
				if (resultado.tipo_droga.includes('Êxtase')){
					$("#chkSubstanciaConsumia5").prop('checked',true);
				}
				if (resultado.tipo_droga.includes('Anfetaminas')){
					$("#chkSubstanciaConsumia6").prop('checked',true);
				}
				if (resultado.tipo_droga.includes('LSD')){
					$("#chkSubstanciaConsumia7").prop('checked',true);
				}
				if (resultado.tipo_droga.includes('K2')){
					$("#chkSubstanciaConsumia8").prop('checked',true);
				}
				if (resultado.tipo_droga.includes('K4')){
					$("#chkSubstanciaConsumia9").prop('checked',true);
				}
				if (resultado.tipo_droga.includes('K9')){
					$("#chkSubstanciaConsumia10").prop('checked',true);
				}

				$("#boxBotoes").html('<button type="button" class="btn btn-success" id="btnEditar">Alterar Informações</button>');
				$("#btnEditar").click(function() {editaAcolhido(resultado.acolhido_hub_id)});

			}
			else{
				$("#boxBotoes").html('<button type="submit" class="btn btn-success" id="btnRegistrar">Cadastrar Usuário</button>');
			}
		},
		complete: function(){}
	 });
}

function cadastraAcolhido(){
	var form = $("#formAcolhido").serialize();
	$.ajax({
	  type: "POST",
	  url: "/public/componentes/cadastro-hub/model/cadastraInfoAcolhido.php",
	  data: form,
	  success: function (retorno) {
		location.href = "cadastro-hub/"+retorno;
	  }
	});
}

function editaAcolhido(id){
	var form = $("#formAcolhido").serialize();
	form += "&id="+id;
	$.ajax({
	  type: "POST",
	  url: "/public/componentes/cadastro-hub/model/editaInfoAcolhido.php",
	  data: form,
	  success: function (retorno) {
		alert('Informações alteradas com sucesso');
		carregaAcolhido(id);
	  }
	});
}

function trataLocalAntesHub(sel){

	if(sel=='Situação de Rua'){
		$("#boxLocalSituacaoRua").removeClass("d-none");
	}
	else{
		$("#boxLocalSituacaoRua").addClass("d-none");
	}

}