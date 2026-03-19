$(document).ready(function(){
	
	validaUsuario();
	carregaStatusNotas(0);
	setTimeout('carregaCategorias(0)',500);
	setTimeout('listaNotasFiscais()',300);
	setTimeout('listaApontamentosDocComp()',300);

	$("#txtValorNotaFiscal , #txtValorProvisao , #txtValorGlosa").maskMoney({
		prefix: "R$ ",
		decimal: ",",
		thousands: "."
	});

	$("body").on("click", "#btnAdicionarNotas", function(){
		adicionarNotas();
	});

	$("body").on("click", "#btnDisponibilizaPrestacao", function(){
		criaPergunta(1,0,0);
	});

	$("body").on("click", "#btnLiberaPrestacao", function(){
		liberaPrestacao();
	});

	$('#txtValorProvisao').on('keydown', function(event) {
		if(event.keyCode === 13) {
			registraProvisao();
		}
	});

	$('#txtValorNotaFiscal').on('blur', function(event) {
		validaRubrica();
	});

	$('#slcCategorias').on('change', function(event) {
		validaRubrica();
	});
	
	$("#formNotaFiscal").validate({
		invalidHandler: function() {
			alert('Preencha todos os campos');
		},
		submitHandler: function(){
			$("#boxBotoes").hide();
			setTimeout('cadastraNotaFiscal()',500);
		},
		rules:{
			txtDataNota:{
			  required:true,
			},
			txtNumeroNotaFiscal:{
			  required:true,
			},
			slcCategorias:{
			  required:true,
			},
			slcSubCategorias:{
			  required:true,
			},
			txtValorNotaFiscal:{
				required:true,
			},
			txtDataPagamento:{
				required:true,
			  },
			uplNf:{
				required:true,
			},
  
		},
  
	  }

	);

	$('#uplDoc').on('change',function(){
		
		$('#boxEnvioDocComplementar').html('<div class="spinner-border text-success" role="status"><span class="visually-hidden">Enviando...</span></div>');
		var form = $("#formDocComplementar")[0];
		var file = this.files[0];
	
		var data = new FormData(form);
		data.append('arquivo',file);
	
		$.ajax({
		  type: "POST",
		  enctype: 'multipart/form-data',
		  url: "https://portal.seds.sp.gov.br/coed/public/componentes/notas/model/upload.php",
		  data: data,
		  processData: false,
		  cache: false,
		  contentType: false,
		  success: function (retorno) {
			if(retorno!=0 && retorno!=1){
				$('#boxEnvioDocComplementar').html('<a href="../public/componentes/notas/model/anexos/prestacoes/'+ retorno + '"' + ' target="_blank"><img src="https://portal.seds.sp.gov.br/coed/images/pdf.gif" border="0"></a> <button type="button" id="delDocComplementar" name="delDocComplementar" class="btn btn-danger" onclick=excluirAnexo("../model/anexos/prestacoes/'+ retorno + '",0) data-bs-toggle="tooltip" data-bs-placement="top" title="Excluir arquivo"><i class="bi bi-trash-fill"></i></button>');
			}
			else{
				$('#boxEnvioDocComplementar').html('Erro no envio do arquivo');
			}
		  }
		});

	});
	
})

function validaUsuario(){
	var prestacao = $("#hidIdPrestacao").val();
	$.ajax({
		type: "POST",
		url: "https://portal.seds.sp.gov.br/coed/public/componentes/notas/model/validaUsuario.php",
		data: {'prestacao':prestacao},
		success: function (retorno) {
			if(retorno==0){
				location.href="https://portal.seds.sp.gov.br/coed/prestacoes";
			}
		}
	});
}

function criaPergunta(id,prestacao,nota){
	
	var modalConfirmacao = new bootstrap.Modal(document.getElementById('confirmacaoModal'), {
		keyboard: false
	})

/* 1 - Disponibiliza Prestação para análise da Celebrante | 2 - Exclusão de Nota Fiscal | 3 - Excluir Anexo de Nota Fiscal ou Documento Complementar | 4 - Pre-disponibiliza Prestação para análise do COED */

	switch(id){
		case 1:
			modalConfirmacao.show();
			$("#tituloModal").removeClass("bg-success");
			$("#tituloModal").addClass("bg-warning");
			$("#tituloModal").html('<h5 class="modal-title" id="tituloModal">Confirmação de Disponibilização</h5>');
			$("#corpoModal").html('<p>Confirma a disponibilização dessa Prestação de Contas para análise?</p>');
			$("#boxBotoesModal").html('<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Não Confirmar</button><button type="button" data-bs-dismiss="modal" class="btn btn-success" onclick="disponibilizaPrestacao();">Confirmar</button>');	
		break;
		case 2:
			modalConfirmacao.show();
			$("#tituloModal").removeClass("bg-success");
			$("#tituloModal").addClass("bg-warning");
			$("#tituloModal").html('<h5 class="modal-title" id="tituloModal">Confirmação de Exclusão</h5>');
			$("#corpoModal").html('<p>Deseja realmente excluir a Nota Fiscal?</p>');
			$("#boxBotoesModal").html('<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button><button type="button" data-bs-dismiss="modal" class="btn btn-success" onclick="excluirNotaFiscal('+prestacao+','+nota+')">Confirmar Exclusão</button>');
		break;
		case 3:
			modalConfirmacao.show();
			$("#tituloModal").removeClass("bg-success");
			$("#tituloModal").addClass("bg-warning");
			$("#tituloModal").html('<h5 class="modal-title" id="tituloModal">Confirmação de Exclusão</h5>');
			$("#corpoModal").html('<p>Deseja realmente excluir o arquivo?</p>');
			if(nota==0){
				$("#boxBotoesModal").html('<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button><button type="button" data-bs-dismiss="modal" class="btn btn-success" onclick=excluirAnexo("'+prestacao+'",'+nota+')>Confirmar Exclusão</button>');
			}
			else{
				$("#boxBotoesModal").html('<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button><button type="button" data-bs-dismiss="modal" class="btn btn-success" onclick="excluirAnexo('+prestacao+','+nota+')">Confirmar Exclusão</button>');
			}
		break;
		case 4:
			modalConfirmacao.show();
			$("#tituloModal").removeClass("bg-success");
			$("#tituloModal").addClass("bg-warning");
			$("#tituloModal").html('<h5 class="modal-title" id="tituloModal">Confirmação de Encerramento</h5>');
			$("#corpoModal").html('<p>Confirma o encerramento dessa Prestação de Contas e envio para análise?</p>');
			$("#boxBotoesModal").html('<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Não Confirmar</button><button type="button" data-bs-dismiss="modal" class="btn btn-success" onclick="encerraPrestacao()">Confirmar</button>');	
		break;
		case 5:
			modalConfirmacao.show();
			$("#tituloModal").removeClass("bg-success");
			$("#tituloModal").addClass("bg-warning");
			$("#tituloModal").html('<h5 class="modal-title" id="tituloModal">Confirmação de Finalização</h5>');
			$("#corpoModal").html('<p>Confirma a finalização dessa Prestação de Contas?</p>');
			$("#boxBotoesModal").html('<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Não Confirmar</button><button type="button" data-bs-dismiss="modal" class="btn btn-success" onclick="finalizaPrestacao()">Confirmar</button>');	
		break;
	}


}

function validaRubrica(){
	var prestacao = $("#hidIdPrestacao").val();
	var rubrica = $("#slcCategorias").val();
	var txtRubrica = $("#slcCategorias option:selected").text();
	var valorOriginal = $('#txtValorNotaEdicao').val().replace('R$ ','');
	var valor = $('#txtValorNotaFiscal').val().replace('R$ ','');

	$.ajax({
		type: "POST",
		url: "https://portal.seds.sp.gov.br/coed/public/componentes/notas/model/validaRubrica.php",
		data: {'prestacao':prestacao,'rubrica':rubrica,'valor':valor,'valorOriginal':valorOriginal},
		success: function (retorno) {
			if(retorno==1){
				var modalConfirmacao = new bootstrap.Modal(document.getElementById('confirmacaoModal'), {
					keyboard: false
				})
			
				modalConfirmacao.show();
				$("#tituloModal").removeClass("bg-success");
				$("#tituloModal").addClass("bg-warning");
				$("#tituloModal").html('<h5 class="modal-title" id="tituloModal"><i class="fas fa-exclamation-triangle"></i> ATENÇÃO</h5>');
				$("#corpoModal").html('<p>O valor total executado para '+txtRubrica+' ultrapassará o valor previsto.</p>');
				$("#boxBotoesModal").html('<button type="button" data-bs-dismiss="modal" class="btn btn-warning">OK</button>');
				$('#txtValorNotaFiscal').val('');
			}
		}
	});
}

/*
function validaRubrica_old(){

	//if($("#btnRegistrar").html()=="Registrar NF"){

	var valorOriginal = parseFloat($('#txtValorNotaEdicao').val().replace('R$ ','').replace('.','').replace(',','.'));
	var valor = parseFloat($('#txtValorNotaFiscal').val().replace('R$ ','').replace('.','').replace(',','.'));

	if(valor > valorOriginal || valorOriginal == 'NaN'){

		var rubrica = $("#slcCategorias").val();
		var total = 0;

		if(valor!="" && rubrica!=0){

			switch(rubrica){
				case '1': // Custeio
					var valorPrevisto = $("#boxPrevistoCusteio");
					var valorExecutado = $("#boxExecutadoCusteio");
					var txtRubrica = "Custeio";
				break;
				case '2': // RH
					var valorPrevisto = $("#boxPrevistoRH");
					var valorExecutado = $("#boxExecutadoRH");
					var txtRubrica = "Recursos Humanos";
				break;
				case '3': // Terceiros
					var valorPrevisto = $("#boxPrevistoTerceiros");
					var valorExecutado = $("#boxExecutadoTerceiros");
					var txtRubrica = "Serviços Terceiros";
				break;
			}

			valorPrevisto = parseFloat(valorPrevisto.html().replace('R$ ','').replace('.','').replace('.','').replace(',','.'));
			valorExecutado = parseFloat(valorExecutado.html().replace('R$ ','').replace('.','').replace('.','').replace(',','.'));

			soma = valor + valorExecutado;

			soma1 = (soma.toString().split('.'));

			if(soma1[1] > 0.00){
			
				if(soma1[1].length>2){

					const centavos = soma1[1].substring(0,2);

					soma = soma - parseFloat('0.' + soma1[1]);
					soma = soma + parseFloat('0.' + centavos);

				}

			}

			if((soma) > valorPrevisto){

				var modalConfirmacao = new bootstrap.Modal(document.getElementById('confirmacaoModal'), {
					keyboard: false
				})
			
				modalConfirmacao.show();
				$("#tituloModal").html('<h5 class="modal-title" id="tituloModal"><i class="fas fa-exclamation-triangle"></i> ATENÇÃO</h5>');
				$("#corpoModal").html('<p>O valor total executado para '+txtRubrica+' ultrapassará o valor previsto.</p>');
				$("#boxBotoesModal").html('<button type="button" data-bs-dismiss="modal" class="btn btn-warning">OK</button>');
				$('#txtValorNotaFiscal').val('');

			}
					
		}

	}

	//}

}

*/

function adicionarNotas(){
	$('html, body').animate({ scrollTop: 100 }, 50);
	$("#boxRegistrarNota").removeClass('d-none');
	$('#txtValorNotaEdicao').val("0");
	$('#formNotaFiscal').each (function(){
		this.reset();
	});
	$("#txtDataNota").prop("disabled",false);
	$("#txtNumeroNotaFiscal").prop("disabled",false);
	setTimeout('$("#slcCategorias").prop("disabled",false)','500');
	setTimeout('$("#slcSubcategorias").prop("disabled",false)','500');
	$("#txtValorNotaFiscal").prop("disabled",false);
	$("#txtDataPagamento").prop("disabled",false);
	$("#boxEnvio").html('<input class="form-control p-3" type="file" id="uplNf" name="uplNf"></input>');
	$('#boxTextoApontamento').addClass('d-none');
	$('#boxTextoJustificativa').addClass('d-none');
	$('#boxApontamentos').addClass('d-none');
	$("#boxBotoes").html('<button type="submit" class="btn btn-primary" id="btnRegistrar">Registrar NF</button>  <button type="button" class="btn btn-secondary" onclick="cancelaCadNota(0)">Cancelar</button>');
	carregaCategorias(0);
}

function listaNotasFiscais(){
	
	var prestacao = $("#hidIdPrestacao").val();

	montaCabecalho(prestacao);

	$.ajax({
		type: "POST",
		url: "https://portal.seds.sp.gov.br/coed/public/componentes/notas/model/listaNotasFiscais.php",
		data: {'prestacao':prestacao},
		success: function (retorno) {
		  $("#boxListaNotasFiscais").html(retorno);
		  
		  const dataTable = new simpleDatatables.DataTable("#tblNotasFiscais", {
			fixedHeight: false,
			perPage: 25
		  })
		}
	});

}

function listaItensGlosa(id){

	$.ajax({
		type: "POST",
		url: "https://portal.seds.sp.gov.br/coed/public/componentes/notas/model/listaItensGlosa.php",
		data: {'id':id},
		success: function (retorno) {
			if(retorno!=0){
				$('#boxItensGlosa').removeClass('d-none');
				$('#boxItensGlosa').html(retorno);
			}
			else{
				$('#boxItensGlosa').addClass('d-none');
				$('#boxItensGlosa').html("");
			}
		}
	});

}

function cadastraNotaFiscal(){

	var categoria = $("#slcCategorias").val();
	var subCategoria = $("#slcSubcategorias").val();

	if(categoria==0 || subCategoria==0){
		alert('Preencha todos os campos');
		if(categoria==0){
			$("#slcCategorias").focus();
		}
		else{
			$("#slcSubcategorias").focus();
		}

	}
	else{
		var form = $("#formNotaFiscal")[0];
		var file = $('#uplNf').prop("files")[0];
		var data = new FormData(form);
		data.append('arquivo',file);

		$.ajax({
		type: "POST",
		enctype: 'multipart/form-data',
		url: "https://portal.seds.sp.gov.br/coed/public/componentes/notas/model/cadastraNf.php",
		data: data,
		processData: false,
		cache: false,
		contentType: false,
		success: function (retorno) {
			if(retorno>0){
				$('#formNotaFiscal').each (function(){
					this.reset();
				});
				//alert("Nota Fiscal registrada com sucesso");
				var modalConfirmacao = new bootstrap.Modal(document.getElementById('confirmacaoModal'), {
					keyboard: false
				})
			
				modalConfirmacao.show();
				$("#tituloModal").removeClass("bg-warning");
				$("#tituloModal").addClass("bg-success");
				$("#tituloModal").html('<h5 class="modal-title text-white" id="tituloModal"><i class="fa-solid fa-circle-check" style="color: #ffffff;"></i> SUCESSO</h5>');
				$("#corpoModal").html('<p>Nota Fiscal registrada com sucesso.</p>');
				$("#boxBotoesModal").html('<button type="button" data-bs-dismiss="modal" class="btn btn-success">OK</button>');
				$("#boxBotoes").show();
			}
			atualizaCabecalho(retorno,0,0);
			listaNotasFiscais();
		}
		});

	}
}

function cancelaCadNota(pf){
	if(pf==1 || pf==2){
		$("#boxRegistrarNota").addClass('d-none');
		$("#boxMotivoGlosa").addClass('d-none');
		$("#txtMotivoGlosa").val('');
		$("#boxRessalva").addClass('d-none');
		$("#txtRessalva").val('');
	}
	else{
		$("#boxRegistrarNota").addClass('d-none');
		$("#tituloNf").html('Registrar Nota Fiscal');
		$("#boxBotoes").html('<button type="submit" class="btn btn-primary" id="btnRegistrar">Registrar NF</button>  <button type="button" class="btn btn-secondary" onclick="cancelaCadNota('+pf+')">Cancelar</button>');
		$("#boxEnvio").html('<input class="form-control p-3" type="file" id="uplNf" name="uplNf"></input>');
		$("#boxApontamentos").addClass('d-none');
		$('#formNotaFiscal').each (function(){
			this.reset();
		});
		carregaCategorias(0);
		carregaSubcategorias(0,0);
		$("#tituloNf").html("Registrar Nota Fiscal");
		setTimeout('$("#slcSubcategorias").prop("disabled","disabled")','300');
		$("#boxMotivoGlosa").addClass('d-none');
		$("#txtMotivoGlosa").val('');
		$("#boxRessalva").addClass('d-none');
		$("#txtRessalva").val('');
	}
}

function detalhesNotaFiscal(id){
	$("#tituloNf").html('Alterar Nota Fiscal');
	$("#boxRegistrarNota").removeClass('d-none');
	$('#boxTextoJustificativa').html('');
	$('#boxTextoApontamento').html('');
	$("#boxTextoApontamento1").addClass('d-none');
	$("#boxTextoJustificativa1").addClass('d-none');
	$("#boxTextoMotivoGlosa1").addClass('d-none');
	$.ajax({
		url:'https://portal.seds.sp.gov.br/coed/public/componentes/notas/model/detalhesNotaFiscal.php',
		dataType: 'JSON',
		type: 'POST',
		data: {'id':id},
		success: function(resultado){
			var rotuloAnalisePerfil = resultado.perfil_analise_descricao ? 'An&aacute;lise ' + resultado.perfil_analise_descricao : 'An&aacute;lise';

			// SE EXISTIR A NOTA FISCAL
			if(resultado.nota_fiscal_id>0){

					setTimeout('listaApontamentos('+resultado.nota_fiscal_id+','+resultado.nota_status+')','300');
					listaItensGlosa(resultado.nota_fiscal_id);
					$('html, body').animate({ scrollTop: 100 }, 50);
					$("#txtDataNota").val(resultado.data_nota_fiscal);
					$("#txtNumeroNotaFiscal").val(resultado.numero_nota_fiscal);
					setTimeout('carregaCategorias('+resultado.categoria_id+')','300');
					setTimeout('carregaSubcategorias('+resultado.subcategoria_id+','+resultado.categoria_id+')','300');
					$("#txtValorNotaEdicao").val(resultado.valor_nota);
					$("#txtValorNotaFiscal").val(resultado.valor_nota);
					$("#txtDataPagamento").val(resultado.data_pagamento);
					if(resultado.nota_status!=3 && resultado.perfil_logado_id!=4){
						carregaStatusNotas(resultado.nota_status);
					}

					//SE EXISTIR ARQUIVO DA NOTA FISCAL
					if(resultado.arquivo==1){
						$("#boxEnvio").html('<a href="../public/componentes/notas/model/anexos/'+ resultado.prestacao_id + '/' + resultado.nota_fiscal_id + '.pdf"' + ' target="_blank"><img src="https://portal.seds.sp.gov.br/coed/images/pdf.gif" border="0"></a> <button type="button" id="delArquivo" name="delArquivo" class="btn btn-danger" onclick="criaPergunta(3 , '+resultado.prestacao_id+' , '+ resultado.nota_fiscal_id +')" data-bs-toggle="tooltip" data-bs-placement="top" title="Excluir anexo"><i class="bi bi-trash-fill"></i></button>');
					}
					//SE NÃO EXISTIR NOTA FISCAL
					else{
						//SE A NOTA ESTIVER COM STATUS EDITAR
						if(resultado.nota_status==2){
							$("#boxEnvio").html('<input class="form-control p-3" type="file" id="uplNf" name="uplNf"></input>');
							$("#tituloNf").html("Editar Nota Fiscal");
						}
						else{
							$("#boxEnvio").html('-');
						}
					}
					
					//SE O PERFIL DO USUÁRIO LOGADO FOR DIFERENTE DE VISUALIZADOR
					if(resultado.perfil_logado_id != 6){
						$("#boxBotoes").html('<button type="button" class="btn btn-success" id="btnEditar">Registrar Informações</button> <button type="button" class="btn btn-secondary" onclick="cancelaCadNota('+resultado.perfil_logado_id+')">Cancelar</button>');
						//SE A NOTA ESTIVER COM STATUS EDITAR
						//if(resultado.nota_status==2){
							$("#btnEditar").click(function() {editaNotaFiscal(0 , resultado.nota_fiscal_id)});
						//}

					}
					
					// SE O PERFIL FOR COED OU CELEBRANTE
					if(resultado.perfil_logado_id==1 || resultado.perfil_logado_id==2){

						if(resultado.nota_status==3){
							$("#boxAnaliseCoed").html('<div class="form-check form-switch"><input class="form-check-input" type="checkbox" role="switch" id="chkAnaliseCoed'+resultado.nota_fiscal_id+'" name="chkAnaliseCoed'+resultado.nota_fiscal_id+'" disabled><label class="form-check-label" for="chkAnaliseCoed'+resultado.nota_fiscal_id+'">'+rotuloAnalisePerfil+'</label></div>');
						}
						else{
							$("#boxAnaliseCoed").html('<div class="form-check form-switch"><input class="form-check-input" type="checkbox" role="switch" id="chkAnaliseCoed'+resultado.nota_fiscal_id+'" name="chkAnaliseCoed'+resultado.nota_fiscal_id+'"><label class="form-check-label" for="chkAnaliseCoed'+resultado.nota_fiscal_id+'">'+rotuloAnalisePerfil+'</label></div>');
							$("#chkAnaliseCoed"+resultado.nota_fiscal_id).change(function() {marcaAnaliseCoed(resultado.nota_fiscal_id)});
						}
						
						if(resultado.analise_coed==1){
							
							$("#chkAnaliseCoed"+resultado.nota_fiscal_id).prop("checked",true);

							if(resultado.perfil_logado_id==2){
								setTimeout('$("#slcNotasStatus").prop("disabled",true)',100);
								setTimeout('$("#btnDefineStatus").addClass("d-none")',100);
								$("#boxCampoApontamento").addClass("d-none");
							}

						}
						else{
							$("#chkAnaliseCoed"+resultado.nota_fiscal_id).prop("checked",false);
						}

						$("#txtDataNota").prop("disabled","disabled");
						$("#txtNumeroNotaFiscal").prop("disabled","disabled");
						setTimeout('$("#slcCategorias").prop("disabled","disabled")','500');
						//setTimeout('$("#slcSubcategorias").prop("disabled","disabled")','500');
						$("#txtValorNotaFiscal").prop("disabled","disabled");
						$("#txtDataPagamento").prop("disabled","disabled");
						$("#uplNf").prop("disabled","disabled");
						$("#delArquivo").hide();

						$("#boxBotoes").html('<button type="button" class="btn btn-success" id="btnDefineStatus">Registrar Status</button> <button type="button" class="btn btn-secondary" onclick="cancelaCadNota('+resultado.perfil_id+')">Cancelar</button>');
						$("#btnDefineStatus").click(function() {registraStatus(resultado.nota_fiscal_id)});

						
						// SE O STATUS DA NOTA FOR DIFERENTE DE APROVADO, PRÉ-APROVADO E GLOSADO (STATUS É IGUAL A AGUARDANDO APROVAÇÃO, EDITAR, EXCLUÍDO OU GLOSADO PARCIALMENTE)
						if(resultado.nota_status!=3 && resultado.nota_status!=6 && resultado.nota_status!=4 && resultado.nota_status!=8){
							
							if(resultado.celebrante_id>0){ //SE A NOTA FOR DA CELEBRANTE

								if(resultado.perfil_logado_id == 2){ // SE O PERFIL LOGADO FOR CELEBRANTE, ABRE OS CAMPOS PARA EDIÇÃO
									$("#txtDataNota").prop("disabled",false);
									$("#txtNumeroNotaFiscal").prop("disabled",false);
									setTimeout('$("#slcCategorias").prop("disabled",false)','500');
									setTimeout('$("#slcSubcategorias").prop("disabled",false)','500');
									$("#txtValorNotaFiscal").prop("disabled",false);
									$("#txtDataPagamento").prop("disabled",false);
									$("#uplNf").prop("disabled",false);
									
									if(resultado.arquivo==1){
										$("#boxEnvio").html('<a href="../public/componentes/notas/model/anexos/'+ resultado.prestacao_id + '/' + resultado.nota_fiscal_id + '.pdf"' + ' target="_blank"><img src="https://portal.seds.sp.gov.br/coed/images/pdf.gif" border="0"></a> <button type="button" id="delArquivo" name="delArquivo" class="btn btn-danger" onclick="criaPergunta(3 , '+resultado.prestacao_id+' , '+ resultado.nota_fiscal_id +')" data-bs-toggle="tooltip" data-bs-placement="top" title="Excluir anexo"><i class="bi bi-trash-fill"></i></button>');
									}
									else{
										$("#boxEnvio").html('<input class="form-control p-3" type="file" id="uplNf" name="uplNf"></input>');
									}
								}
								else{ // SE O PERFIL LOGADO FOR DIFERENTE DE CELEBRANTE, MANTEM OS CAMPOS BLOQUEADOS
									if(resultado.arquivo==1){
										$("#boxEnvio").html('<a href="../public/componentes/notas/model/anexos/'+ resultado.prestacao_id + '/' + resultado.nota_fiscal_id + '.pdf"' + ' target="_blank"><img src="https://portal.seds.sp.gov.br/coed/images/pdf.gif" border="0"></a> ');
									}
									else{
										$("#boxEnvio").html('');
									}
								}

								if(resultado.perfil_logado_id == 1){ // SE O PERFIL LOGADO FOR COED
									carregaStatusNotas(resultado.nota_status);
									$("#boxBotoes").html('<button type="button" class="btn btn-success" id="btnDefineStatus">Registrar Status</button> <button type="button" class="btn btn-secondary" onclick="cancelaCadNota('+resultado.perfil_id+')">Cancelar</button>');
									$("#btnDefineStatus").click(function() {registraStatus(resultado.nota_fiscal_id)});
								}
								else{ // SE O PERFIL LOGADO NÃO FOR COED (É CELEBRANTE), MOSTRA O STATUS DA NOTA
									$("#boxStatusNotas").html('<p class="p-2">Status da nota: ' + resultado.nota_status_descricao + '</p>');
								}
								
								$("#boxStatusNotas").removeClass('d-none');
								
								if(resultado.nota_apontamento_id>0){ // SE HOUVER APONTAMENTO NA NOTA DA CELEBRANTE

									// SE NÃO EXISTIR JUSTIFICATIVA PARA APONTAMENTO DA NOTA, MOSTRAR O APONTAMENTO E LIBERAR CAMPO PARA REGISTRO DE JUSTIFICATIVA

									$("#boxBotoes").html('<button type="button" class="btn btn-success" id="btnDefineStatus">Registrar Status</button> <button type="button" class="btn btn-secondary" onclick="cancelaCadNota('+resultado.perfil_id+')">Cancelar</button>');
									$("#btnDefineStatus").click(function() {registraStatus(resultado.nota_fiscal_id)});
			
								}
								//SE NÃO EXISTIR APONTAMENTO
								else{
									// ABRE CAMPO PARA APONTAMENTO
								}


							}
							else{// SE A NOTA NÃO FOR DA CELEBRANTE

								if(resultado.perfil_logado_id==2 && resultado.nota_status==2){
									//FECHAR CAMPO DE ALTERAÇÃO DE STATUS
									$("#boxStatusNotas").addClass('d-none');
									$("#btnDefineStatus").addClass('d-none');
									
								}
							
								// SE EXISTIR APONTAMENTO REGISTRADO PARA A NOTA
								if(resultado.nota_apontamento_id>0){
									
									//MOSTRA APONTAMENTO
								
								
								}
								// SE NÃO EXISTIR APONTAMENTO PARA A NOTA
								else{

									// ABRE CAMPO PARA REGISTRAR APONTAMENTO

								}
							}

						}
						else if(resultado.nota_status==6 && resultado.perfil_logado_id==1){ //SE STATUS É IGUAL A PRÉ-APROVADO E PERFIL LOGADO FOR COED
							carregaStatusNotas(resultado.nota_status);
						}
						else if(resultado.nota_status==4 || resultado.nota_status==7){ //SE STATUS É IGUAL A GLOSADO OU GLOSA PARCIAL, ABRE BOX DE MOTIVO DA GLOSA
							$("#boxStatusNotas").html('<p class="p-2">Status da nota: ' + resultado.nota_status_descricao + '</p>');
							$("#boxMotivoGlosa").removeClass('d-none');

							if(resultado.nota_status==7){//SE STATUS É IGUAL A GLOSA PARCIAL, ABRE BOX DOS VALORES GLOSADOS
								$("#boxValorGlosa").removeClass('d-none');
							}

							$("#boxCampoMotivoGlosa").addClass('d-none');
							$('#boxTextoMotivoGlosa').html('<span class="text-body">Motivo da Glosa:</span> ' + resultado.motivo_glosa_descricao);
							//************* */
							$("#boxTextoMotivoGlosa1").removeClass('d-none');
							$('#textoMotivoGlosa').html(resultado.motivo_glosa_descricao);
							$('#dataMotivoGlosa').html(resultado.data_motivo_glosa);
							//************ */

							$("#boxBotoes").html('<button type="button" class="btn btn-secondary" onclick="cancelaCadNota('+resultado.perfil_logado_id+')">Cancelar</button>');
						}
						else if(resultado.nota_status==3){ //SE STATUS É IGUAL A APROVADO
							$("#boxStatusNotas").html('<p class="p-2">Status da nota: ' + resultado.nota_status_descricao + '</p>');
						}
						else if(resultado.nota_status==8){ //SE STATUS É IGUAL A APROVADO COM RESSALVA, ABRE BOX DE RESSALVA
							setTimeout('listaRessalvas('+resultado.nota_fiscal_id+','+resultado.nota_status+')','300');
							$("#boxStatusNotas").html('<p class="p-2">Status da nota: ' + resultado.nota_status_descricao + '</p>');
							$("#boxRessalva").removeClass('d-none');

							$("#boxCampoRessalva").addClass('d-none');
							$('#boxTextoRessalva').html('<span class="text-body">Ressalva:</span> ' + resultado.ressalva_descricao);
							//************* */
							$("#boxTextoRessalva").removeClass('d-none');
							$('#textoMotivoGlosa').html(resultado.ressalva_descricao);
							$('#dataMotivoGlosa').html(resultado.data_ressalva);
							//************ */

							$("#boxBotoes").html('<button type="button" class="btn btn-secondary" onclick="cancelaCadNota('+resultado.perfil_logado_id+')">Cancelar</button>');
						}
						else{ // SE STATUS É IGUAL A AGUARDANDO APROVAÇÃO OU EDITAR, MOSTRA STATUS DA NOTA
							$("#boxStatusNotas").html('<p class="p-2">Status da nota: ' + resultado.nota_status_descricao + '</p>');
							$("#boxBotoes").html('<button type="button" class="btn btn-secondary" onclick="cancelaCadNota('+resultado.perfil_logado_id+')">Cancelar</button>');
						}
					}
					// SE O PERFIL FOR DIFERENTE DE COED OU CELEBRANTE
					else{
						// SE O STATUS DA NOTA FOR DIFERENTE DE 'EDITAR', BLOQUEIA OS CAMPOS
						if(resultado.nota_status!=2){
							if(resultado.prestacao_disponibilizada==1){
								$("#boxStatusNotas").html('<p class="p-2">Status da nota: ' + resultado.nota_status_descricao + '</p>');
								$("#txtDataNota").prop("disabled","disabled");
								$("#txtNumeroNotaFiscal").prop("disabled","disabled");
								setTimeout('$("#slcCategorias").prop("disabled","disabled")','500');
								setTimeout('$("#slcSubcategorias").prop("disabled","disabled")','500');
								$("#txtValorNotaFiscal").prop("disabled","disabled");
								$("#txtDataPagamento").prop("disabled","disabled");
								$("#uplNf").prop("disabled","disabled");
								$("#delArquivo").hide();
								$("#boxBotoes").html('<button type="button" class="btn btn-secondary" onclick="cancelaCadNota('+resultado.perfil_logado_id+')">Cancelar</button>');
							}
							else{
								$("#boxStatusNotas").html('<p class="p-2">Status da nota: ' + resultado.nota_status_descricao + '</p>');
								$("#txtDataNota").prop("disabled",false);
								$("#txtNumeroNotaFiscal").prop("disabled",false);
								setTimeout('$("#slcCategorias").prop("disabled",false)','500');
								setTimeout('$("#slcSubcategorias").prop("disabled",false)','500');
								$("#txtValorNotaFiscal").prop("disabled",false);
								$("#txtDataPagamento").prop("disabled",false);
								$("#uplNf").prop("disabled",false);	

							}
						}
						else{
							$("#boxStatusNotas").html('<p class="p-2">Status da nota: ' + resultado.nota_status_descricao + '</p>');
							$("#txtDataNota").prop("disabled",false);
							$("#txtNumeroNotaFiscal").prop("disabled",false);
							setTimeout('$("#slcCategorias").prop("disabled",false)','500');
							setTimeout('$("#slcSubcategorias").prop("disabled",false)','500');
							$("#txtValorNotaFiscal").prop("disabled",false);
							$("#txtDataPagamento").prop("disabled",false);
							$("#uplNf").prop("disabled",false);
							$("#boxBtnJustificativa").html('');

						}
						
						// SE EXISTIR APONTAMENTO REGISTRADO PARA A NOTA
						if(resultado.nota_apontamento_id>0){

							// SE EXISTIR JUSTIFICATIVA PARA APONTAMENTO DA NOTA, MOSTRAR APONTAMENTO E JUSTIFICATIVA
							if(resultado.nota_justificativa_id>0){
								// MOSTRA JUSTIFICATIVA
								$("#boxStatusNotas").removeClass('d-none');
								$("#boxStatusNotas").html('<p class="p-2">Status da nota: ' + resultado.nota_status_descricao + '</p>');
							}
							// SE NÃO EXISTIR JUSTIFICATIVA PARA APONTAMENTO DA NOTA, MOSTRAR O APONTAMENTO E LIBERAR CAMPO PARA REGISTRO DE JUSTIFICATIVA
							else{
								
									if(resultado.perfil_logado_id != 6){
										$("#boxBotoes").html('<button type="button" class="btn btn-success" id="btnEditar">Registrar Informações</button> <button type="button" class="btn btn-secondary" onclick="cancelaCadNota('+resultado.perfil_logado_id+')">Cancelar</button>');
									}
									if(resultado.nota_status==2){
										$("#btnEditar").click(function() {editaNotaFiscal(1,resultado.nota_fiscal_id);registraJustificativa(resultado.nota_apontamento_id,resultado.nota_fiscal_id)});
									}
									else{
										$("#btnEditar").click(function() {editaNotaFiscal(0 , resultado.nota_fiscal_id)});
									}
								
							}

						}
						//SE NÃO EXISTIR APONTAMENTO
						else{

							$("#boxStatusNotas").html('<p class="p-2">Status da nota: ' + resultado.nota_status_descricao + '</p>');

						}

						if(resultado.motivo_glosa_id>0 && resultado.valor_glosa_parcial==""){
							$("#boxMotivoGlosa").removeClass('d-none');
							$("#boxCampoMotivoGlosa").addClass('d-none');
							$('#boxTextoMotivoGlosa').html('<span class="text-body">Motivo da Glosa:</span> ' + resultado.motivo_glosa_descricao);
							//************* */
							$("#boxTextoMotivoGlosa1").removeClass('d-none');
							$('#textoMotivoGlosa').html(resultado.motivo_glosa_descricao);
							$('#dataMotivoGlosa').html(resultado.data_motivo_glosa);
							//************ */

						}

					}	
				
			}
			// SE NÃO EXISTIR NOTA FISCAL
			else{
				if(resultado.perfil_logado_id != 6){
					$("#boxBotoes").html('<button type="submit" class="btn btn-success" id="btnRegistrar">Registrar NF</button>');
				}
			}

			if(resultado.perfil_logado_id == 6){

				$("#btnRegistrar").addClass('d-none');
				$("#delArquivo").addClass('d-none');
				$("#txtDataNota").prop("disabled","disabled");
				$("#txtNumeroNotaFiscal").prop("disabled","disabled");
				setTimeout('$("#slcCategorias").prop("disabled","disabled")','500');
				setTimeout('$("#slcSubcategorias").prop("disabled","disabled")','500');
				$("#txtValorNotaFiscal").prop("disabled","disabled");
				$("#txtDataPagamento").prop("disabled","disabled");
				$("#uplNf").prop("disabled","disabled");
				$("#delArquivo").hide();
				$("#boxCampoApontamento").addClass('d-none');
				$("#boxCampoJustificativa").addClass('d-none');
				$("#boxMotivoGlosa").addClass('d-none');
				
			}
		},
		complete: function(){}
	 });
}

function validaStatusNotasFiscais(perfil,status){
	if(perfil!=1){
		if(status==0){
			$("#boxDisponibilizaPrestacao").removeClass('d-none');
		}
	}
}

function editaNotaFiscal(tipo,id){

	var categoria = $("#slcCategorias").val();
	var subCategoria = $("#slcSubcategorias").val();

	if(categoria==0 || subCategoria==0 || $("#txtDataNota").val()=="" || $("#txtNumeroNotaFiscal").val()=="" || $("#txtValorNotaFiscal").val()=="" || $("#uplNf").val()==""){
		alert('Preencha todos os campos');
		if(categoria==0){
			$("#slcCategorias").focus();
		}
		else{
			$("#slcSubcategorias").focus();
		}

	}
	else if(tipo != 0 && $("#txtJustificativa").val()==""){
		alert('Informe a justificativa');
	}
	else{
	
		var form = $("#formNotaFiscal")[0];
		
		if($('#uplNf').prop("files")){
			var file = $('#uplNf').prop("files")[0];
		}
		else{
			file = 0;
		}

		var data = new FormData(form);
		data.append('arquivo',file);
		data.append('id',id);

		$.ajax({
			enctype: 'multipart/form-data',
			type: "POST",
			url: "https://portal.seds.sp.gov.br/coed/public/componentes/notas/model/editaNotaFiscal.php",
			data: data,
			processData: false,
			cache: false,
			contentType: false,
			success: function (retorno){
				
				$("#boxApontamentos").addClass('d-none');
				$("#boxBotoes").html('<button type="submit" class="btn btn-primary" id="btnRegistrar">Registrar NF</button>');
				
				
				var modalConfirmacao = new bootstrap.Modal(document.getElementById('confirmacaoModal'), {
					keyboard: false
				})
			
				modalConfirmacao.show();
				$("#tituloModal").removeClass("bg-warning");
				$("#tituloModal").addClass("bg-success");
				$("#tituloModal").html('<h5 class="modal-title text-white" id="tituloModal"><i class="fa-solid fa-circle-check" style="color: #ffffff;"></i> SUCESSO</h5>');
				$("#corpoModal").html('<p>Informações alteradas com sucesso.</p>');
				$("#boxBotoesModal").html('<button type="button" data-bs-dismiss="modal" class="btn btn-success">OK</button>');
				
				
				$("#boxEnvio").html('<input class="form-control p-3" type="file" id="uplNf" name="uplNf"></input>');
				$('#formNotaFiscal').each (function(){
					this.reset();
				});
				atualizaCabecalho(id,retorno,0);
				listaNotasFiscais();
				cancelaCadNota(0);
			}
		});
	}
}

function registraApontamento(id){
	var apontamento = $("#txtApontamento").val();
	var status = $("#slcNotasStatus").val();

	if(apontamento==""){
		alert("Preencha o campo Apontamento");
	}
	else{
		$.ajax({
			type: "POST",
			url: "https://portal.seds.sp.gov.br/coed/public/componentes/notas/model/cadastraApontamento.php",
			data: {'id':id,'apontamento':apontamento,'status':status},
			success: function () {
				detalhesNotaFiscal(id);
				listaNotasFiscais();
				$("#boxRegistrarNota").addClass('d-none');
				$("#txtApontamento").val('');
				alert('Apontamento registrado');
			}
		});
	}
}

function registraJustificativa(nota_apontamento_id,id){
	var justificativa = $("#txtJustificativa").val();
	
	if(justificativa==""){
		//alert("Preencha o campo Justificativa"); -- Já está sendo informado na função editarNotafiscal
	}
	else{
		$.ajax({
			type: "POST",
			url: "https://portal.seds.sp.gov.br/coed/public/componentes/notas/model/cadastraJustificativa.php",
			data: {'nota_apontamento_id':nota_apontamento_id,'id':id,'justificativa':justificativa},
			success: function () {
				
				listaNotasFiscais();
				$("#boxCampoJustificativa").addClass('d-none');
				$("#boxTextoJustificativa").removeClass('d-none');
				$('#boxTextoJustificativa').html('<span class="text-body">Justificativa:</span> ' + justificativa);
				$("#boxBtnJustificativa").html('');
				$("#txtJustificativa").val('');
				alert('Justificativa registrada');
			}
		});
	}
}

function listaApontamentos(id,status){
	$.ajax({
		type: "POST",
		url: "https://portal.seds.sp.gov.br/coed/public/componentes/notas/model/listaApontamentosJustificativas.php",
		data: {'id':id,'status':status},
		success: function (retorno) {
		  //$("#boxApontamentos").html(retorno);
		  $("#boxTeste").html(retorno);
		}
	});

}

function listaRessalvas(id,status){
	$.ajax({
		type: "POST",
		url: "https://portal.seds.sp.gov.br/coed/public/componentes/notas/model/listaRessalvas.php",
		data: {'id':id,'status':status},
		success: function (retorno) {
			$("#boxRessalva").removeClass('d-none');
			$("#boxRessalva").html(retorno);
		}
	});
}

function registraStatus(id){
	var status = $("#slcNotasStatus").val();
	var motivo = "";
	var valorGlosa = "";//VALOR DA GLOSA PARCIAL
	var ressalva = "";

	if(status==0){
		alert('Selecione um status para esse lançamento');
	}
	else{
	
		if(status==4){
			motivo = $("#txtMotivoGlosa").val();
		}
		else if(status==7){ //GLOSA PARCIAL
			motivo = $("#txtMotivoGlosa").val();
			valorGlosa = $("#txtValorGlosa").val();
		}
		if(status==8){
			ressalva = $("#txtRessalva").val();
		}
		else{
		}

		if(motivo=="" && (status==4 || status==7)){
			alert("Por favor, informe o motivo da glosa");
		}
		else{
		
			$.ajax({
				type: "POST",
				url: "https://portal.seds.sp.gov.br/coed/public/componentes/notas/model/registraStatus.php",
				data: {'id':id,'status':status,'motivo':motivo,'valorGlosa':valorGlosa,'ressalva':ressalva},
				success: function (retorno) {
					atualizaCabecalho(id,status,valorGlosa);
					cancelaCadNota(0);
					listaNotasFiscais();
					alert('Status Definido com sucesso');
				}
			});
		
		}
	}

}

function excluirAnexo(prestacao,nota){
	$.ajax({
		type: "POST",
		url: "https://portal.seds.sp.gov.br/coed/public/componentes/notas/model/excluirArquivo.php",
		data: {'prestacao':prestacao , 'nota':nota},
		success: function (retorno) {
			if(retorno==0){
				$("#boxEnvio").html('<input class="form-control p-3" type="file" id="uplNf" name="uplNf"></input>');
			}
			else{
				$('#boxEnvioDocComplementar').html('<input class="form-control p-3" type="file" id="uplDoc" name="uplDoc">');

				$('#uplDoc').on('change',function(){
		
					$('#boxEnvioDocComplementar').html('<div class="spinner-border text-success" role="status"><span class="visually-hidden">Enviando...</span></div>');
					var form = $("#formDocComplementar")[0];
					var file = this.files[0];
				
					var data = new FormData(form);
					data.append('arquivo',file);
				
					$.ajax({
					  type: "POST",
					  enctype: 'multipart/form-data',
					  url: "https://portal.seds.sp.gov.br/coed/public/componentes/notas/model/upload.php",
					  data: data,
					  processData: false,
					  cache: false,
					  contentType: false,
					  success: function (retorno) {
						if(retorno!=0 && retorno!=1){
							$('#boxEnvioDocComplementar').html('<a href="../public/componentes/notas/model/anexos/prestacoes/'+ retorno + '"' + ' target="_blank"><img src="https://portal.seds.sp.gov.br/coed/images/pdf.gif" border="0"></a> <button type="button" id="delDocComplementar" name="delDocComplementar" class="btn btn-danger" onclick=excluirAnexo("../model/anexos/prestacoes/'+ retorno + '",0) data-bs-toggle="tooltip" data-bs-placement="top" title="Excluir arquivo"><i class="bi bi-trash-fill"></i></button>');
						}
						else{
							$('#boxEnvioDocComplementar').html('Erro no envio do arquivo');
						}
					  }
					});
			
				});
				
			}
		}
	  });
}

function carregaCategorias(id){
	$.ajax({
	  type: "POST",
	  url: "https://portal.seds.sp.gov.br/coed/public/componentes/notas/model/carregaCategorias.php",
	  data: {'id':id},
	  success: function (retorno) {
		$("#boxCategorias").html(retorno);
	  }
	});
}

function carregaSubcategorias(id,categoria){
	$.ajax({
	  type: "POST",
	  url: "https://portal.seds.sp.gov.br/coed/public/componentes/notas/model/carregaSubcategorias.php",
	  data: {'id':id , 'categoria_id':categoria},
	  success: function (retorno) {
		$("#boxSubcategorias").html(retorno);
	  }
	});
}

function carregaStatusNotas(id){
	$.ajax({
	  type: "POST",
	  url: "https://portal.seds.sp.gov.br/coed/public/componentes/notas/model/carregaStatusNotas.php",
	  data: {'id':id},
	  success: function (retorno) {
		$("#boxStatusNotas").html(retorno);
	  }
	});
}

function atualizaCabecalho(id,retorno,valorGlosa){

	var arrayRetorno = $.parseJSON(retorno);

	console.log("Retorno: " + retorno + " - ");
	
	console.log(arrayRetorno[0] + ' - ' + arrayRetorno[1] + ' - ' + arrayRetorno[2]);

	if(arrayRetorno.length>=2) {
		var categoria = arrayRetorno[0];
		var status = arrayRetorno[1];
		var valor = arrayRetorno[2];
	}
	else
	{
		var status = retorno;
		categoria = "";
		valor = "";
	}

	$.ajax({
		type: "POST",
		url: "https://portal.seds.sp.gov.br/coed/public/componentes/notas/model/atualizaCabecalho.php",
		data: {'id':id,'status':status,'categoria':categoria,'valor':valor,'valorGlosa':valorGlosa},
		success: function (retorno) {
			setTimeout('montaCabecalho("'+retorno+'")',500);
		}
	  });
}

function montaCabecalho(id){

	$.ajax({
		url:'https://portal.seds.sp.gov.br/coed/public/componentes/notas/model/carregaCabecalho.php',
		dataType: 'JSON',
		type: 'POST',
		data: {'id':id},
		success: function(resultado){

			if(resultado.tipoPrestacao==4){
				$("#boxCabecalhoInfoOsc").removeClass("d-none");
				$('#infoOsc').html(resultado.localNome);
				if(resultado.celebrante_id>0){
					if(resultado.prestacaoDisponibilizada==1){
						$("#btnAdicionarNotas").addClass("d-none");
					}
					else{
						if(resultado.perfil!=6){
							setTimeout('$("#btnAdicionarNotas").removeClass("d-none")',500);
						}
					}
				}
			}
			else{
				$("#boxCabecalho").removeClass("d-none");
				$('#nomeOsc').html(resultado.localNome);
				$('#boxTipoRepasse').html(resultado.tipo_repasse_descricao);
				$('#boxMesReferencia').html(resultado.cabecalho_mes_referencia);
				$('#boxVagasDisponibilizadas').html(resultado.executora_vagas);
				
				if(resultado.perfil==4 || (resultado.perfil==2 && resultado.celebrante_id>0)){
					if(resultado.valor_provisao=='0,00'){
						$("#txtValorProvisao").removeClass("d-none")
					}
					else{
						$('#boxProvisao').html('<strong>Provisão:</strong> <span id="boxProvisao">'+resultado.valor_provisao+'</span>');
					}
					
				}
				else{
					$('#boxProvisao').html('<strong>Provisão:</strong> <span id="boxProvisao">'+resultado.valor_provisao+'</span>');
				}
				
				$('#boxValorRepasse').html(resultado.valor_repasse);
				$('#boxValorExecutado').html(resultado.valor_executado);
				$('#boxValorGlosado').html(resultado.valor_glosado);
				$('#boxValorNaoExecutado').html(resultado.valor_nao_executado);
				$('#boxPrevistoRH').html(resultado.recursos_humanos_previsto);
				$('#boxExecutadoRH').html(resultado.recursos_humanos_executado);
				$('#boxPrevistoCusteio').html(resultado.custeio_previsto);
				$('#boxExecutadoCusteio').html(resultado.custeio_executado);
				$('#boxPrevistoTerceiros').html(resultado.servicos_terceiros_previsto);
				$('#boxExecutadoTerceiros').html(resultado.servicos_terceiros_executado);
				$('#txtPrevistoRH').val(resultado.recursos_humanos_previsto);
				$('#txtExecutadoRH').val(resultado.recursos_humanos_executado);
				$('#txtPrevistoCusteio').val(resultado.custeio_previsto);
				$('#txtExecutadoCusteio').val(resultado.custeio_executado);
				$('#txtPrevistoTerceiros').val(resultado.servicos_terceiros_previsto);
				$('#txtExecutadoTerceiros').val(resultado.servicos_terceiros_executado);
				if(resultado.celebrante_id>0){
					if(resultado.prestacaoDisponibilizada==1){
						$("#btnAdicionarNotas").addClass("d-none");
					}
					else{
						if(resultado.perfil!=6){
							setTimeout('$("#btnAdicionarNotas").removeClass("d-none")',500);
						}
					}
				}
			}

			if(resultado.perfil==6){
				$("#btnAdicionarNotas").addClass("d-none");
				$("#boxTxtMensagem").addClass("d-none");
				$("#btnEnviarMensagem").addClass("d-none");
				$("#btnDisponibilizaPrestacao").addClass("d-none");
				$("#uplDoc").addClass("d-none");
			}

			if(resultado.logado==1 || resultado.logado==189 || resultado.logado==139 || resultado.logado==217){
				$("#tituloModal").removeClass("bg-success");
				$("#tituloModal").addClass("bg-warning");
				$("#boxAlteraPrevisto").html('<p class="text-end"><button type="button" class="btn btn-warning" onclick="abreCamposRubrica()"><i class="fas fa-random"></i> Alterar Valor Previsto</button></p>');
				$("#tituloModal").html('<h5 class="modal-title" id="tituloModal"><i class="fas fa-random"></i> Alteração de Valores Previsto</h5>');
				$("#corpoModal").html('<div class="col-md-12">	<div class="form-floating">	  <input type="text" class="form-control" id="txtValorPrevistoRh" name="txtValorPrevistoRh" value="R$ '+resultado.recursos_humanos_previsto+'" placeholder="Valor Previsto Recursos Humanos">	  <label for="txtValorPrevistoRh">Valor Previsto RH</label>	</div>  </div>  <div class="col-md-12 mt-3">	<div class="form-floating">	  <input type="text" class="form-control" id="txtValorPrevistoCusteio" name="txtValorPrevistoCusteio" value="R$ '+resultado.custeio_previsto+'" placeholder="Valor Previsto Custeio">	  <label for="txtValorPrevistoCusteio">Valor Previsto Custeio</label>	</div>  </div>  <div class="col-md-12 mt-3">	<div class="form-floating">	  <input type="text" class="form-control" id="txtValorPrevistoTerceiros" name="txtValorPrevistoTerceiros" value="R$ '+resultado.servicos_terceiros_previsto+'" placeholder="Valor Previsto Seriços Terceiros">	  <label for="txtValorPrevistoTerceiros">Valor Previsto Terceiros</label>	</div>  </div>');
				$("#boxBotoesModal").html('<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button><button type="button" data-bs-dismiss="modal" class="btn btn-success" onclick="alteraRubricaPrevista('+resultado.cabecalho_id+');">Alterar</button>');

				$("#txtValorPrevistoRh , #txtValorPrevistoCusteio , #txtValorPrevistoTerceiros").maskMoney({
					prefix: "R$ ",
					decimal: ",",
					thousands: "."
				});
			}

		},
		complete: function(){}
	 });

}

function registraProvisao(){
	var provisao = $("#txtValorProvisao").val();
	var id = $("#hidIdPrestacao").val();
	$.ajax({
		type: "POST",
		url: "https://portal.seds.sp.gov.br/coed/public/componentes/notas/model/registraProvisao.php",
		data: {'id':id,'provisao':provisao},
		success: function () {
			montaCabecalho(id);
		}
	  });
}

function carregaDocumentoComplementar(retorno,perfil,id){
	
	if(perfil==1 || perfil==6 || (perfil==2 && id==0)){
		$('#boxEnvioDocComplementar').html('<a href="../public/componentes/notas/model/anexos/prestacoes/'+ retorno + '"' + ' target="_blank"><img src="https://portal.seds.sp.gov.br/coed/images/pdf.gif" border="0"></a>');
	}
	else{
		$('#boxEnvioDocComplementar').html('<a href="../public/componentes/notas/model/anexos/prestacoes/'+ retorno + '"' + ' target="_blank"><img src="https://portal.seds.sp.gov.br/coed/images/pdf.gif" border="0"></a> <button type="button" id="delDocComplementar" name="delDocComplementar" class="btn btn-danger" onclick=criaPergunta(3,"../model/anexos/prestacoes/'+ retorno + '",0) data-bs-toggle="tooltip" data-bs-placement="top" title="Excluir arquivo"><i class="bi bi-trash-fill"></i></button>');
	}
}

function registraApontamentoDocComp(id){
	var apontamento = $("#txtMensagem").val();
	
	if(apontamento==""){
		alert("Preencha o campo Apontamento");
	}
	else{

		$.ajax({
			type: "POST",
			url: "https://portal.seds.sp.gov.br/coed/public/componentes/notas/model/cadastraApontamentoDocComp.php",
			data: {'id':id,'apontamento':apontamento},
			success: function () {
				$("#txtMensagem").val('');
				listaApontamentosDocComp();
			}
		});

	}
}

function listaApontamentosDocComp(){
	var prestacao = $("#hidIdPrestacao").val();
	$.ajax({
		type: "POST",
		url: "https://portal.seds.sp.gov.br/coed/public/componentes/notas/model/listaApontamentosDocComp.php",
		data: {'prestacao':prestacao},
		success: function (retorno) {
		  $("#boxListaApontamentosDocComp").html(retorno);
		}
	});

}

function acaoStatus(id){
	if(id==4){
		$('#boxCampoApontamento').addClass('d-none');
	}
	else{
		$('#boxCampoApontamento').removeClass('d-none');
	}
}

function finalizaPrestacao(){
	var prestacao = $("#hidIdPrestacao").val();
	$.ajax({
		type: "POST",
		url: "https://portal.seds.sp.gov.br/coed/public/componentes/notas/model/finalizaPrestacao.php",
		data: {'prestacao':prestacao},
		success: function (retorno) {
			if(retorno==0){
				alert("Prestação finalizada");
				listaNotasFiscais();
			}
		}
	});
}

function disponibilizaPrestacao(){
	var prestacao = $("#hidIdPrestacao").val();
	$.ajax({
		type: "POST",
		url: "https://portal.seds.sp.gov.br/coed/public/componentes/notas/model/disponibilizaPrestacao.php",
		data: {'prestacao':prestacao},
		success: function (retorno) {
			if(retorno==0){
				listaNotasFiscais();
				$("#boxDisponibilizaPrestacao").addClass('d-none');
			}
		}
	});
}

function encerraPrestacao(){
	var prestacao = $("#hidIdPrestacao").val();
	$.ajax({
		type: "POST",
		url: "https://portal.seds.sp.gov.br/coed/public/componentes/notas/model/encerraPrestacao.php",
		data: {'prestacao':prestacao},
		success: function (retorno) {
			if(retorno==0){
				alert("Prestação encerrada");
				listaNotasFiscais();
			}
		}
	});
}

function trataStatus(id){

	if(id==4){
		$("#boxMotivoGlosa").removeClass('d-none');
		$("#boxValorGlosa").addClass('d-none');
		$("#boxCampoApontamento").addClass('d-none');
		
		$("#boxRessalva").addClass('d-none');
	}
	else if(id==7){
		$("#boxMotivoGlosa").removeClass('d-none');
		$("#boxValorGlosa").removeClass('d-none');
		$("#boxCampoApontamento").addClass('d-none');
		$("#boxRessalva").addClass('d-none');
	}
	else if(id==8){
		$("#boxRessalva").removeClass('d-none');
		$("#boxMotivoGlosa").addClass('d-none');
		$("#boxValorGlosa").addClass('d-none');
		$("#boxCampoApontamento").addClass('d-none');
	}
	else{
		$("#boxMotivoGlosa").addClass('d-none');
		$("#txtMotivoGlosa").val('');
		$("#boxCampoApontamento").removeClass('d-none');
		$("#boxRessalva").addClass('d-none');
	}
}

function excluirNotaFiscal(prestacao,nota){
	$.ajax({
		type: "POST",
		url: "https://portal.seds.sp.gov.br/coed/public/componentes/notas/model/excluirNotaFiscal.php",
		data: {'prestacao':prestacao , 'nota':nota},
		success: function () {
			atualizaCabecalho(nota,5,0);
			cancelaCadNota(0);
			listaNotasFiscais();
		}
	  });
}

function gerarCsv(prestacao){
	
	$.ajax({
		type: "POST",
		url: "https://portal.seds.sp.gov.br/coed/public/componentes/notas/model/gerarExcel.php",
		data: {'prestacao':prestacao},
		success: function (retorno) {
			openCsv = window.open("https://portal.seds.sp.gov.br/coed/public/componentes/notas/model/planilhas/" + retorno,"CSV","width=10,height=10");
			setTimeout('openCsv.close()',500);
			setTimeout("alert('Download efetuado')",800);
			
		}
	  });

}

function mostraFerramentasCoed(){
	$('#boxLiberaPrestacao').removeClass('d-none');
}

function liberaPrestacao(){
	var prestacao = $("#hidIdPrestacao").val();
	$.ajax({
		type: "POST",
		url: "https://portal.seds.sp.gov.br/coed/public/componentes/notas/model/liberaPrestacao.php",
		data: {'prestacao':prestacao},
		success: function (retorno) {
			if(retorno==0){
				listaNotasFiscais();
				$("#boxLiberaPrestacao").addClass('d-none');
				alert('Prestação liberada');
			}
		}
	});
}

function marcaAnaliseCoed(id){

	if($("#chkAnaliseCoed"+id).is(":checked") == true){
		var acao = 1;
		carregaStatusNotas(6);
		setTimeout('$("#slcNotasStatus").prop("disabled",true)',300);
		$("#btnDefineStatus").addClass("d-none");
		$("#boxCampoApontamento").addClass("d-none");
	}
	else{
		carregaStatusNotas(1);
		var acao = 0;
		setTimeout('$("#slcNotasStatus").prop("disabled",false)',300);
		$("#btnDefineStatus").removeClass("d-none");
		$("#boxCampoApontamento").removeClass("d-none");
	}

	$.ajax({
		type: "POST",
		url: "https://portal.seds.sp.gov.br/coed/public/componentes/notas/model/marcaAnaliseCoed.php",
		data: {'acao':acao,'id':id},
		success: function () {
			listaNotasFiscais();
		}
	  });

}

function abreCamposRubrica(){

	var modalConfirmacao = new bootstrap.Modal(document.getElementById('confirmacaoModal'), {
		keyboard: false
	})

	modalConfirmacao.show();

}

function alteraRubricaPrevista(cabecalho){
	
	var rh = $("#txtValorPrevistoRh").val();
	var custeio = $("#txtValorPrevistoCusteio").val();
	var terceiros = $("#txtValorPrevistoTerceiros").val();
	var prestacao = $("#hidIdPrestacao").val();

	$.ajax({
		type: "POST",
		url: "https://portal.seds.sp.gov.br/coed/public/componentes/notas/model/alteraRubricaPrevista.php",
		data: {'cabecalho':cabecalho,'rh':rh,'custeio':custeio,'terceiros':terceiros},
		success: function () {
			montaCabecalho(prestacao);
		}
	});

}
