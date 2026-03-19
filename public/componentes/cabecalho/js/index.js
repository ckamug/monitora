$(document).ready(function(){
	
	$("#txtMesReferencia").datepicker({
		format: "mm/yyyy",
		viewMode: "months",
		minViewMode: "months",
		autoclose: true,
		language: "pt-BR",
		orientation: "bottom"
	});

	carregaExecutoras();
	carregaTiposRepasse();
	
	$("#txtValorRecursosHumanos , #txtValorCusteio , #txtValorServicosTerceiros").maskMoney({
		prefix: "R$",
		decimal: ",",
		thousands: "."
	});

	$("#txtValorRepasse").maskMoney({
		prefix: "R$",
		decimal: ",",
		thousands: "."
	});

	$("#chkCelebrante").on( "click", function() {
		if($("#chkCelebrante").is(":checked")){
			listaCabecalhos(1,'celebrante');
			$("#slcExecutoras").prop("disabled","disabled");
		}else{
			$("#boxCabecalhos").slideUp('fast');
			$("#slcExecutoras").prop("disabled",false);
			carregaExecutoras();
		}
	});


	$("#txtMesReferencia").on( "change", function() {
			carregaSaldo(this.value);
			if($("#slcTiposRepasse").val()==3){
				$("#txtValorRecursosHumanos , #txtValorCusteio , #txtValorServicosTerceiros").maskMoney({
					prefix: "R$",
					decimal: ",",
					thousands: "."
				});
				$("#txtValorRecursosHumanos").val('R$0,00');
				$("#txtValorCusteio").val('R$0,00');
				$("#txtValorServicosTerceiros").val('R$0,00');
			}
			else{
				$("#txtValorRecursosHumanos , #txtValorCusteio , #txtValorServicosTerceiros").maskMoney('destroy');
			}
	});

	$("#formCabecalho").validate({
		invalidHandler: function() {
			alert('Preencha todos os campos obrigatórios');
		},
		submitHandler: function(){
			cadastraCabecalho();
		},
		rules:{},
  
	  }

	);

	$('#myTab').tab('show');
	
})

function cadastraCabecalho(){

	var form = $("#formCabecalho")[0];
	var id = $('#slcExecutoras').val();
	if($("#chkCelebrante").is(":checked")){
		var tipo = "celebrante";
	}else{
		var tipo = "executora";
	}

	var data = new FormData(form);
	data.append('id',id);
	data.append('tipo',tipo);

	$.ajax({
	  url: "https://portal.seds.sp.gov.br/coed/public/componentes/cabecalho/model/cadastrarCabecalho.php",
	  type: "POST",
	  data: data,
	  processData: false,
      cache: false,
      contentType: false,
	  success: function (retorno) {
		if(retorno==1){
			$('#formCabecalho').each (function(){
				this.reset();
            });
			alert("Cabeçalho registrado com sucesso");
			listaCabecalhos(id,tipo);
		}
		else{
			var modalConfirmacao = new bootstrap.Modal(document.getElementById('avisoModal'), {
				
			})
			modalConfirmacao.show();
			$("#tituloAvisoModal").html('<h5 class="modal-title"><i class="bi bi-exclamation-triangle text-danger"></i> Atenção</h5>');
			$("#corpoAvisoModal").html('<p>Já existe Cabeçalho cadastrado para o mês informado</p>');
			$("#boxBotoesAvisoModal").html('<button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>');
		}
		
	  }
	});
}

function listaCabecalhos(id,tipo){

	if(id>0 || tipo=='celebrante'){
		$.ajax({
			type: "POST",
			url: "public/componentes/cabecalho/model/listaCabecalhos.php",
			data: {'id':id,'tipo':tipo},
			success: function (retorno) {
				$("#linhaCabecalhos").html(retorno);
			}
		});

		$("#boxCadastraCabecalho").slideUp('fast');
		$("#boxCabecalhos").slideDown('fast');
	}
	else{
		if(tipo=="executora"){
			$("#boxCadastraCabecalho").slideUp('fast');
			$("#boxCabecalhos").slideUp('fast');
		}
		else{
			$("#boxCadastraCabecalho").slideUp('fast');
			$("#boxCabecalhos").slideDown('fast');
		}

	}

}

function novoCabecalho(){
	$("#tituloNovoCabecalho").html($('#slcExecutoras option:selected').text());
	$("#boxCadastraCabecalho").slideDown('fast');
	$("#boxCabecalhos").slideUp('fast');
	$("#btnRegistrarCabecalho").prop("disabled","disabled");
	$("#boxSaldoPrestacao").addClass("d-none");
	$('#slcTipoRepasse option:first').prop('selected',true);
	$("#txtMesReferencia").val('');
	$("#txtValorRepasse").val('');
	$("#txtValorRecursosHumanos").val('');
	$("#txtValorCusteio").val('');
	$("#txtValorServicosTerceiros").val('');
	$("#txtValorRecursosHumanos").prop("readonly", false);
	$("#txtValorCusteio").prop("readonly", false);
	$("#txtValorServicosTerceiros").prop("readonly", false);
}

function cancelaCadCabecalho(){
	var executora = $('#slcExecutoras').val();
	
	$("#txtMesReferencia").val('');
	$("#txtValorRepasse").val('');
	$("#txtValorRecursosHumanos").val('');
	$("#txtValorCusteio").val('');
	$("#txtValorServicosTerceiros").val('');
	$("#boxSaldoPrestacao").addClass("d-none");
	
	$("#txtValorRecursosHumanos").prop("readonly", false);
	$("#txtValorCusteio").prop("readonly", false);
	$("#txtValorServicosTerceiros").prop("readonly", false);

	$("#boxCalcRH").removeClass('text-decoration-line-through');
	$("#boxCalcCusteio").removeClass('text-decoration-line-through');
	$("#boxCalcTerceiros").removeClass('text-decoration-line-through');
	listaCabecalhos(executora);
}

function carregaExecutoras(){
	$.ajax({
	  type: "POST",
	  url: "https://portal.seds.sp.gov.br/coed/public/componentes/cabecalho/model/carregaExecutoras.php",
	  success: function (retorno) {
		$("#boxSlcExecutoras").html(retorno);
		$('.form-select').select2({
			placeholder: 'OSC Executora',
			allowClear: false,
			theme:'bootstrap4'});

			$('#select2-slcExecutoras-container').parent().css('padding', '30px');
			$('#select2-slcTiposRepasse-container').parent().css('padding', '29px');
	  }
	});
}

function carregaTiposRepasse(){
	$.ajax({
	  type: "POST",
	  url: "https://portal.seds.sp.gov.br/coed/public/componentes/cabecalho/model/carregaTiposRepasse.php",
	  success: function (retorno) {
		$("#boxSlcTiposRepasse").html(retorno);
	  }
	});
}

function carregaSaldo(data){

	if($("#chkCelebrante").is(':checked')){
		var celebrante = 1;
		var dados = celebrante;
		var pagina = "carregaSaldoCelebrante.php";
	}
	else{
		var executora = $("#slcExecutoras").val();
		var dados = executora;
		var pagina = "carregaSaldoExecutora.php";
	}

	var tipoRepasse = $("#slcTiposRepasse").val();
	$("#txtValorRecursosHumanos").val('');
	$("#txtValorCusteio").val('');
	$("#txtValorServicosTerceiros").val('');
	
	if(tipoRepasse=='1'){
		$("#boxSaldoPrestacao").removeClass("d-none");
	
		$.ajax({
			url:'https://portal.seds.sp.gov.br/coed/public/componentes/cabecalho/model/'+pagina,
			dataType: 'JSON',
			type: 'POST',
			data: {'data':data,'id':dados},
			success: function(resultado){

				$("#boxTotalRHPrevisto").html("<span class='text-secondary' style='font-size:11px;'>(previsto) </span>" + resultado.totalRHPrevisto);
				$("#boxTotalCusteioPrevisto").html(resultado.totalCusteioPrevisto);
				$("#boxTotalTerceirosPrevisto").html(resultado.totalTerceirosPrevisto);
				$("#boxSaldoRH").html("<span class='text-danger' style='font-size:11px;'>(executado) </span>" + resultado.saldoRH + "<span class='text-danger' style='font-size:13px;'> -</span>");
				$("#boxSaldoCusteio").html(resultado.saldoCusteio + "<span class='text-danger' style='font-size:13px;'> -</span>");
				$("#boxSaldoTerceiros").html(resultado.saldoTerceiros + "<span class='text-danger' style='font-size:13px;'> -</span>");
				$("#boxGlosaRH").html("<span class='text-danger' style='font-size:11px;'>(glosado) </span>" + resultado.totalGlosaRH + "<span class='text-danger' style='font-size:13px;'> -</span>");
				$("#boxGlosaCusteio").html(resultado.totalGlosaCusteio + "<span class='text-danger' style='font-size:13px;'> -</span>");
				$("#boxGlosaTerceiros").html(resultado.totalGlosaTerceiros + "<span class='text-danger' style='font-size:13px;'> -</span>");
				$("#boxCalcRH").html("<span class='text-primary' style='font-size:11px;'>(saldo) </span>" + resultado.totalRH);
				$("#boxCalcCusteio").html(resultado.totalCusteio);
				$("#boxCalcTerceiros").html(resultado.totalTerceiros);

				$("#boxPrevistoFixoRH").html("<span class='text-success' style='font-size:11px;'>(previsto Fixo) </span>" + resultado.previstoFixoRH);
				$("#boxPrevistoFixoCusteio").html(resultado.previstoFixoCusteio);
				$("#boxPrevistoFixoTerceiros").html(resultado.previstoFixoTerceiros + "<span class='text-success' style='font-size:13px;'> +</span>");
				$("#boxTotalRH").html("<span class='text-primary' style='font-size:11px;'>(Total) </span>" + resultado.totalPrevistoRH);
				$("#boxTotalCusteio").html(resultado.totalPrevistoCusteio);
				$("#boxTotalTerceiros").html(resultado.totalPrevistoTerceiros);

				calculaRubrica(resultado.totalPrevistoRH , resultado.totalPrevistoCusteio , resultado.totalPrevistoTerceiros);
				
			},
			complete: function(){}
		});
	}
	else{
		$("#btnRegistrarCabecalho").prop("disabled",false);
	}
}

function calculaRubrica(saldoRh , saldoCusteio , saldoTerceiros){

	var rh = $("#txtValorRecursosHumanos").val().replace('R$','');
	var custeio = $("#txtValorCusteio").val().replace('R$','');
	var terceiros = $("#txtValorServicosTerceiros").val().replace('R$','');

	if(rh==""){
		var rh = '0,00';
	}
	if(custeio==""){
		var custeio = '0,00';
	}
	if(terceiros==""){
		var terceiros = '0,00';
	}

	r_rh = rh.replace(/\./g, '').replace(',', '.');
	r_custeio = custeio.replace(/\./g, '').replace(',', '.');
	r_terceiros = terceiros.replace(/\./g, '').replace(',', '.');
	r_saldoRh = saldoRh.replace(/\./g, '').replace(',', '.');
	r_saldoCusteio = saldoCusteio.replace(/\./g, '').replace(',', '.');
	r_saldoTerceiros = saldoTerceiros.replace(/\./g, '').replace(',', '.');

	n_rh = parseFloat(r_rh);
	n_custeio = parseFloat(r_custeio);
	n_terceiros = parseFloat(r_terceiros);
	n_saldoRh = parseFloat(r_saldoRh);
	n_saldoCusteio = parseFloat(r_saldoCusteio);
	n_saldoTerceiros = parseFloat(r_saldoTerceiros);

	totalRh = n_rh + n_saldoRh;
	totalCusteio = n_custeio + n_saldoCusteio;
	totalTerceiros = n_terceiros + n_saldoTerceiros;

	totalRh = totalRh.toLocaleString('pt-br',{minimumFractionDigits: 2,maximumFractionDigits: 2});
	totalCusteio = totalCusteio.toLocaleString('pt-br',{minimumFractionDigits: 2,maximumFractionDigits: 2});
	totalTerceiros = totalTerceiros.toLocaleString('pt-br',{minimumFractionDigits: 2,maximumFractionDigits: 2});

	$("#txtValorRecursosHumanos").val("R$" + totalRh);
	$("#txtValorCusteio").val("R$" + totalCusteio);
	$("#txtValorServicosTerceiros").val("R$" + totalTerceiros);

	$("#txtValorRecursosHumanos").prop("readonly", true);
	$("#txtValorCusteio").prop("readonly", true);
	$("#txtValorServicosTerceiros").prop("readonly", true);
	
	$("#btnRegistrarCabecalho").prop("disabled",false);

}