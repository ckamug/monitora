$(document).ready(function(){
  carregaTiposDesligamentos(0);
  carregaDesligamento();
  carregaEncaminhamentoRealizado();

  $("input[name='acolhido_encaminhado_hub']").on("change", function () {
    if (this.value === "1") {
      $("#boxRetornoSP").removeClass("d-none");
      carregaEncaminhamentosHub();
    } else {
      $("#boxRetornoSP").addClass("d-none");
      $("#boxRetornoSPOpcoes").html("");
    }
  });

  $(document).on("change", "input[name='tipo_encaminhamento_realizado_id[]']", function () {
    trataEncaminhamentoRealizado();
  });
});

function carregaEncaminhamentosHub(selectedId){
  $.ajax({
    type: "POST",
    url: "../public/componentes/desligamento/model/carregaEncaminhamentosHub.php",
    dataType: "JSON",
    success: function (retorno) {
      var box = $("#boxRetornoSPOpcoes");
      box.html("");

      if (!retorno) {
        return;
      }

      if (!Array.isArray(retorno)) {
        retorno = [retorno];
      }

      if (retorno.length === 0) {
        return;
      }

      retorno.forEach(function (item, idx) {
        var id = item.tipo_encaminhamento_hub_id || item.encaminhamento_hub_id || item.id;
        var descricao = item.tipo_encaminhamento_hub_descricao || item.descricao || item.nome;
        if (!id || !descricao) {
          return;
        }

        var idOpcao = "tipoEncaminhamentoHub" + id + "_" + (idx + 1);
        box.append(
          "<div class='form-check'>" +
            "<input class='form-check-input' type='radio' name='tipo_encaminhamento_hub_id' id='" + idOpcao + "' value='" + id + "'>" +
            "<label class='form-check-label' for='" + idOpcao + "'>" + descricao + "</label>" +
          "</div>"
        );
      });

      if (selectedId) {
        $("input[name='tipo_encaminhamento_hub_id'][value='" + selectedId + "']").prop("checked", true);
      }
    }
  });
}


function carregaTiposDesligamentos(id){
	$.ajax({
	  type: "POST",
	  url: "../public/componentes/desligamento/model/carregaTiposDesligamentos.php",
	  data: {'id':id},
	  success: function (retorno) {
		$("#boxTiposDesligamentos").html(retorno);
	  }
	});
}

function abreTipoCarregamento(id){
    carregaEncaminhamentos(id);
    
    if(id>0){
        $("#boxInfoDesligamentos").removeClass("d-none");
        $("#boxInfoEncaminhamento").removeClass("d-none")
        $("#boxInfoDesligamentosAdministrativo").addClass("d-none");
        $("#boxInfoDesligamentosQualificado").addClass("d-none");
        $("#boxInfoDesligamentosSolicitado").addClass("d-none");
        $("#boxInfoDesligamentosDesistencia").addClass("d-none");
        $("#boxInfoDesligamentosEvasao").addClass("d-none");
        $("#boxInfoDesligamentosTransferencia").addClass("d-none");
        
        switch(id){
            case "1":
                $("#boxInfoDesligamentosAdministrativo").removeClass("d-none");
            break;
            case "2":
                $("#boxInfoDesligamentosQualificado").removeClass("d-none");
            break;
            case "3":
                $("#boxInfoDesligamentosSolicitado").removeClass("d-none");
            break;
            case "4":
                $("#boxInfoDesligamentosDesistencia").removeClass("d-none");
            break;
            case "5":
                $("#boxInfoDesligamentosEvasao").removeClass("d-none");
            break;
            case "6":
                $("#boxInfoDesligamentosTransferencia").removeClass("d-none");
            break;
        }
    }
    else{
        $("#boxInfoDesligamentos").addClass("d-none");
        $("#boxInfoEncaminhamento").addClass("d-none");
        $("#boxInfoDesligamentosAdministrativo").removeClass("d-none");
        $("#boxInfoDesligamentosQualificado").removeClass("d-none");
        $("#boxInfoDesligamentosSolicitado").removeClass("d-none");
        $("#boxInfoDesligamentosDesistencia").removeClass("d-none");
        $("#boxInfoDesligamentosEvasao").removeClass("d-none");
        $("#boxInfoDesligamentosTransferencia").removeClass("d-none");
    }
}

function carregaEncaminhamentos(tipoDesligamentoId, selectedId){
    $.ajax({
      type: "POST",
      url: "../public/componentes/desligamento/model/carregaEncaminhamentos.php",
      dataType: "JSON",
      data: {tipo_desligamento_id: tipoDesligamentoId},
      success: function (retorno) {
        var box = $("#boxDestinoResidenteOpcoes");
        box.html("");
        $("#boxDestinoResidente").addClass("d-none");

        if (!retorno) {
          return;
        }

        if (!Array.isArray(retorno)) {
          retorno = [retorno];
        }

        if (retorno.length === 0) {
          return;
        }

        retorno.forEach(function (item, idx) {
          var id = item.tipo_encaminhamento_id || item.id;
          var descricao = item.tipo_encaminhamento_descricao || item.descricao || item.nome;
          if (!id || !descricao) {
            return;
          }

          var idOpcao = "destino_" + id + "_" + (idx + 1);
          box.append(
            "<div class='form-check'>" +
              "<input class='form-check-input' type='radio' name='tipo_encaminhamento_id' id='" + idOpcao + "' value='" + id + "'>" +
              "<label class='form-check-label' for='" + idOpcao + "'>" + descricao + "</label>" +
            "</div>"
          );
        });

        if ($("#boxDestinoResidenteOpcoes").children().length > 0) {
          $("#boxDestinoResidente").removeClass("d-none");
        }

        if (selectedId) {
          $("input[name='tipo_encaminhamento_id'][value='" + selectedId + "']").prop("checked", true);
        }
      }
    });
}

function carregaEncaminhamentoRealizado(selectedId){
    $.ajax({
      type: "POST",
      url: "../public/componentes/desligamento/model/carregaEncaminhamentosRealizados.php",
      dataType: "JSON",
      success: function (retorno) {
        var box = $("#boxEncaminhamentoRealizadoOpcoes");
        box.html("");

        if (!retorno) {
          return;
        }

        if (!Array.isArray(retorno)) {
          retorno = [retorno];
        }

        var selectedIds = [];
        if (Array.isArray(selectedId)) {
          selectedIds = selectedId.map(function (id) { return $.trim(String(id)); });
        } else if (selectedId) {
          selectedIds = String(selectedId).split(",").map(function (id) { return $.trim(id); });
        }

        retorno.forEach(function (item, idx) {
          var id = item.tipo_encaminhamento_realizado_id || item.id;
          var descricao = item.tipo_encaminhamento_realizado_descricao || item.descricao || item.nome;
          if (!id || !descricao) {
            return;
          }
          var idStr = String(id);
          var idOpcao = "encaminhamento_realizado_" + idStr + "_" + (idx + 1);
          var checked = selectedIds.indexOf(idStr) !== -1 ? " checked" : "";
          box.append(
            "<div class='form-check'>" +
              "<input class='form-check-input' type='checkbox' name='tipo_encaminhamento_realizado_id[]' id='" + idOpcao + "' value='" + idStr + "'" + checked + ">" +
              "<label class='form-check-label' for='" + idOpcao + "'>" + descricao + "</label>" +
            "</div>"
          );
        });

        trataEncaminhamentoRealizado();
      }
    });
}

function trataEncaminhamentoRealizado(){
    var mostrarOutroEquipSaude = false;
    var mostrarOutroDestino = false;

    $("input[name='tipo_encaminhamento_realizado_id[]']:checked").each(function () {
        var label = $("label[for='" + this.id + "']").text();
        var textoNormalizado = $.trim(label).toLowerCase();
        if (textoNormalizado.indexOf("outros equipamentos de sa") === 0) {
            mostrarOutroEquipSaude = true;
        }
        if (textoNormalizado === "outros") {
            mostrarOutroDestino = true;
        }
    });

    if (mostrarOutroEquipSaude) {
        $("#boxOutroEquipSaude").removeClass("d-none");
    } else {
        $("#boxOutroEquipSaude").addClass("d-none");
        $("#txtOutroEquipSaude").val("");
    }

    if (mostrarOutroDestino) {
        $("#boxOutroDestino").removeClass("d-none");
    } else {
        $("#boxOutroDestino").addClass("d-none");
        $("#txtOutroDestino").val("");
    }
}
function cadastraDesligamento(){
    var id = $("#hidEntrada").val();
    var form = $("#formDesligamento")[0];
    var data = new FormData(form);
    data.append('id',id);
	$.ajax({
	  type: "POST",
      enctype: 'multipart/form-data',
	  url: "https://portal.seds.sp.gov.br/coed/public/componentes/desligamento/model/cadastraDesligamento.php",
	  data: data,
      dataType: "json",
      processData: false,
      cache: false,
      contentType: false,
	  success: function (retorno) {
        console.log("desligamento debug:", retorno);
        alert('Desligamento efetuado');
		location.href = "/coed/desligamento/" + id;
	  },
      error: function (xhr) {
        console.log("desligamento debug (error):", xhr.responseText);
	  }
	});
}

function carregaDesligamento(){
	id = $("#hidEntrada").val();
	$.ajax({
		url:'https://portal.seds.sp.gov.br/coed/public/componentes/desligamento/model/carregaDesligamento.php',
		dataType: 'JSON',
		type: 'POST',
		data: {id:id},
		success: function(resultado){

			if(jQuery.isEmptyObject(resultado)==false){

                carregaTiposDesligamentos(resultado.tipo_desligamento_id);
				$("#txtSintese").val(resultado.desligamento_sintese);


                switch(resultado.tipo_desligamento_id){
                    case '1':
                        $("#boxInfoDesligamentosAdministrativo").removeClass("d-none");
                        if(resultado.desligamento_motivo.includes("pessoas acolhidas")){
                            $("#radMotivoDesligamentoAdm1").prop('checked',true);
                        }
                        else if(resultado.desligamento_motivo.includes("descumprimentos")){
                            $("#radMotivoDesligamentoAdm2").prop('checked',true);
                        }
                        else{
                            $("#radMotivoDesligamentoAdm3").prop('checked',true);
                        }
                    break;
                    case '2':
                        $("#boxInfoDesligamentos").removeClass("d-none");
                        if (resultado.desligamento_motivo.includes('Cumprimento')){
                            $("#chkMotivosDesligamentoQualificado1").prop('checked',true);
                        }
                        if (resultado.desligamento_motivo.includes('Melhora')){
                            $("#chkMotivosDesligamentoQualificado2").prop('checked',true);
                        }
                        if (resultado.desligamento_motivo.includes('autocuidado')){
                            $("#chkMotivosDesligamentoQualificado3").prop('checked',true);
                        }
                        if (resultado.desligamento_motivo.includes('ConsciÃªncia')){
                            $("#chkMotivosDesligamentoQualificado4").prop('checked',true);
                        }
                        if (resultado.desligamento_motivo.includes('habilidades')){
                            $("#chkMotivosDesligamentoQualificado5").prop('checked',true);
                        }
                        if (resultado.desligamento_motivo.includes('trabalho')){
                            $("#chkMotivosDesligamentoQualificado6").prop('checked',true);
                        }
                        if (resultado.desligamento_motivo.includes('sustento')){
                            $("#chkMotivosDesligamentoQualificado6").prop('checked',true);
                        }
                        if (resultado.desligamento_motivo.includes('moradia')){
                            $("#chkMotivosDesligamentoQualificado6").prop('checked',true);
                        }
                    break;
                    case '3':
                        $("#boxInfoDesligamentosSolicitado").removeClass("d-none");
                        if(resultado.desligamento_motivo.includes("INSS")){
                            $("#radDesligamentoSolicitado1").prop('checked',true);
                        }
                        else if(resultado.desligamento_motivo.includes("filhos")){
                            $("#radDesligamentoSolicitado2").prop('checked',true);
                        }
                        else if(resultado.desligamento_motivo.includes("companheiro")){
                            $("#radDesligamentoSolicitado3").prop('checked',true);
                        }
                        else{
                            $("#radDesligamentoSolicitado4").prop('checked',true);
                        }
                    break;
                    case '4':
                        $("#boxInfoDesligamentosDesistencia").removeClass("d-none");
                        if(resultado.desligamento_motivo.includes("prestado")){
                            $("#radDesistencia1").prop('checked',true);
                        }
                        else if(resultado.desligamento_motivo.includes("processo")){
                            $("#radDesistencia2").prop('checked',true);
                        }
                        else if(resultado.desligamento_motivo.includes("afetivos")){
                            $("#radDesistencia3").prop('checked',true);
                        }
                        else{
                            $("#radDesistencia4").prop('checked',true);
                        }
                    break;
                    case '6':
                        $("#boxInfoDesligamentosTransferencia").removeClass("d-none");
                        if(resultado.desligamento_motivo.includes("saÃºde")){
                            $("#radTransferencia1").prop('checked',true);
                        }
                        else if(resultado.desligamento_motivo.includes("Drogas")){
                            $("#radTransferencia2").prop('checked',true);
                        }
                        else if(resultado.desligamento_motivo.includes("Sistema")){
                            $("#radTransferencia3").prop('checked',true);
                        }
                        else{
                            $("#radTransferencia4").prop('checked',true);
                        }
                    break;
                }
                if (resultado.tipo_encaminhamento_id) {
                    carregaEncaminhamentos(resultado.tipo_desligamento_id, resultado.tipo_encaminhamento_id);
                } else {
                    carregaEncaminhamentos(resultado.tipo_desligamento_id);
                }
                if (resultado.tipo_encaminhamento_realizado_id) {
                    carregaEncaminhamentoRealizado(resultado.tipo_encaminhamento_realizado_id);
                } else {
                    carregaEncaminhamentoRealizado();
                }
                if (resultado.tipo_encaminhamento_realizado_outros_equipamentos) {
                    $("#txtOutroEquipSaude").val(resultado.tipo_encaminhamento_realizado_outros_equipamentos);
                }
                if (resultado.tipo_encaminhamento_realizado_outro) {
                    $("#txtOutroDestino").val(resultado.tipo_encaminhamento_realizado_outro);
                }

                if (resultado.desligamento_impactos.includes('ENCEJA')){
                    $("#chkImpactos1").prop('checked',true);
                }
                if (resultado.desligamento_impactos.includes('superior')){
                    $("#chkImpactos2").prop('checked',true);
                }
                if (resultado.desligamento_impactos.includes('profissionalizaÃ§Ã£o')){
                    $("#chkImpactos3").prop('checked',true);
                }
                if (resultado.desligamento_impactos.includes('vÃ­nculos')){
                    $("#chkImpactos4").prop('checked',true);
                }
                if (resultado.desligamento_impactos.includes('protetivo')){
                    $("#chkImpactos5").prop('checked',true);
                }
                if (resultado.desligamento_impactos.includes('situaÃ§Ã£o de rua')){
                    $("#chkImpactos6").prop('checked',true);
                }
                if (resultado.desligamento_impactos.includes('ajuda')){
                    $("#chkImpactos7").prop('checked',true);
                }
                if (resultado.desligamento_impactos.includes('CRAS')){
                    $("#chkImpactos8").prop('checked',true);
                }
                if (resultado.desligamento_impactos.includes('CAPS')){
                    $("#chkImpactos9").prop('checked',true);
                }
                if (resultado.desligamento_impactos.includes('Prevenir')){
                    $("#chkImpactos10").prop('checked',true);
                }
                if (resultado.desligamento_impactos.includes('mundo do trabalho')){
                    $("#chkImpactos11").prop('checked',true);
                }
                if (resultado.desligamento_impactos.includes('autossustento')){
                    $("#chkImpactos12").prop('checked',true);
                }
                if (resultado.desligamento_impactos.includes('BancarizaÃ§Ã£o')){
                    $("#chkImpactos13").prop('checked',true);
                }
                if (resultado.desligamento_impactos.includes('NÃ£o houve')){
                    $("#chkImpactos14").prop('checked',true);
                }
                var impactosTexto = resultado.desligamento_impactos || "";
                $("input[name='chkImpactos[]']").each(function(){
                    if(impactosTexto.includes($(this).val())){
                        $(this).prop('checked',true);
                    }
                });

                if (impactosTexto.includes('autossustento') || impactosTexto.includes('auto sustento')){
                    $("#chkImpactos12").prop('checked',true);
                }
                if (
                    impactosTexto.includes('Capacidade de Autocuidado e Auto-organização') ||
                    impactosTexto.includes('Capacidade de Autocuidado e Autoorganização')
                ){
                    $("#chkImpactos15").prop('checked',true);
                }

                if (resultado.acolhido_encaminhado_hub === "1") {
                    $("#desligamento_hub_sim").prop("checked", true);
                    $("#boxRetornoSP").removeClass("d-none");
                    carregaEncaminhamentosHub(resultado.tipo_encaminhamento_hub_id);
                    } else if (resultado.acolhido_encaminhado_hub === "0") {
                    $("#desligamento_hub_nao").prop("checked", true);
                    $("#boxRetornoSP").addClass("d-none");
                    $("#boxRetornoSPOpcoes").html("");
                    }
                setTimeout("$('#formDesligamento input[type=text], #formDesligamento input[type=date], #formDesligamento input[type=radio], #formDesligamento input[type=checkbox], #formDesligamento textarea, #formDesligamento select').prop('disabled', true)",1000);
				$("#btnCadDesligamento").addClass('d-none');
                $("#boxInfoDesligamentos").removeClass('d-none');
			}
		},
		complete: function(){}
	 });
}
