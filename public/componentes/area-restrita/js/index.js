$(document).ready(function(){
	carregaTotalVagas();
	carregaTotalAcolhidos();
	carregaTotalOscs();
	carregaTotalPortasDeEntrada();
	carregaTotalPretacoesDisponiveis();
	carregaTotalPretacoesFinalizadas();
	carregaSolicitacoesVagas();
	exibirVinculos();
	carregaOscsExecutoras();
})

function pergunta(acao , param , id){
	var modalConfirmacao = new bootstrap.Modal(document.getElementById('confirmacaoModal'), {
		keyboard: false
	})

	var modalJustificativa = new bootstrap.Modal(document.getElementById('justificativaModal'), {
		keyboard: false
	})

/* acao = 1 - Controle de vaga */

	switch(acao){
		case 1:
			$("#tituloModal").html('<h5 class="modal-title" id="tituloModal">Solicitação de vaga</h5>');
			
			if(param==0){
				modalJustificativa.show();
				$("#boxBotoesModalJustificativa").html('<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button><button type="button" data-bs-dismiss="modal" class="btn btn-danger" onclick="controlaVaga(0,'+id+');">Negar vaga</button>');
			}
			else{
				modalConfirmacao.show();
				$("#corpoModal").html('<p>Deseja reservar a vaga?</p>');
				$("#boxBotoesModal").html('<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button><button type="button" data-bs-dismiss="modal" class="btn btn-success" onclick="controlaVaga(1,'+id+');">Reservar</button>');
			}
		break;
	}

}

function carregaTotalVagas(){
	
	$.ajax({
		type: "POST",
		url: "public/componentes/area-restrita/model/totalVagas.php",
		success: function (retorno) {
		  
			const valor = retorno.split(",");
			
			$("#totalVagas").html(valor[0]);
		  	$("#totalVagasOcupadas").html(valor[1]);
		}
	});

}

function carregaTotalAcolhidos(){
	
	$.ajax({
		type: "POST",
		url: "public/componentes/area-restrita/model/totalAcolhidos.php",
		success: function (retorno) {
		  $("#totalAcolhidos").html(retorno);
		}
	});

}

function carregaTotalOscs(){
	
	$.ajax({
		type: "POST",
		url: "public/componentes/area-restrita/model/totalOscs.php",
		success: function (retorno) {
		  $("#totalOscs").html(retorno);
		}
	});

}

function carregaTotalPortasDeEntrada(){
	
	$.ajax({
		type: "POST",
		url: "public/componentes/area-restrita/model/totalMunicipios.php",
		success: function (retorno) {
		  $("#totalPortasDeEntrada").html(retorno);
		}
	});

}

function carregaTotalPretacoesDisponiveis(){
	
	$.ajax({
		type: "POST",
		url: "public/componentes/area-restrita/model/totalPrestacoesDisponiveis.php",
		success: function (retorno) {
		  $("#totalPrestacoesDisponibilizadas").html(retorno);
		}
	});

}

function carregaTotalPretacoesFinalizadas(){
	
	$.ajax({
		type: "POST",
		url: "public/componentes/area-restrita/model/totalPrestacoesFinalizadas.php",
		success: function (retorno) {
		  $("#totalPrestacoesFinalizadas").html(retorno);
		}
	});

}

function carregaSolicitacoesVagas(){
	
	$.ajax({
		type: "POST",
		url: "public/componentes/area-restrita/model/solicitacoesVagas.php",
		success: function (retorno) {
	  		$("#boxSolicitacoesVagas").html(retorno);
		}
	});

}

function abrePrestacoes(){
	location.href="https://portal.seds.sp.gov.br/coed/prestacoes";
}

function controlaVaga(param , solicitacao_id){

	var justificativa = $("#txtJustificativa").val();

	if(justificativa=="" && param==0){
		alert('Preencha a justificativa da vaga ser negada');
	}
	else{

		$.ajax({
			type: "POST",
			url: "https://portal.seds.sp.gov.br/coed/public/componentes/area-restrita/model/alteraSolicitacaoVaga.php",
			data: {'solicitacao_id':solicitacao_id,'parametro':param,'justificativa':justificativa},
			success: function (retorno) {
				alert(retorno);
				$("#mdlSolicitarVaga").modal('hide');
				carregaSolicitacoesVagas();
			}
		});

	}
}

function exibirVinculos(){
    $.ajax({
        url: "public/componentes/login/model/exibeVinculos.php",
        success: function(retorno){

            $("#boxVinculos").html(retorno);

        }

    });
}

function direcionaUsuario(id){
    $.ajax({
		url: "public/componentes/login/model/direcionaUsuario.php",
		type: "POST",
		data: {'id':id},
		success: function(retorno){
            location.href=retorno;
		}

	});
}

function carregaOscsExecutoras(){
	$.ajax({
	  type: "POST",
	  url: "https://portal.seds.sp.gov.br/coed/public/componentes/area-restrita/model/listaOscsPortasEntrada.php",
	  data: {},
	  success: function (retorno) {
		$("#boxRelacaoOscs").html(retorno);
	  }
	});
}