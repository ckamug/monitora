$(document).ready(function(){
	
	$("#txtMesReferencia , #txtMesReferenciaDocComplementar").datepicker({
		format: "mm/yyyy",
		viewMode: "months",
		minViewMode: "months",
		autoclose: true,
		language: "pt-BR",
		orientation: "bottom"
	});
	
	verificaLogin();
	carregaTiposPrestacao();
	listaPrestacoes(0,'executora');
	$("#btnNovaPrestacao").click(function() {$("#boxNovaPrestacao").removeClass('d-none');$("#btnNovaPrestacao").addClass('disabled');carregaExecutoras();listaPrestacoes();});

	$("#chkCelebrante").on( "click", function() {
		if($("#chkCelebrante").is(":checked")){
			listaPrestacoes(1,'celebrante');
			$("#slcExecutoras").prop("disabled","disabled");
			$("#txtTituloPrestacao").html(" (Celebrante)");
			$("#slcExecutoras option:first").attr('selected','selected');
		}else{
			$("#slcExecutoras").prop("disabled",false);
			carregaExecutoras('todas');
			$("#txtTituloPrestacao").html("");
			setTimeout('$("#slcExecutoras option:first").attr("selected","selected")',100);
		}
	});

	$("body").on("click", "#btnDocsComp", function(){
		var mes = $("#txtMesReferenciaDocComplementar").val();
		zipDocsComp(mes);
	});

	$("#formPrestacao").validate({
		invalidHandler: function() {
			alert('Preencha todos os campos obrigatórios');
		},
		submitHandler: function(){
			cadastraPrestacao();
		},
		rules:{},
  
	  }

	);
	
})

function verificaLogin(){
	$.ajax({
	  type: "POST",
	  url: "https://portal.seds.sp.gov.br/coed/public/componentes/prestacoes/model/verificaLogin.php",
	  success: function (retorno) {

		switch(retorno){
			case '1':
				$("#btnNovaPrestacao").addClass('d-none');
				$("#boxSlcExecutoras").removeClass('d-none');
				$("#boxCheckCelebrante").removeClass('d-none');
				$("#boxOscsExecutoras").removeClass('d-none');
				carregaExecutoras('todas');
			break;
			case '2':
				$("#boxSlcExecutoras").removeClass('d-none');
				$("#btnNovaPrestacao").removeClass('d-none');
				$("#boxOscsExecutoras").removeClass('d-none');
				carregaExecutoras('todas');
			break;
			case '6':
				$("#btnNovaPrestacao").addClass('d-none');
				$("#boxSlcExecutoras").removeClass('d-none');
				$("#boxCheckCelebrante").removeClass('d-none');
				$("#boxOscsExecutoras").removeClass('d-none');
				carregaExecutoras('todas');
			break;
			default:
				$("#btnNovaPrestacao").removeClass('d-none');
				$("#boxSlcExecutoras").addClass('d-none');
				$("#boxPrestacoes").slideDown('fast');
			break;
		}

	  }
	});
}

function abreTermo(id){
	$.ajax({
		type: "POST",
		url: "public/componentes/prestacoes/model/consultaCiencia.php",
		data:{'id':id},
		success: function (retorno) {
		  if(retorno==0){

			$("#boxBotoesTermo").html('<button type="button" class="btn btn-primary" id="btnAceitaTermo">EU ACEITO</button><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">EU NÃO ACEITO</button>');
			$("#btnAceitaTermo").click(function() {registraAceite(id)});

			var myModal = new bootstrap.Modal(document.getElementById('staticBackdrop'), {
				keyboard: false
			  })
			myModal.show();
		  }
		  else{
			abrePrestacao(id);
		  }
		  
		}
	});
}

function abrePrestacao(id){
	location.href="notas/"+id;
}

function registraAceite(id){
	$.ajax({
	  type: "POST",
	  url: "https://portal.seds.sp.gov.br/coed/public/componentes/prestacoes/model/registrarAceite.php",
	  data:{'id':id},
	  success: function () {
		abrePrestacao(id);
	  }
	});
}

function carregaExecutoras(tipo){
	console.log(tipo);
	$.ajax({
	  type: "POST",
	  url: "https://portal.seds.sp.gov.br/coed/public/componentes/prestacoes/model/carregaExecutoras.php",
	  data:{'tipo':tipo},
	  success: function (retorno) {
		$("#boxSlcExecutoras").html(retorno);
		//setTimeout("$('.form-select').select2({dropdownParent: $('#boxSlcExecutoras'),placeholder: 'OSC Executora',allowClear: false,theme:'bootstrap4'})",'500');
		$('.form-select').select2({
			placeholder: 'OSC Executora',
			allowClear: false,
			theme:'bootstrap4'});

			$('#select2-slcExecutoras-container').parent().css('padding', '30px');
			$('#select2-slcTiposPrestacao-container').parent().css('padding', '29px');
	  }
	});
}

function listaPrestacoes(id,tipo){

	$.ajax({
		type: "POST",
		url: "public/componentes/prestacoes/model/listaPrestacoes.php",
		data:{'id':id,'tipo':tipo},
		success: function (retorno) {
		  $("#boxListaPrestacoes").html(retorno);
		  $("#boxPrestacoes").slideDown('fast');

		  if(id == 0 || tipo == 'celebrante'){
			$("#txtTituloPrestacao").html(" - Celebrante");
		  }
		  else{
		  	setTimeout('$("#txtTituloPrestacao").html(" - " + $("#slcExecutoras option:selected").text())',300);
		  }
		}
	});

}

function cadastraPrestacao(){
	var form = $("#formPrestacao")[0];
	var data = new FormData(form);
	$.ajax({
	  type: "POST",
	  url: "https://portal.seds.sp.gov.br/coed/public/componentes/prestacoes/model/cadastrarPrestacao.php",
	  data: data,
	  processData: false,
      cache: false,
      contentType: false,
	  success: function (retorno) {
		if(retorno==1){
			$('#formPrestacao').each (function(){
				this.reset();
            });
			$("#boxNovaPrestacao").addClass('d-none');
			$("#btnNovaPrestacao").removeClass('disabled')
			$("#boxPrestacoes").slideDown('fast');
			listaPrestacoes(0);
		}
		else{
			var modalConfirmacao = new bootstrap.Modal(document.getElementById('avisoModal'), {
				
			})
			modalConfirmacao.show();
			$("#tituloAvisoModal").html('<h5 class="modal-title"><i class="bi bi-exclamation-triangle text-danger"></i> Atenção</h5>');
			$("#corpoAvisoModal").html('<p>Já existe Prestação de Contas para o mês informado</p>');
			$("#boxBotoesAvisoModal").html('<button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>');
		}

	  }
	});
}

function carregaTiposPrestacao(){
	$.ajax({
	  type: "POST",
	  url: "https://portal.seds.sp.gov.br/coed/public/componentes/prestacoes/model/carregaTiposPrestacao.php",
	  success: function (retorno) {
		$("#boxSlcTiposPrestacao").html(retorno);
		$('#select2-slcTiposPrestacao-container').parent().css('padding', '29px');
	  }
	});
}

function cancelaCadPrestacao(){
	$('#formPrestacao').each (function(){
		this.reset();
	});
	$("#boxNovaPrestacao").addClass('d-none')
	$("#btnNovaPrestacao").removeClass('disabled')
}

function zipDocsComp(mes){
	$("#boxArquivoGerado").html('<div class="progress mt-4"><div class="progress-bar progress-bar-striped progress-bar-animated bg-warning" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">Gerando Arquivo...</div></div>');
	$("#boxArquivoGerado").removeClass('d-none');
	setTimeout('$(".progress-bar").html("Aguarde...")',10000);
	setTimeout('$(".progress-bar").html("Gravando documentos...")',15000);
	$.ajax({
	  type: "POST",
	  url: "https://portal.seds.sp.gov.br/coed/public/componentes/prestacoes/model/geraZipDocComplementar.php",
	  data:{'mes':mes},
	  success: function (retorno) {
		if(retorno!=0){
			$("#boxArquivoGerado").html(retorno);
		}
		else{
			$("#boxArquivoGerado").html('Erro!');
		}
	  }
	});
}