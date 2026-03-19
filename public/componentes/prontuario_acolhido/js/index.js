var prontuarioIdentificacaoId = 0;
var prontuarioHistoricoSocialId = 0;
var prontuarioSaudeGeralId = 0;
var prontuarioAvaliacaoPsicossocialId = 0;
var prontuarioSobreUsoId = 0;
var sobreUsoListaSubstancias = [];
var sobreUsoListaRanking = [];
var sobreUsoListaAjudaEmergencia = [];
var fontesIdentificacao = {
    etnia: false,
    tipoCertidao: false,
    registroCartorio: false,
    ufRegistro: false
};

$(document).ready(function(){
	listaAcoes('Et');
    carregaTiposDesligamentos(0);
    carregaDesligamento();
    carregaDadosSensiveis();
    inicializaAbaIdentificacao();
    inicializaAbaHistoricoSocial();
    inicializaAbaSaudeGeral();
    inicializaAbaAvaliacaoPsicossocial();
    inicializaAbaSobreUso();
    inicializaAbaMedicacao();

    $(document).on("change", "input[name='tipo_encaminhamento_realizado_id[]']", function () {
        trataEncaminhamentoRealizado();
    });

    $("#txtAjudaDoacao , #txtAposentadoria , #txtSeguroDesemprego , #txtPensao , #txtOutrasdFontes").maskMoney({
		decimal: ",",
		thousands: "."
	});

    $("#boxOutraDoenca , #boxOutroTranstorno , #boxOutroMembroFamilia , #boxRompimentoVinculos").hide();

    $("[name='radNegligencia'] , [name='radViolenciaFisica'] , [name='radViolenciaSexual'] , [name='radViolenciaParceiros'] , [name='radSuporte'] , [name='radAutorViolencia'] , [name='radResponsabilizado'] , [name='radPenaAplicada'] , [name='radEgresso'] , [name='radEgressoPena'] , [name='radPendenciaJudicial']").on('click',function(){

		switch(this.name){
            case 'radNegligencia':
                if(this.value.indexOf('Sim,')===0){
                    $("#boxIdadeNegligencia").removeClass("d-none");
                }
                else{
                    $("#boxIdadeNegligencia").addClass("d-none");
                    $("#txtIdadeNegligencia").val("");
                }
            break;
            case 'radViolenciaFisica':
                if(this.value.indexOf('Sim,')===0){
                    $("#boxIdadeViolenciaFisica").removeClass("d-none");
                }
                else{
                    $("#boxIdadeViolenciaFisica").addClass("d-none");
                    $("#txtIdadeViolenciaFisica").val("");
                }
            break;
			case 'radViolenciaSexual':
				if(this.value=='Sim'){
					$("#boxIdade").removeClass("d-none");
				}
				else{
					$("#boxIdade").addClass("d-none");
					$("#txtQualIdade").val("");
				}
			break;
			case 'radViolenciaParceiros':
				if(this.value=='Sim'){
					$("#boxViolenciaParceiros").removeClass("d-none");
                    $("#boxIdadeViolenciaParceiros").removeClass("d-none");
				}
				else{
					$("#boxViolenciaParceiros").addClass("d-none");
                    $("#boxIdadeViolenciaParceiros").addClass("d-none");
                    $("#txtIdadeViolenciaParceiros").val("");
                    var itens = document.getElementsByName('chkTipoViolenciaParceiro[]');
                    var i = 0;
                    for(i=0; i<itens.length;i++){
                        itens[i].checked = false;
                    }
                    var itens = document.getElementsByName('radSuporte');
                    var i = 0;
                    for(i=0; i<itens.length;i++){
                        itens[i].checked = false;
                    }
                    $("#boxSuporte").addClass("d-none");
                    $("#txtQualSuporte").val("");
				}
			break;
			case 'radSuporte':
				if(this.value=='Sim'){
					$("#boxSuporte").removeClass("d-none");
				}
				else{
					$("#boxSuporte").addClass("d-none");
					$("#txtQualSuporte").val("");
				}
			break;
			case 'radAutorViolencia':
				if(this.value=='Sim'){
					$("#boxAutorViolencia").removeClass("d-none");
				}
				else{
					$("#boxAutorViolencia").addClass("d-none");
                    var itens = document.getElementsByName('chkTipoViolencia[]');
                    var i = 0;
                    for(i=0; i<itens.length;i++){
                        itens[i].checked = false;
                    }
                    var itens = document.getElementsByName('radResponsabilizado');
                    var i = 0;
                    for(i=0; i<itens.length;i++){
                        itens[i].checked = false;
                    }
                    var itens = document.getElementsByName('radPenaAplicada');
                    var i = 0;
                    for(i=0; i<itens.length;i++){
                        itens[i].checked = false;
                    }
                    $("#boxPenaAplicada").addClass("d-none");
                    $("#boxTempoPenaAplicada").addClass("d-none");
                    $("#txtTempoPenaAplicada").val("");
					
				}
			break;
			case 'radResponsabilizado':
				if(this.value=='Foi responsabilizado criminalmente'){
					$("#boxPenaAplicada").removeClass("d-none");
				}
				else{
					$("#boxPenaAplicada").addClass("d-none");
                    var itens = document.getElementsByName('radPenaAplicada');
                    var i = 0;
                    for(i=0; i<itens.length;i++){
                        itens[i].checked = false;
                    }
                    $("#txtTempoPenaAplicada").val("");
				}
			break;
			case 'radPenaAplicada':
				if(this.value=='Sentença em regime fechado'){
					$("#boxTempoPenaAplicada").removeClass("d-none");
				}
				else{
					$("#boxTempoPenaAplicada").addClass("d-none");
					$("#txtTempoPenaAplicada").val("");
				}
			break;
            case 'radEgresso':
				if(this.value=='Sim'){
					$("#boxEgresso").removeClass("d-none");
				}
				else{
					$("#boxEgresso").addClass("d-none");
                    var itens = document.getElementsByName('radEgressoPena');
                    var i = 0;
                    for(i=0; i<itens.length;i++){
                        itens[i].checked = false;
                    }
                    $("#txtTempoPenaEgresso").val("");
                    $("#boxSentenca").addClass("d-none");

                    var itens = document.getElementsByName('radCumpriuPena');
                    var i = 0;
                    for(i=0; i<itens.length;i++){
                        itens[i].checked = false;
                    }
                    var itens = document.getElementsByName('radForagido');
                    var i = 0;
                    for(i=0; i<itens.length;i++){
                        itens[i].checked = false;
                    }
                    var itens = document.getElementsByName('radLiberdade');
                    var i = 0;
                    for(i=0; i<itens.length;i++){
                        itens[i].checked = false;
                    }
					
				}
			break;
            case 'radEgressoPena':
				if(this.value=='Sentença em regime fechado'){
					$("#boxSentenca").removeClass("d-none");
				}
				else{
					$("#boxSentenca").addClass("d-none");
					$("#txtTempoPenaEgresso").val("");

                    var itens = document.getElementsByName('radCumpriuPena');
                    var i = 0;
                    for(i=0; i<itens.length;i++){
                        itens[i].checked = false;
                    }
                    var itens = document.getElementsByName('radForagido');
                    var i = 0;
                    for(i=0; i<itens.length;i++){
                        itens[i].checked = false;
                    }
                    var itens = document.getElementsByName('radLiberdade');
                    var i = 0;
                    for(i=0; i<itens.length;i++){
                        itens[i].checked = false;
                    }
				}
			break;
            case 'radPendenciaJudicial':
				if(this.value=='Sim'){
					$("#boxPendencia").removeClass("d-none");
				}
				else{
					$("#boxPendencia").addClass("d-none");
                    $("#txtMotivoPendencia").val("");
				}
			break;

		}

	});

    $("#btnAcolhimento").click(function() {
        $(this).toggleClass("btn-ativo");
        $("#btnAcolhimento").removeClass("btn-light");
        $("#btnAcolhimento").addClass("btn-success");
        $("#btnAnamnese").removeClass("btn-ativo");
        $("#btnAnamnese").removeClass("btn-success");
        $("#btnAnamnese").addClass("btn-light");
        $("#cardAnamnese").hide();
        $("#cardAcolhimento").show();
    });

    $("#btnAnamnese").click(function() {
        $(this).toggleClass("btn-ativo");
        $("#cardAnamnese").removeClass("d-none");
        $("#btnAnamnese").removeClass("btn-light");
        $("#btnAnamnese").addClass("btn-success");
        $("#btnAcolhimento").removeClass("btn-ativo");
        $("#btnAcolhimento").removeClass("btn-success");
        $("#btnAcolhimento").addClass("btn-light");
        $("#cardAcolhimento").hide();
        $("#cardAnamnese").show();
    });

    ocultaDetalhesTipoPsiSs();

})

function listaAcoes(tipo){
	
	var id = $("#hidEntrada").val();
    
    $.ajax({
		type: "POST",
		url: "../public/componentes/prontuario_acolhido/model/listaAcoes.php",
        data:{id:id,tipo:tipo},
		success: function (retorno) {
		  $("#boxListaAcoes"+tipo).html(retorno);
		}
	});

}

function carregaTiposAtendimentos(){
	$.ajax({
	  type: "POST",
	  url: "../public/componentes/prontuario_acolhido/model/carregaTiposAtendimentos.php",
	  data: {idsPermitidos:'5,6'},
	  success: function (retorno) {
		$("#boxTiposAtendimentosPsicologia").html(retorno);
        $("#boxTiposAtendimentosServicoSocial").html(retorno);
	  }
	});

	$.ajax({
	  type: "POST",
	  url: "../public/componentes/prontuario_acolhido/model/carregaTiposAtendimentos.php",
	  data: {idsPermitidos:'2,3,4'},
	  success: function (retorno) {
		$("#boxTiposAtendimentosAtividades").html(retorno);
	  }
	});
}

function ocultaDetalhesTipoPsiSs(){
    $("#tabPsicologia #slcSubTiposAtendimentos").closest(".form-floating").parent().remove();
    $("#tabServicoSocial #slcSubTiposAtendimentos").closest(".form-floating").parent().remove();
}

function carregaSubAtendimento(id){
    if(id!=3){
        $.ajax({
        type: "POST",
        url: "../public/componentes/prontuario_acolhido/model/carregaSubTiposAtendimentos.php",
        data: {id:id},
        success: function (retorno) {
            $("#boxSubTiposAtendimentosAtividades").html(retorno);
            ocultaDetalhesTipoPsiSs();
        }
        });
    }
    else{
        $("#boxSubTiposAtendimentosAtividades").html('<div class="form-floating"><input type="text" class="form-control" id="txtOutraAtividade" name="txtOutraAtividade" placeholder="Quais?"><label for="txtQualSuporte">Quais?</label></div>');
        ocultaDetalhesTipoPsiSs();
    }
}

function carregaTiposDesligamentos(id){
	$.ajax({
	  type: "POST",
	  url: "../public/componentes/prontuario_acolhido/model/carregaTiposDesligamentos.php",
	  data: {'id':id},
	  success: function (retorno) {
		$("#boxTiposDesligamentos").html(retorno);
	  }
	});
}

function abreTipoCarregamento(id){
    
    if(id>0){
        $("#boxInfoDesligamentos").removeClass("d-none");
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
        $("#boxInfoDesligamentosAdministrativo").removeClass("d-none");
        $("#boxInfoDesligamentosQualificado").removeClass("d-none");
        $("#boxInfoDesligamentosSolicitado").removeClass("d-none");
        $("#boxInfoDesligamentosDesistencia").removeClass("d-none");
        $("#boxInfoDesligamentosEvasao").removeClass("d-none");
        $("#boxInfoDesligamentosTransferencia").removeClass("d-none");
    }
}

function cadastraAcaoEt(){

    var id = $("#hidEntrada").val();
    var form = $("#formEquipeTecnica")[0];
    var file = $('#uplDocEquipeTecnica').prop("files")[0];
    var data = new FormData(form);
    data.append('id',id);
    data.append('arquivo',file);

    $.ajax({
        type: "POST",
        enctype: 'multipart/form-data',
        url: "../public/componentes/prontuario_acolhido/model/cadastraAcaoEt.php",
        data: data,
        processData: false,
        cache: false,
        contentType: false,
        success: function (retorno) {
            if(retorno>0){
                $('#formEquipeTecnica').each (function(){
                    this.reset();
                });
                alert("Ação registrada com sucesso");
            }
            listaAcoes('Et');
            defineDataAtualCampo("#txtDataAnotacaoTecnica");
            $("#txtDescricaoAcaoEquipeTecnica").val('');
            $("#uplDocEquipeTecnica").val('');
        }
    });

}

function cadastraAcaoPsi(){

    var id = $("#hidEntrada").val();
    var form = $("#formPsicologia")[0];
    var file = $('#uplDocPsicologia').prop("files")[0];
    var data = new FormData(form);
    data.append('id',id);
    data.append('arquivo',file);

    $.ajax({
        type: "POST",
        enctype: 'multipart/form-data',
        url: "../public/componentes/prontuario_acolhido/model/cadastraAcaoPsi.php",
        data: data,
        processData: false,
        cache: false,
        contentType: false,
        success: function (retorno) {
            if(retorno>0){
                $('#formPsicologia').each (function(){
                    this.reset();
                });
                alert("Ação registrada com sucesso");
            }
            listaAcoes('Psi');

            defineDataAtualCampo("#txtDataAnotacaoPsicologia");
            $("#slcTiposAtendimentos").val('0');
            $("#slcSubTiposAtendimentos").val('0');
            $("#txtOutraAtividade").val('');
            $("#txtDescricaoAcaoPsicologia").val('');
            $("#uplDocPsicologia").val('');
            $("#boxSubTiposAtendimentosAtividades").html('');

        }
    });

}

function cadastraAcaoAtv(){

    var id = $("#hidEntrada").val();
    var form = $("#formAtividades")[0];
    var data = new FormData(form);
    data.append('id',id);

    $.ajax({
        type: "POST",
        url: "../public/componentes/prontuario_acolhido/model/cadastraAcaoAtv.php",
        data: data,
        processData: false,
        cache: false,
        contentType: false,
        success: function (retorno) {
            if(retorno>0){
                $('#formAtividades').each (function(){
                    this.reset();
                });
                alert("Ação registrada com sucesso");
            }
            listaAcoes('Atv');

            defineDataAtualCampo("#txtDataAnotacaoAtividades");
            $("#boxSubTiposAtendimentosAtividades").html('');
        }
    });

}

function cadastraAcaoSs(){

    var id = $("#hidEntrada").val();
    var form = $("#formServicoSocial")[0];
    var file = $('#uplDocServicoSocial').prop("files")[0];
    var data = new FormData(form);
    data.append('id',id);
    data.append('arquivo',file);

    $.ajax({
        type: "POST",
        enctype: 'multipart/form-data',
        url: "../public/componentes/prontuario_acolhido/model/cadastraAcaoSs.php",
        data: data,
        processData: false,
        cache: false,
        contentType: false,
        success: function (retorno) {
            if(retorno>0){
                $('#formServicoSocial').each (function(){
                    this.reset();
                });
                alert("Ação registrada com sucesso");
            }
            listaAcoes('Ss');

            defineDataAtualCampo("#txtDataAnotacaoServicoSocial");
            $("#slcTiposAtendimentos").val('0');
            $("#slcSubTiposAtendimentos").val('0');
            $("#txtOutraAtividade").val('');
            $("#txtDescricaoAcaoServicoSocial").val('');
            $("#uplDocServicoSocial").val('');
            $("#boxSubTiposAtendimentosAtividades").html('');
        }
    });

}

function defineDataAtualCampo(selector){
    var hoje = new Date();
    var ano = hoje.getFullYear();
    var mes = String(hoje.getMonth() + 1).padStart(2, '0');
    var dia = String(hoje.getDate()).padStart(2, '0');

    $(selector).val(ano + '-' + mes + '-' + dia);
}

function validaIdadeNegligencia(){
    var respostaNegligencia = $("input[name='radNegligencia']:checked").val();
    var idadeNegligencia = $.trim($("#txtIdadeNegligencia").val());
    var respostaViolenciaFisica = $("input[name='radViolenciaFisica']:checked").val();
    var idadeViolenciaFisica = $.trim($("#txtIdadeViolenciaFisica").val());
    var respostaViolenciaParceiros = $("input[name='radViolenciaParceiros']:checked").val();
    var idadeViolenciaParceiros = $.trim($("#txtIdadeViolenciaParceiros").val());
    var respostaPendenciaJudicial = $("input[name='radPendenciaJudicial']:checked").val();
    var motivoPendenciaJudicial = $.trim($("#txtMotivoPendencia").val());

    if(respostaNegligencia && respostaNegligencia.indexOf('Sim,')===0 && idadeNegligencia==""){
        alert("Preencha o campo 'Qual idade?' da pergunta de negligência.");
        $("#txtIdadeNegligencia").focus();
        return false;
    }

    if(!respostaNegligencia || respostaNegligencia.indexOf('Sim,')!==0){
        $("#txtIdadeNegligencia").val("");
    }

    if(respostaViolenciaFisica && respostaViolenciaFisica.indexOf('Sim,')===0 && idadeViolenciaFisica==""){
        alert("Preencha o campo 'Qual idade?' da pergunta de violência física.");
        $("#txtIdadeViolenciaFisica").focus();
        return false;
    }

    if(!respostaViolenciaFisica || respostaViolenciaFisica.indexOf('Sim,')!==0){
        $("#txtIdadeViolenciaFisica").val("");
    }

    if(respostaViolenciaParceiros=='Sim' && idadeViolenciaParceiros==""){
        alert("Preencha o campo 'Qual idade?' da pergunta de violência por parceiros.");
        $("#txtIdadeViolenciaParceiros").focus();
        return false;
    }

    if(respostaViolenciaParceiros!='Sim'){
        $("#txtIdadeViolenciaParceiros").val("");
    }

    if(respostaPendenciaJudicial=='Sim' && motivoPendenciaJudicial==""){
        alert("Preencha o campo 'Qual o motivo?' da pendência judicial.");
        $("#txtMotivoPendencia").focus();
        return false;
    }

    if(respostaPendenciaJudicial!='Sim'){
        $("#txtMotivoPendencia").val("");
    }

    return true;
}

function cadastraDadosSensiveis(){
    if(!validaIdadeNegligencia()){
        return;
    }

    var id = $("#hidEntrada").val();
    var form = $("#formDadosSensiveis")[0];
    var data = new FormData(form);
    data.append('id',id);
	$.ajax({
	  type: "POST",
      enctype: 'multipart/form-data',
	  url: "https://portal.seds.sp.gov.br/coed/public/componentes/prontuario_acolhido/model/cadastraDadosSensiveis.php",
	  data: data,
      processData: false,
      cache: false,
      contentType: false,
	  success: function (retorno) {
        var idRetorno = parseInt($.trim(retorno), 10);
        if(!idRetorno || idRetorno <= 0){
            alert("Nao foi possivel cadastrar dados sensiveis.\nRetorno: " + retorno);
            return;
        }

        alert('Registros efetuados');

        $("#boxBotaoDadosSensiveis").html('<button type="button" class="btn btn-success mt-5 mb-3 mx-0" id="btnEditar">Alterar dados sensíveis</button>');
		    $("#btnEditar").click(function() {editaDadosSensiveis(idRetorno)});
            carregaDadosSensiveis();
	    },
      error: function(xhr){
        alert("Erro ao cadastrar dados sensiveis: " + (xhr.responseText || xhr.statusText));
      }

	});
}

function editaDadosSensiveis(id){
    if(!validaIdadeNegligencia()){
        return;
    }

    var form = $("#formDadosSensiveis")[0];
    var data = new FormData(form);
    data.append('id',id);
	$.ajax({
	  type: "POST",
      enctype: 'multipart/form-data',
	  url: "https://portal.seds.sp.gov.br/coed/public/componentes/prontuario_acolhido/model/editaDadosSensiveis.php",
	  data: data,
      processData: false,
      cache: false,
      contentType: false,
	  success: function (retorno) {
        if($.trim(retorno) !== "" && isNaN(parseInt($.trim(retorno), 10))){
            alert("Nao foi possivel editar dados sensiveis.\nRetorno: " + retorno);
            return;
        }
        carregaDadosSensiveis();
        alert('Alterações efetuadas');
      }

	});
}

function carregaDadosSensiveis(){
	var id = $("#hidEntrada").val();
	$.ajax({
		url:'https://portal.seds.sp.gov.br/coed/public/componentes/prontuario_acolhido/model/carregaDadosSensiveis.php',
		dataType: 'JSON',
		type: 'POST',
		data: {id:id},
		success: function(resultado){

			if(jQuery.isEmptyObject(resultado)==false){
                //NEGLIGÊNCIA
                if (resultado.negligencia.includes('acolhimento institucional')){
                    $("#radNegligencia1").prop('checked',true);
                }
                if (resultado.negligencia.includes('poder familiar')){
                    $("#radNegligencia2").prop('checked',true);
                }
                if (resultado.negligencia.includes('protetiva')){
                    $("#radNegligencia3").prop('checked',true);
                }
                if (resultado.negligencia=='Não'){
                    $("#radNegligencia4").prop('checked',true);
                }
                if (resultado.negligencia.includes('Não foi possível')){
                    $("#radNegligencia5").prop('checked',true);
                }
                if (resultado.negligencia.includes('Há indícios')){
                    $("#radNegligencia6").prop('checked',true);
                }
                if (resultado.negligencia.indexOf('Sim,')===0){
                    $("#boxIdadeNegligencia").removeClass("d-none");
                    $("#txtIdadeNegligencia").val(resultado.negligencia_idade);
                }
                else{
                    $("#boxIdadeNegligencia").addClass("d-none");
                    $("#txtIdadeNegligencia").val("");
                }

                //VIOLÊNCIA FÍSICA
                if (resultado.violencia_fisica.includes('acolhimento institucional')){
                    $("#radViolenciaFisica1").prop('checked',true);
                }
                if (resultado.violencia_fisica.includes('poder familiar')){
                    $("#radViolenciaFisica2").prop('checked',true);
                }
                if (resultado.violencia_fisica.includes('protetiva')){
                    $("#radViolenciaFisica3").prop('checked',true);
                }
                if (resultado.violencia_fisica=='Não'){
                    $("#radViolenciaFisica4").prop('checked',true);
                }
                if (resultado.violencia_fisica.includes('Não foi possível')){
                    $("#radViolenciaFisica5").prop('checked',true);
                }
                if (resultado.violencia_fisica.includes('Há indícios')){
                    $("#radViolenciaFisica6").prop('checked',true);
                }
                if (resultado.violencia_fisica.indexOf('Sim,')===0){
                    $("#boxIdadeViolenciaFisica").removeClass("d-none");
                    $("#txtIdadeViolenciaFisica").val(resultado.violencia_fisica_idade);
                }
                else{
                    $("#boxIdadeViolenciaFisica").addClass("d-none");
                    $("#txtIdadeViolenciaFisica").val("");
                }

                //VIOLÊNCIA SEXUAL
                if (resultado.violencia_sexual.includes('Sim')){
                    $("#radViolenciaSexual1").prop('checked',true);
                    $("#boxIdade").removeClass("d-none");
                    $("#txtQualIdade").val(resultado.violencia_sexual_idade);
                }
                if (resultado.violencia_sexual=='Não'){
                    $("#radViolenciaSexual2").prop('checked',true);
                }
                if (resultado.violencia_sexual.includes('Não foi possível')){
                    $("#radViolenciaSexual3").prop('checked',true);
                }
                if (resultado.violencia_sexual.includes('Há indícios')){
                    $("#radViolenciaSexual4").prop('checked',true);
                }

                $("#txtObservacoesViolenciaSexual").val(resultado.observacoes_violencia_sexual);

                //AGRESSOR
                if (resultado.agressor.includes('Pai')){
                    $("#chkAgressor1").prop('checked',true);
                }
                if (resultado.agressor.includes('Mãe')){
                    $("#chkAgressor2").prop('checked',true);
                }
                if (resultado.agressor.includes('Padrasto')){
                    $("#chkAgressor3").prop('checked',true);
                }
                if (resultado.agressor.includes('Madrasta')){
                    $("#chkAgressor4").prop('checked',true);
                }
                if (resultado.agressor.includes('Tios/primos/sobrinhos')){
                    $("#chkAgressor5").prop('checked',true);
                }
                if (resultado.agressor.includes('Irmãos')){
                    $("#chkAgressor6").prop('checked',true);
                }
                if (resultado.agressor.includes('Pessoas de confiança da família')){
                    $("#chkAgressor7").prop('checked',true);
                }
                if (resultado.agressor.includes('Desconhecidos')){
                    $("#chkAgressor8").prop('checked',true);
                }
                if (resultado.agressor.includes('Não se aplica')){
                    $("#chkAgressor9").prop('checked',true);
                }

                //VIOLÊNCIA PARCEIRO
                if (resultado.violencia_parceiros=='Não'){
                    $("#radViolenciaParceiros1").prop('checked',true);
                }
                if (resultado.violencia_parceiros.includes('Não foi possível')){
                    $("#radViolenciaParceiros2").prop('checked',true);
                }
                if (resultado.violencia_parceiros.includes('Há indícios')){
                    $("#radViolenciaParceiros3").prop('checked',true);
                }
                if (resultado.violencia_parceiros.includes('Sim')){
                    $("#radViolenciaParceiros4").prop('checked',true);
                    $("#boxViolenciaParceiros").removeClass("d-none");
                    $("#boxIdadeViolenciaParceiros").removeClass("d-none");
                    $("#txtIdadeViolenciaParceiros").val(resultado.violencia_parceiros_idade);
                    
                    if (resultado.tipos_violencia_parceiros.includes('Patrimonial')){
                        $("#chkTipoViolenciaParceiro1").prop('checked',true);
                    }
                    if (resultado.tipos_violencia_parceiros.includes('Física')){
                        $("#chkTipoViolenciaParceiro2").prop('checked',true);
                    }
                    if (resultado.tipos_violencia_parceiros.includes('Psicológica')){
                        $("#chkTipoViolenciaParceiro3").prop('checked',true);
                    }
                    if (resultado.tipos_violencia_parceiros.includes('Sexual')){
                        $("#chkTipoViolenciaParceiro4").prop('checked',true);
                    }
                    if (resultado.tipos_violencia_parceiros.includes('Moral')){
                        $("#chkTipoViolenciaParceiro5").prop('checked',true);
                    }

                    if (resultado.suporte_violencia_parceiros.includes('Sim')){
                        $("#radSuporte1").prop('checked',true);
                        $("#boxSuporte").removeClass("d-none");
                        $("#txtQualSuporte").val(resultado.tipo_suporte);
                    }
                    else{
                        $("#radSuporte2").prop('checked',true);
                    }
                }
                else{
                    $("#boxIdadeViolenciaParceiros").addClass("d-none");
                    $("#txtIdadeViolenciaParceiros").val("");
                }

                //AUTOR DE VIOLÊNCIA
                if (resultado.autor_violencia=='Não'){
                    $("#radAutorViolencia1").prop('checked',true);
                }
                if (resultado.autor_violencia.includes('Não foi possível')){
                    $("#radAutorViolencia2").prop('checked',true);
                }
                if (resultado.autor_violencia.includes('Há indícios')){
                    $("#radAutorViolencia3").prop('checked',true);
                }
                if (resultado.autor_violencia.includes('Sim')){
                    $("#radAutorViolencia4").prop('checked',true);
                    $("#boxAutorViolencia").removeClass("d-none");
                    
                    if (resultado.tipo_autor_violencia.includes('Patrimonial')){
                        $("#chkTipoViolencia1").prop('checked',true);
                    }
                    if (resultado.tipo_autor_violencia.includes('Física')){
                        $("#chkTipoViolencia2").prop('checked',true);
                    }
                    if (resultado.tipo_autor_violencia.includes('Psicológica')){
                        $("#chkTipoViolencia3").prop('checked',true);
                    }
                    if (resultado.tipo_autor_violencia.includes('Sexual')){
                        $("#chkTipoViolencia4").prop('checked',true);
                    }
                    if (resultado.tipo_autor_violencia.includes('Moral')){
                        $("#chkTipoViolencia5").prop('checked',true);
                    }

                    if (resultado.responsabilizado=='Foi responsabilizado criminalmente'){
                        $("#radResponsabilizado2").prop('checked',true);
                        $("#boxPenaAplicada").removeClass("d-none");

                        if (resultado.pena_aplicada.includes('Pena alternativa')){
                            $("#radPenaAplicada1").prop('checked',true);
                        }
                        if (resultado.pena_aplicada.includes('Audiência de custódia')){
                            $("#radPenaAplicada2").prop('checked',true);
                        }
                        if (resultado.pena_aplicada.includes('Sentença em regime semiaberto')){
                            $("#radPenaAplicada3").prop('checked',true);
                        }
                        if (resultado.pena_aplicada.includes('Sentença em regime fechado')){
                            $("#radPenaAplicada4").prop('checked',true);
                            $("#txtTempoPenaAplicada").val(resultado.tempo_pena_aplicada);
                            $("#boxTempoPenaAplicada").removeClass("d-none");
                        }

                    }
                    else{
                        $("#radResponsabilizado1").prop('checked',true);
                    }

                    if (resultado.suporte_violencia_parceiros.includes('Sim')){
                        $("#radSuporte1").prop('checked',true);
                        $("#boxSuporte").removeClass("d-none");
                        $("#txtQualSuporte").val(resultado.tipo_suporte);
                    }
                    else{
                        $("#radSuporte2").prop('checked',true);
                    }
                }

                //EGRESSO
                if (resultado.egresso_sistema_prisional=='Não'){
                    $("#radEgresso2").prop('checked',true);
                }
                else{
                    $("#radEgresso1").prop('checked',true);
                    $("#boxEgresso").removeClass("d-none");
                    if (resultado.pena_egresso.includes('Pena alternativa')){
                        $("#radEgressoPena1").prop('checked',true);
                    }
                    if (resultado.pena_egresso.includes('Audiência de custódia')){
                        $("#radEgressoPena2").prop('checked',true);
                    }
                    if (resultado.pena_egresso.includes('Sentença em regime semiaberto')){
                        $("#radEgressoPena3").prop('checked',true);
                    }
                    if (resultado.pena_egresso.includes('Sentença em regime fechado')){
                        $("#radEgressoPena4").prop('checked',true);
                        $("#txtTempoPenaEgresso").val(resultado.tempo_pena_egresso);
                        $("#boxSentenca").removeClass("d-none");
                    }

                }

                if (resultado.cumpriu_pena=='Sim'){
                    $("#radCumpriuPena1").prop('checked',true);
                }else{
                    $("#radCumpriuPena2").prop('checked',true);
                }

                if (resultado.foragido=='Sim'){
                    $("#radForagido1").prop('checked',true);
                }else{
                    $("#radForagido2").prop('checked',true);
                }

                if (resultado.liberdade_provisoria=='Sim'){
                    $("#radLiberdade1").prop('checked',true);
                }else{
                    $("#radLiberdade2").prop('checked',true);
                }

                //PENDENCIA JUDICIAL
                if (resultado.pendencia_judicial=='Não'){
                    $("#radPendenciaJudicial2").prop('checked',true);
                    $("#boxPendencia").addClass("d-none");
                    $("#txtMotivoPendencia").val("");
                }
                else if (resultado.pendencia_judicial=='Sim'){
                    $("#radPendenciaJudicial1").prop('checked',true);
                    $("#boxPendencia").removeClass("d-none");
                    $("#txtMotivoPendencia").val(resultado.motivo_pendencia_judicial);
                }
                else{
                    $("#boxPendencia").addClass("d-none");
                    $("#txtMotivoPendencia").val("");
                }
                
                $("#boxBotaoDadosSensiveis").html('<button type="button" class="btn btn-success mt-5 mb-3 mx-0" id="btnEditar">Alterar dados sensíveis</button>');
                $("#btnEditar").click(function() {editaDadosSensiveis(resultado.dados_sensiveis_id)});

			}
		},
		complete: function(){}
	 });
}

function cadastraDesligamento(){
    var id = $("#hidEntrada").val();
    var form = $("#formDesligamento")[0];
    var data = new FormData(form);
    data.append('id',id);
	$.ajax({
	  type: "POST",
      enctype: 'multipart/form-data',
	  url: "https://portal.seds.sp.gov.br/coed/public/componentes/prontuario_acolhido/model/cadastraDesligamento.php",
	  data: data,
      processData: false,
      cache: false,
      contentType: false,
	  success: function (retorno) {
        alert('Desligamento efetuado');
		location.href = "prontuario_acolhido/" + id;
	  }
	});
}

function carregaDesligamento(){
	id = $("#hidEntrada").val();
	$.ajax({
		url:'https://portal.seds.sp.gov.br/coed/public/componentes/prontuario_acolhido/model/carregaDesligamento.php',
		dataType: 'JSON',
		type: 'POST',
		data: {id:id},
		success: function(resultado){

			if(jQuery.isEmptyObject(resultado)==false){

                $("#aba-desligamento").removeClass("d-none");
                $("#boxInfoDesligamento").removeClass("d-none");
                $("#boxInfoEncaminhamento").removeClass("d-none");

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
                        switch(resultado.tipo_encaminhamento_hub_id){
                            case 1:
                                $("#tipoEncaminhamentoHub1_1").prop('checked',true);
                            break;
                        }
                    break;
                    case '2':
                        $("#boxInfoDesligamentos").removeClass("d-none");
                        $("#boxInfoDesligamentosQualificado").removeClass("d-none");

                        if (resultado.desligamento_motivo.includes('Cumprimento')){
                            $("#chkMotivosDesligamentoQualificado1").prop('checked',true);
                        }
                        if (resultado.desligamento_motivo.includes('Melhora')){
                            $("#chkMotivosDesligamentoQualificado2").prop('checked',true);
                        }
                        if (resultado.desligamento_motivo.includes('autocuidado')){
                            $("#chkMotivosDesligamentoQualificado3").prop('checked',true);
                        }
                        if (resultado.desligamento_motivo.includes('Consciência')){
                            $("#chkMotivosDesligamentoQualificado4").prop('checked',true);
                        }
                        if (resultado.desligamento_motivo.includes('habilidades')){
                            $("#chkMotivosDesligamentoQualificado5").prop('checked',true);
                        }
                        if (resultado.desligamento_motivo.includes('trabalho')){
                            $("#chkMotivosDesligamentoQualificado6").prop('checked',true);
                        }
                        if (resultado.desligamento_motivo.includes('sustento')){
                            $("#chkMotivosDesligamentoQualificado7").prop('checked',true);
                        }
                        if (resultado.desligamento_motivo.includes('moradia')){
                            $("#chkMotivosDesligamentoQualificado8").prop('checked',true);
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
                        if(resultado.desligamento_motivo.includes("saúde")){
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

                if (resultado.desligamento_impactos.includes('ENCEJA')){
                    $("#chkImpactos1").prop('checked',true);
                }
                if (resultado.desligamento_impactos.includes('superior')){
                    $("#chkImpactos2").prop('checked',true);
                }
                if (resultado.desligamento_impactos.includes('profissionalização')){
                    $("#chkImpactos3").prop('checked',true);
                }
                if (resultado.desligamento_impactos.includes('vínculos')){
                    $("#chkImpactos4").prop('checked',true);
                }
                if (resultado.desligamento_impactos.includes('protetivo')){
                    $("#chkImpactos5").prop('checked',true);
                }
                if (resultado.desligamento_impactos.includes('situação de rua')){
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
                // if (resultado.desligamento_impactos.includes('autossustento')){
                //     $("#chkImpactos12").prop('checked',true);
                // }
                if (
                    resultado.desligamento_impactos.includes('autossustento') ||
                    resultado.desligamento_impactos.includes('auto sustento')
                ){
                    $("#chkImpactos12").prop('checked',true);
                }

                if (resultado.desligamento_impactos.includes('Bancarização')){
                    $("#chkImpactos13").prop('checked',true);
                }
                if (resultado.desligamento_impactos.includes('Não houve')){
                    $("#chkImpactos14").prop('checked',true);
                }
                if (
                    resultado.desligamento_impactos.includes('Capacidade de Autocuidado e Auto-organização') ||
                    resultado.desligamento_impactos.includes('Capacidade de Autocuidado e Autoorganização')
                ){
                    $("#chkImpactos15").prop('checked',true);
                }

                //INICIO DO TRATAMENTO DE ENCAMINHAMENTOS
                
                if (resultado.acolhido_encaminhado_hub==1){
                    $("#desligamento_hub_sim").prop('checked',true);
                    $("#boxRetornoSP").removeClass('d-none');
                    carregaEncaminhamentosHub(resultado.tipo_encaminhamento_hub_id);
                    switch(resultado.tipo_encaminhamento_hub_id){
                        case 1:
                            $("#tipoEncaminhamentoHub1_1").prop('checked',true);
                        break;
                        case 2:
                            $("#tipoEncaminhamentoHub1_2").prop('checked',true);
                        break;
                        case 3:
                            $("#tipoEncaminhamentoHub1_3").prop('checked',true);
                        break;
                        case 4:
                            $("#tipoEncaminhamentoHub1_4").prop('checked',true);
                        break;
                        case 5:
                            $("#tipoEncaminhamentoHub1_5").prop('checked',true);
                        break;
                    }
                } else if (resultado.acolhido_encaminhado_hub==0){
                    $("#desligamento_hub_nao").prop('checked',true);
                    $("#boxRetornoSP").addClass('d-none');
                    $("#boxRetornoSPOpcoes").html("");
                }

                carregaEncaminhamentos(resultado.tipo_desligamento_id, resultado.tipo_encaminhamento_id);
                carregaEncaminhamentoRealizado(resultado.tipo_encaminhamento_realizado_id);

                if (resultado.tipo_encaminhamento_realizado_outros_equipamentos) {
                    $("#txtOutroEquipSaude").val(resultado.tipo_encaminhamento_realizado_outros_equipamentos);
                }
                if (resultado.tipo_encaminhamento_realizado_outro) {
                    $("#txtOutroDestino").val(resultado.tipo_encaminhamento_realizado_outro);
                }


                setTimeout("$('#formDesligamento input[type=text], #formDesligamento input[type=date], #formDesligamento input[type=radio], #formDesligamento input[type=checkbox], #formDesligamento textarea, #formDesligamento select').prop('disabled', true)",1000);
				$("#btnCadDesligamento").addClass('d-none');
                $("#boxInfoDesligamentos").removeClass('d-none');
			}
		},
		complete: function(){}
	 });
}

function boxControl(id,act,obj){

    let box = $("#" + id);
    let sel = $("#" + obj);

    if(obj!=0){

        switch(id){
            case 'boxOutraAtividade':
                var textoSelecionadoOutraAtividade = $.trim(sel.find("option:selected").text());
                if (sel.val()=='Outro' || textoSelecionadoOutraAtividade=='Outro'){
                    box.removeClass('d-none');
                }else{
                    box.addClass('d-none');
                }
            break;
            case 'boxOutraSubstancia':
                if (sel.val()=='Outro(a)'){
                    box.removeClass('d-none');
                }else{
                    box.addClass('d-none');
                }
            break;
            case 'boxHistoricoFamilia':
                if (sel.val()=='Sim'){
                    box.removeClass('d-none');
                }else{
                    box.addClass('d-none');
                }
            break;
            case 'boxOutroOfertou':
                if (sel.val()=='Outros'){
                    box.removeClass('d-none');
                }else{
                    box.addClass('d-none');
                }
            break;
            case 'boxOutroOfertouExperiencia':
                if (sel.val()=='Outros'){
                    box.removeClass('d-none');
                }else{
                    box.addClass('d-none');
                }
            break;
            case 'boxIdadeIniciou':
                if (sel.val()=='Acima dos 46'){
                    box.removeClass('d-none');
                }else{
                    box.addClass('d-none');
                }
            break;
            case 'boxTraumasIndividuais':
                if (sel.val()=='Traumas individuais'){
                    box.removeClass('d-none');
                }else{
                    box.addClass('d-none');
                }
            break;
            case 'boxOutroTrauma':
                if (sel.val()=='Outros'){
                    box.removeClass('d-none');
                }else{
                    box.addClass('d-none');
                }
            break;
            case 'boxRelacao':
                if (sel.val()=='Sim'){
                    box.removeClass('d-none');
                }else{
                    box.addClass('d-none');
                }
            break;
            case 'boxTipoAjuda':
                if (sel.val()=='Sim'){
                    box.removeClass('d-none');
                }else{
                    box.addClass('d-none');
                }
            break;
            case 'boxFrequentouCenas':
                if (sel.val()=='Sim'){
                    box.removeClass('d-none');
                }else{
                    box.addClass('d-none');
                }
            break;
            case 'boxRelacaoAjuda':
                if (sel.val()=='Sim'){
                    box.removeClass('d-none');
                }else{
                    box.addClass('d-none');
                }
            break;
            case 'boxTipoTratamento':
                if (sel.val()=='Sim'){
                    box.removeClass('d-none');
                }else{
                    box.addClass('d-none');
                }
            break;
            case 'boxInternacao':
                if (sel.val()=='Sim'){
                    box.removeClass('d-none');
                }else{
                    box.addClass('d-none');
                }
            break;
            case 'boxTraumaRecaida':
                if (sel.val()=='Traumas individuais'){
                    box.removeClass('d-none');
                }else{
                    box.addClass('d-none');
                }
            break;
            case 'boxOutroTraumaRecaida':
                if (sel.val()=='Outros'){
                    box.removeClass('d-none');
                }else{
                    box.addClass('d-none');
                }
            break;
            case 'boxQtdRecaidaUsoDrogas':
                if (sel.val()=='Sim'){
                    box.removeClass('d-none');
                }else{
                    box.addClass('d-none');
                    $("#txtQtdRecaidaUsoDrogas").val("");
                }
            break;
            case 'boxQtdInternacaoDesintoxicacao':
                if (sel.val()=='Sim'){
                    box.removeClass('d-none');
                }else{
                    box.addClass('d-none');
                    $("#txtQtdInternacaoDesintoxicacao").val("");
                }
            break;

        }

    }
    else{
        if(id=="boxOutroTranstorno" || id=="boxOutraDoenca" || id=="boxOutroMembroFamilia" || id=="boxRompimentoVinculos"){
            box.slideToggle();
        }
        else{
            if (act==1){
                box.removeClass('d-none');
                box.slideDown();
            }else{
                box.slideUp();
            }
        }
    
    }

}

function controlaSituacaoRuaPorMoradia(exibir){
    if (exibir == 1){
        boxControl('boxSituacaoRua',1,0);
    }
    else{
        boxControl('boxSituacaoRua',0,0);
        $("#slcTempoSituacaoRua").val("0");
        $("#txtEstavaSituacaoRua").val("");
        $("input[name='chkMotivosRua[]']").prop("checked", false);
    }
}

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

function defineStatusFonteIdentificacao(chave, disponivel){
    if(!Object.prototype.hasOwnProperty.call(fontesIdentificacao, chave)){
        return;
    }

    fontesIdentificacao[chave] = !!disponivel;
    atualizaBloqueioIdentificacao();
}

function atualizaBloqueioIdentificacao(){
    var faltantes = [];

    if(!fontesIdentificacao.etnia){
        faltantes.push("Etnia");
    }
    if(!fontesIdentificacao.tipoCertidao){
        faltantes.push("Tipo de Certidão");
    }
    if(!fontesIdentificacao.registroCartorio){
        faltantes.push("Registro em Cartório");
    }
    if(!fontesIdentificacao.ufRegistro){
        faltantes.push("UF do Registro");
    }

    var btnSalvar = $("#tabIdentificacao #btnCadIdentificacao").first();
    if(btnSalvar.length){
        btnSalvar.prop("disabled", faltantes.length > 0);
    }

    var boxAviso = $("#tabIdentificacao #boxAvisoFontesIdentificacao");
    if(!boxAviso.length && btnSalvar.length){
        boxAviso = $("<div id='boxAvisoFontesIdentificacao' class='small text-danger mt-2'></div>");
        btnSalvar.after(boxAviso);
    }

    if(boxAviso.length){
        if(faltantes.length > 0){
            boxAviso.text("Salvamento bloqueado: não foi possível carregar do banco os campos " + faltantes.join(", ") + ".");
        }
        else{
            boxAviso.text("");
        }
    }
}

function fontesIdentificacaoProntas(){
    return (
        fontesIdentificacao.etnia &&
        fontesIdentificacao.tipoCertidao &&
        fontesIdentificacao.registroCartorio &&
        fontesIdentificacao.ufRegistro
    );
}

function inicializaAbaIdentificacao(){
    defineStatusFonteIdentificacao("etnia", false);
    defineStatusFonteIdentificacao("tipoCertidao", false);
    defineStatusFonteIdentificacao("registroCartorio", false);
    defineStatusFonteIdentificacao("ufRegistro", false);

    $(document)
        .off("change.identificacaoUf", "#slcUfRegistro")
        .on("change.identificacaoUf", "#slcUfRegistro", function(){
            carregaMunicipiosRegistroIdentificacao($(this).val(), 0);
        });

    $(document)
        .off("click.identificacaoSalvar", "#tabIdentificacao #btnCadIdentificacao")
        .on("click.identificacaoSalvar", "#tabIdentificacao #btnCadIdentificacao", function(){
            salvaIdentificacao();
        });

    carregaIdentificacao();
}

function carregaIdentificacao(){
    var entradaId = $("#hidEntrada").val();

    $.ajax({
        type: "POST",
        url: "../public/componentes/prontuario_acolhido/model/carregaIdentificacao.php",
        dataType: "JSON",
        data: {id: entradaId},
        success: function(resultado){
            if(resultado && !jQuery.isEmptyObject(resultado)){
                prontuarioIdentificacaoId = parseInt(resultado.prontuario_identificacao_id, 10) || 0;

                $("#txtNacionalidade").val(resultado.nacionalidade || "");
                $("#txtNaturalidade").val(resultado.naturalidade || "");
                $("#txtNomeCartorio").val(resultado.nome_cartorio || "");
                $("#txtDataRegistro").val(resultado.data_registro || "");
                $("#txtNLivro").val(resultado.numero_livro || "");
                $("#txtNFolha").val(resultado.numero_folha || "");
                $("#txtNTermo").val(resultado.numero_termo_rani || "");
                $("#txtMatricula").val(resultado.matricula || "");

                carregaEtniaIdentificacao(resultado.etnia_id || 0);
                carregaTiposCertidaoIdentificacao(resultado.tipo_certidao_id || 0);
                carregaOpcoesRegistroCartorioIdentificacao(resultado.registro_cartorio_opcao_id || 0);
                carregaEstadosRegistroIdentificacao(resultado.estado_registro_id || 0, resultado.cidade_registro_id || 0);
            }
            else{
                prontuarioIdentificacaoId = 0;
                carregaEtniaIdentificacao(0);
                carregaTiposCertidaoIdentificacao(0);
                carregaOpcoesRegistroCartorioIdentificacao(0);
                carregaEstadosRegistroIdentificacao(0, 0);
            }
        },
        error: function(){
            prontuarioIdentificacaoId = 0;
            carregaEtniaIdentificacao(0);
            carregaTiposCertidaoIdentificacao(0);
            carregaOpcoesRegistroCartorioIdentificacao(0);
            carregaEstadosRegistroIdentificacao(0, 0);
        }
    });
}

function salvaIdentificacao(){
    if(!fontesIdentificacaoProntas()){
        atualizaBloqueioIdentificacao();
        alert("Não é possível salvar enquanto os campos de referência não forem carregados do banco.");
        return;
    }

    if(prontuarioIdentificacaoId > 0){
        editaIdentificacao(prontuarioIdentificacaoId);
    }
    else{
        cadastraIdentificacao();
    }
}

function cadastraIdentificacao(){
    var entradaId = $("#hidEntrada").val();
    if(!entradaId || isNaN(parseInt(entradaId, 10)) || parseInt(entradaId, 10) <= 0){
        alert("ID de acolhimento inválido na tela de Anamnese.");
        return;
    }
    var form = $("#formIdentificacao")[0];
    var data = new FormData(form);
    data.append("id", entradaId);

    $.ajax({
        type: "POST",
        enctype: "multipart/form-data",
        url: "../public/componentes/prontuario_acolhido/model/cadastraIdentificacao.php",
        data: data,
        processData: false,
        cache: false,
        contentType: false,
        success: function(retorno){
            var novoId = parseInt($.trim(retorno), 10) || 0;
            if(novoId > 0){
                prontuarioIdentificacaoId = novoId;
            } else {
                alert("Não foi possível salvar a identificação. Retorno: " + retorno);
                return;
            }
            alert("Identificação registrada");
            carregaIdentificacao();
        },
        error: function(xhr){
            alert("Erro ao salvar identificação: " + (xhr.responseText || xhr.statusText));
        }
    });
}

function editaIdentificacao(id){
    var entradaId = $("#hidEntrada").val();
    if(!entradaId || isNaN(parseInt(entradaId, 10)) || parseInt(entradaId, 10) <= 0){
        alert("ID de acolhimento inválido na tela de Anamnese.");
        return;
    }
    var form = $("#formIdentificacao")[0];
    var data = new FormData(form);
    data.append("id", id);
    data.append("entrada_id", entradaId);

    $.ajax({
        type: "POST",
        enctype: "multipart/form-data",
        url: "../public/componentes/prontuario_acolhido/model/editaIdentificacao.php",
        data: data,
        processData: false,
        cache: false,
        contentType: false,
        success: function(retorno){
            var idRetorno = parseInt($.trim(retorno), 10) || 0;
            if(idRetorno > 0){
                prontuarioIdentificacaoId = idRetorno;
            } else {
                alert("Não foi possível editar a identificação. Retorno: " + retorno);
                return;
            }
            alert("Alterações efetuadas");
            carregaIdentificacao();
        },
        error: function(xhr){
            alert("Erro ao editar identificação: " + (xhr.responseText || xhr.statusText));
        }
    });
}

function normalizaItensIdentificacao(retorno, campoId, campoDescricao){
    var itens = [];

    if(!retorno){
        return itens;
    }

    if(typeof retorno === "string"){
        try{
            retorno = JSON.parse(retorno);
        }
        catch(e){
            return itens;
        }
    }

    if(!Array.isArray(retorno)){
        if(retorno && Array.isArray(retorno.data)){
            retorno = retorno.data;
        }
        else if(retorno && typeof retorno === "object"){
            retorno = [retorno];
        }
        else{
            return itens;
        }
    }

    retorno.forEach(function(item){
        if(!item || typeof item !== "object"){
            return;
        }

        var id = item[campoId];
        if(id === undefined || id === null){
            id = item.id;
        }

        var descricao = item[campoDescricao];
        if(descricao === undefined || descricao === null || descricao === ""){
            descricao = item.descricao || item.nome || item.label || "";
        }

        if(id === undefined || id === null || String(id) === "" || descricao === ""){
            return;
        }

        itens.push({
            id: String(id),
            descricao: String(descricao)
        });
    });

    return itens;
}

function preencheSelectIdentificacao(selector, itens, valorSelecionado){
    var select = $(selector);

    if(!select.length){
        return false;
    }

    select.empty();
    select.append($("<option>").val("0").text(""));

    if(!Array.isArray(itens) || itens.length === 0){
        select.val("0");
        select.prop("disabled", true);
        return false;
    }

    select.prop("disabled", false);

    itens.forEach(function(item){
        select.append($("<option>").val(item.id).text(item.descricao));
    });

    if(valorSelecionado && String(valorSelecionado) !== "0"){
        select.val(String(valorSelecionado));
    }
    else{
        select.val("0");
    }

    return true;
}

function garanteContainerRegistroCartorioIdentificacao(){
    var boxOpcoes = $("#boxRegistroCartorioOpcoes");

    if(boxOpcoes.length){
        boxOpcoes.empty();
        return boxOpcoes;
    }

    var colunaPergunta = $("#tabIdentificacao input[name='radRegistroCartorio']").first().closest(".col-md-12");
    if(!colunaPergunta.length){
        return $();
    }

    // Remove opções estáticas para evitar fallback fora do banco.
    colunaPergunta.find(".form-check").remove();

    boxOpcoes = $("<div id='boxRegistroCartorioOpcoes'></div>");
    colunaPergunta.append(boxOpcoes);

    return boxOpcoes;
}

function carregaEtniaIdentificacao(valorSelecionado){
    $.ajax({
        type: "POST",
        url: "../public/componentes/prontuario_acolhido/model/carregaEtnias.php",
        dataType: "JSON",
        success: function(retorno){
            var itens = normalizaItensIdentificacao(retorno, "etnia_id", "etnia_descricao");
            var ok = preencheSelectIdentificacao("#slcEtnia", itens, valorSelecionado);
            defineStatusFonteIdentificacao("etnia", ok);
        },
        error: function(){
            preencheSelectIdentificacao("#slcEtnia", [], 0);
            defineStatusFonteIdentificacao("etnia", false);
        }
    });
}

function carregaTiposCertidaoIdentificacao(valorSelecionado){
    $.ajax({
        type: "POST",
        url: "../public/componentes/prontuario_acolhido/model/carregaTiposCertidao.php",
        dataType: "JSON",
        success: function(retorno){
            var itens = normalizaItensIdentificacao(retorno, "tipo_certidao_id", "tipo_certidao_descricao");
            var ok = preencheSelectIdentificacao("#slcTipoCertidao", itens, valorSelecionado);
            defineStatusFonteIdentificacao("tipoCertidao", ok);
        },
        error: function(){
            preencheSelectIdentificacao("#slcTipoCertidao", [], 0);
            defineStatusFonteIdentificacao("tipoCertidao", false);
        }
    });
}

function atualizaRadiosRegistroCartorioIdentificacao(itens, valorSelecionado){
    var boxOpcoes = garanteContainerRegistroCartorioIdentificacao();
    if(!boxOpcoes.length){
        return false;
    }

    boxOpcoes.empty();

    if(!Array.isArray(itens) || itens.length === 0){
        return false;
    }

    itens.forEach(function(item){
        var idOpcao = "radRegistroCartorioDb" + item.id;
        var checked = String(valorSelecionado) === String(item.id);
        var divCheck = $("<div>").addClass("form-check");
        var input = $("<input>")
            .addClass("form-check-input")
            .attr("type", "radio")
            .attr("name", "radRegistroCartorio")
            .attr("id", idOpcao)
            .val(item.id);
        var label = $("<label>")
            .addClass("form-check-label")
            .attr("for", idOpcao)
            .text(item.descricao);

        if(checked){
            input.prop("checked", true);
        }

        divCheck.append(input).append(label);
        boxOpcoes.append(divCheck);
    });

    return true;
}

function carregaOpcoesRegistroCartorioIdentificacao(valorSelecionado){
    $.ajax({
        type: "POST",
        url: "../public/componentes/prontuario_acolhido/model/carregaOpcoesRegistroCartorio.php",
        dataType: "JSON",
        success: function(retorno){
            var itens = normalizaItensIdentificacao(retorno, "registro_cartorio_opcao_id", "registro_cartorio_opcao_descricao");
            var ok = atualizaRadiosRegistroCartorioIdentificacao(itens, valorSelecionado);
            defineStatusFonteIdentificacao("registroCartorio", ok);
        },
        error: function(){
            atualizaRadiosRegistroCartorioIdentificacao([], 0);
            defineStatusFonteIdentificacao("registroCartorio", false);
        }
    });
}

function limpaMunicipiosRegistroIdentificacao(){
    var selectMunicipio = $("#slcMunicipioCertidao");
    if(!selectMunicipio.length){
        return;
    }

    selectMunicipio.empty();
    selectMunicipio.append($("<option>").val("0").text(""));
    selectMunicipio.val("0");
    selectMunicipio.prop("disabled", true);
}

function carregaEstadosRegistroIdentificacao(estadoSelecionado, cidadeSelecionada){
    $.ajax({
        type: "POST",
        url: "../public/componentes/prontuario_acolhido/model/carregaEstadosRegistro.php",
        dataType: "JSON",
        success: function(retorno){
            var itens = normalizaItensIdentificacao(retorno, "estado_id", "estado_descricao");
            var ok = preencheSelectIdentificacao("#slcUfRegistro", itens, estadoSelecionado);
            defineStatusFonteIdentificacao("ufRegistro", ok);

            if(ok && estadoSelecionado && String(estadoSelecionado) !== "0"){
                carregaMunicipiosRegistroIdentificacao(estadoSelecionado, cidadeSelecionada);
            }
            else{
                limpaMunicipiosRegistroIdentificacao();
            }
        },
        error: function(){
            preencheSelectIdentificacao("#slcUfRegistro", [], 0);
            limpaMunicipiosRegistroIdentificacao();
            defineStatusFonteIdentificacao("ufRegistro", false);
        }
    });
}

function carregaMunicipiosRegistroIdentificacao(estadoId, cidadeSelecionada){
    if(!estadoId || String(estadoId) === "0"){
        limpaMunicipiosRegistroIdentificacao();
        return;
    }

    $.ajax({
        type: "POST",
        url: "../public/componentes/prontuario_acolhido/model/carregaMunicipiosRegistro.php",
        data: {estado_id: estadoId},
        dataType: "JSON",
        success: function(retorno){
            var itens = normalizaItensIdentificacao(retorno, "cidade_id", "cidade_descricao");
            preencheSelectIdentificacao("#slcMunicipioCertidao", itens, cidadeSelecionada);
        },
        error: function(){
            limpaMunicipiosRegistroIdentificacao();
        }
    });
}

function inicializaAbaHistoricoSocial(){
    $(document)
        .off("change.historicoSocialUfEscola", "#slcUfEscola")
        .on("change.historicoSocialUfEscola", "#slcUfEscola", function(){
            carregaMunicipiosEscolaHistoricoSocial($(this).val(), 0);
        });

    $(document)
        .off("change.historicoSocialFrequentou", "#tabHistoricoSocial input[name='radFrequentouEscola']")
        .on("change.historicoSocialFrequentou", "#tabHistoricoSocial input[name='radFrequentouEscola']", function(){
            atualizaDependenciasEscolaHistoricoSocial();
        });

    $(document)
        .off("change.historicoSocialMoradia", "#tabHistoricoSocial input[name='radCostumaDormir']")
        .on("change.historicoSocialMoradia", "#tabHistoricoSocial input[name='radCostumaDormir']", function(){
            atualizaDependenciasMoradiaHistoricoSocial();
        });

    $(document)
        .off("change.historicoSocialAtividade", "#tabHistoricoSocial input[name='radAtividadeRemunerada']")
        .on("change.historicoSocialAtividade", "#tabHistoricoSocial input[name='radAtividadeRemunerada']", function(){
            atualizaDependenciasAtividadeRemuneradaHistoricoSocial();
        });

    $(document)
        .off("change.historicoSocialTrabalho", "#slcTrabalhoPrincipal")
        .on("change.historicoSocialTrabalho", "#slcTrabalhoPrincipal", function(){
            atualizaDependenciasAtividadeRemuneradaHistoricoSocial();
        });

    $(document)
        .off("change.historicoSocialQualificacao", "#tabHistoricoSocial input[name='radPrecisaQualificacao']")
        .on("change.historicoSocialQualificacao", "#tabHistoricoSocial input[name='radPrecisaQualificacao']", function(){
            atualizaDependenciasQualificacaoHistoricoSocial();
        });

    $(document)
        .off("click.historicoSocialSalvar", "#tabHistoricoSocial #btnCadIdentificacao")
        .on("click.historicoSocialSalvar", "#tabHistoricoSocial #btnCadIdentificacao", function(){
            salvaHistoricoSocial();
        });

    carregaHistoricoSocial();
}

function carregaHistoricoSocial(){
    var entradaId = $("#hidEntrada").val();

    $.ajax({
        type: "POST",
        url: "../public/componentes/prontuario_acolhido/model/carregaHistoricoSocial.php",
        dataType: "JSON",
        data: {id: entradaId},
        success: function(resultado){
            var possuiRegistro = resultado && !jQuery.isEmptyObject(resultado);
            prontuarioHistoricoSocialId = possuiRegistro ? (parseInt(resultado.prontuario_historico_social_id, 10) || 0) : 0;

            limpaFormularioHistoricoSocial();

            if(possuiRegistro){
                aplicaValoresHistoricoSocial(resultado);
            }

            carregaGrausEscolaridadeHistoricoSocial(possuiRegistro ? (resultado.grau_escolaridade_id || 0) : 0);
            carregaAnosSeriesHistoricoSocial(possuiRegistro ? (resultado.ano_serie_id || 0) : 0);
            carregaEstadosEscolaHistoricoSocial(
                possuiRegistro ? (resultado.uf_escola_id || 0) : 0,
                possuiRegistro ? (resultado.municipio_escola_id || 0) : 0
            );
            carregaOndeCostumaDormirHistoricoSocial(possuiRegistro ? (resultado.onde_costuma_dormir_id || 0) : 0);
            carregaFaixasTempoMoradiaHistoricoSocial(possuiRegistro ? (resultado.tempo_moradia_id || 0) : 0);
            carregaRotinaDiurnaHistoricoSocial(possuiRegistro ? (resultado.rotina_diurna_id || 0) : 0);
            carregaFaixasTempoSituacaoRuaHistoricoSocial(possuiRegistro ? (resultado.tempo_situacao_rua_id || 0) : 0);
            carregaTrabalhoPrincipalHistoricoSocial(possuiRegistro ? (resultado.trabalho_principal_id || 0) : 0);
            carregaMotivosRuaHistoricoSocial(possuiRegistro ? (resultado.motivos_rua_ids || []) : []);
            carregaReferenciadaHistoricoSocial(possuiRegistro ? (resultado.referenciada_ids || []) : []);

            atualizaDependenciasEscolaHistoricoSocial();
            atualizaDependenciasAtividadeRemuneradaHistoricoSocial();
            atualizaDependenciasQualificacaoHistoricoSocial();
        },
        error: function(){
            prontuarioHistoricoSocialId = 0;
            limpaFormularioHistoricoSocial();

            carregaGrausEscolaridadeHistoricoSocial(0);
            carregaAnosSeriesHistoricoSocial(0);
            carregaEstadosEscolaHistoricoSocial(0, 0);
            carregaOndeCostumaDormirHistoricoSocial(0);
            carregaFaixasTempoMoradiaHistoricoSocial(0);
            carregaRotinaDiurnaHistoricoSocial(0);
            carregaFaixasTempoSituacaoRuaHistoricoSocial(0);
            carregaTrabalhoPrincipalHistoricoSocial(0);
            carregaMotivosRuaHistoricoSocial([]);
            carregaReferenciadaHistoricoSocial([]);
        }
    });
}

function aplicaValoresHistoricoSocial(resultado){
    marcaRadioBinarioHistoricoSocial("#radSabeLer1", "#radSabeLer2", resultado.sabe_ler_escrever);
    marcaRadioBinarioHistoricoSocial("#radFrequentouEscola1", "#radFrequentouEscola2", resultado.frequentou_escola);
    marcaRadioBinarioHistoricoSocial("#radAtividadeRemunerada1", "#radAtividadeRemunerada2", resultado.atividade_remunerada);
    marcaRadioBinarioHistoricoSocial("#radPrecisaQualificacao1", "#radPrecisaQualificacao2", resultado.precisa_qualificacao);

    $("#txtNomeEscola").val(resultado.nome_escola || "");
    $("#txtEstavaSituacaoRua").val(resultado.situacao_rua_origem || "");
    $("#txtOutroTrabalhoPrincipal").val(resultado.outro_trabalho_principal || "");
    $("#txtQualQualificacao").val(resultado.qualificacao_descricao || "");

    $("#txtAjudaDoacao").val(moedaBancoParaCampoHistoricoSocial(resultado.valor_ajuda_doacao));
    $("#txtAposentadoria").val(moedaBancoParaCampoHistoricoSocial(resultado.valor_aposentadoria));
    $("#txtSeguroDesemprego").val(moedaBancoParaCampoHistoricoSocial(resultado.valor_seguro_desemprego));
    $("#txtPensao").val(moedaBancoParaCampoHistoricoSocial(resultado.valor_pensao_alimenticia));
    $("#txtOutrasdFontes").val(moedaBancoParaCampoHistoricoSocial(resultado.valor_outras_fontes));
}

function limpaFormularioHistoricoSocial(){
    var form = $("#formHistoricoSocial")[0];
    if(form){
        form.reset();
    }

    $("#boxEscola").addClass("d-none");
    $("#boxTempoMoradia").addClass("d-none");
    $("#boxSituacaoRua").addClass("d-none");
    $("#boxAtividadeRemunerada").addClass("d-none");
    $("#boxOutraAtividade").addClass("d-none");
    $("#boxQualificacao").addClass("d-none");

    $("#txtNomeEscola").val("");
    $("#txtEstavaSituacaoRua").val("");
    $("#txtOutroTrabalhoPrincipal").val("");
    $("#txtQualQualificacao").val("");
    $("#txtAjudaDoacao").val("");
    $("#txtAposentadoria").val("");
    $("#txtSeguroDesemprego").val("");
    $("#txtPensao").val("");
    $("#txtOutrasdFontes").val("");

    $("#slcGrauEscolaridade").val("0");
    $("#slcAnoSerie").val("0");
    $("#slcUfEscola").val("0");
    limpaMunicipiosEscolaHistoricoSocial();
    $("#slcTempoMoradia").val("0");
    $("#slcTempoSituacaoRua").val("0");
    $("#slcTrabalhoPrincipal").val("0");

    $("input[name='radRotina']").prop("checked", false);
    $("input[name='radCostumaDormir']").prop("checked", false);
    $("input[name='chkMotivosRua[]']").prop("checked", false);
    $("input[name='chkReferenciada[]']").prop("checked", false);
}

function salvaHistoricoSocial(){
    if(prontuarioHistoricoSocialId > 0){
        editaHistoricoSocial(prontuarioHistoricoSocialId);
    }
    else{
        cadastraHistoricoSocial();
    }
}

function cadastraHistoricoSocial(){
    var entradaId = $("#hidEntrada").val();
    if(!entradaId || isNaN(parseInt(entradaId, 10)) || parseInt(entradaId, 10) <= 0){
        alert("ID de acolhimento inválido na tela de Anamnese.");
        return;
    }

    var form = $("#formHistoricoSocial")[0];
    var data = new FormData(form);
    data.append("id", entradaId);

    $.ajax({
        type: "POST",
        enctype: "multipart/form-data",
        url: "../public/componentes/prontuario_acolhido/model/cadastraHistoricoSocial.php",
        data: data,
        processData: false,
        cache: false,
        contentType: false,
        success: function(retorno){
            var novoId = parseInt($.trim(retorno), 10) || 0;
            if(novoId > 0){
                prontuarioHistoricoSocialId = novoId;
            }
            else{
                alert("Não foi possível salvar o Histórico Social. Retorno: " + retorno);
                return;
            }

            alert("Histórico Social registrado");
            carregaHistoricoSocial();
        },
        error: function(xhr){
            alert("Erro ao salvar Histórico Social: " + (xhr.responseText || xhr.statusText));
        }
    });
}

function editaHistoricoSocial(id){
    var entradaId = $("#hidEntrada").val();
    if(!entradaId || isNaN(parseInt(entradaId, 10)) || parseInt(entradaId, 10) <= 0){
        alert("ID de acolhimento inválido na tela de Anamnese.");
        return;
    }

    var form = $("#formHistoricoSocial")[0];
    var data = new FormData(form);
    data.append("id", id);
    data.append("entrada_id", entradaId);

    $.ajax({
        type: "POST",
        enctype: "multipart/form-data",
        url: "../public/componentes/prontuario_acolhido/model/editaHistoricoSocial.php",
        data: data,
        processData: false,
        cache: false,
        contentType: false,
        success: function(retorno){
            var idRetorno = parseInt($.trim(retorno), 10) || 0;
            if(idRetorno > 0){
                prontuarioHistoricoSocialId = idRetorno;
            }
            else{
                alert("Não foi possível editar o Histórico Social. Retorno: " + retorno);
                return;
            }

            alert("Alterações efetuadas");
            carregaHistoricoSocial();
        },
        error: function(xhr){
            alert("Erro ao editar Histórico Social: " + (xhr.responseText || xhr.statusText));
        }
    });
}

function carregaGrausEscolaridadeHistoricoSocial(valorSelecionado){
    $.ajax({
        type: "POST",
        url: "../public/componentes/prontuario_acolhido/model/carregaGrausEscolaridade.php",
        dataType: "JSON",
        success: function(retorno){
            var itens = normalizaItensIdentificacao(retorno, "grau_escolaridade_id", "grau_escolaridade_descricao");
            preencheSelectIdentificacao("#slcGrauEscolaridade", itens, valorSelecionado);
        },
        error: function(){
            preencheSelectIdentificacao("#slcGrauEscolaridade", [], 0);
        }
    });
}

function carregaAnosSeriesHistoricoSocial(valorSelecionado){
    $.ajax({
        type: "POST",
        url: "../public/componentes/prontuario_acolhido/model/carregaAnosSeries.php",
        dataType: "JSON",
        success: function(retorno){
            var itens = normalizaItensIdentificacao(retorno, "ano_serie_id", "ano_serie_descricao");
            preencheSelectIdentificacao("#slcAnoSerie", itens, valorSelecionado);
        },
        error: function(){
            preencheSelectIdentificacao("#slcAnoSerie", [], 0);
        }
    });
}

function carregaFaixasTempoMoradiaHistoricoSocial(valorSelecionado){
    $.ajax({
        type: "POST",
        url: "../public/componentes/prontuario_acolhido/model/carregaFaixasTempo.php",
        dataType: "JSON",
        success: function(retorno){
            var itens = normalizaItensIdentificacao(retorno, "faixa_tempo_id", "faixa_tempo_descricao");
            preencheSelectIdentificacao("#slcTempoMoradia", itens, valorSelecionado);
        },
        error: function(){
            preencheSelectIdentificacao("#slcTempoMoradia", [], 0);
        }
    });
}

function carregaFaixasTempoSituacaoRuaHistoricoSocial(valorSelecionado){
    $.ajax({
        type: "POST",
        url: "../public/componentes/prontuario_acolhido/model/carregaFaixasTempo.php",
        dataType: "JSON",
        success: function(retorno){
            var itens = normalizaItensIdentificacao(retorno, "faixa_tempo_id", "faixa_tempo_descricao");
            preencheSelectIdentificacao("#slcTempoSituacaoRua", itens, valorSelecionado);
        },
        error: function(){
            preencheSelectIdentificacao("#slcTempoSituacaoRua", [], 0);
        }
    });
}

function carregaTrabalhoPrincipalHistoricoSocial(valorSelecionado){
    $.ajax({
        type: "POST",
        url: "../public/componentes/prontuario_acolhido/model/carregaTrabalhoPrincipal.php",
        dataType: "JSON",
        success: function(retorno){
            var itens = normalizaItensIdentificacao(retorno, "trabalho_principal_id", "trabalho_principal_descricao");
            preencheSelectIdentificacao("#slcTrabalhoPrincipal", itens, valorSelecionado);
            atualizaDependenciasAtividadeRemuneradaHistoricoSocial();
        },
        error: function(){
            preencheSelectIdentificacao("#slcTrabalhoPrincipal", [], 0);
            atualizaDependenciasAtividadeRemuneradaHistoricoSocial();
        }
    });
}

function carregaEstadosEscolaHistoricoSocial(estadoSelecionado, cidadeSelecionada){
    $.ajax({
        type: "POST",
        url: "../public/componentes/prontuario_acolhido/model/carregaEstadosRegistro.php",
        dataType: "JSON",
        success: function(retorno){
            var itens = normalizaItensIdentificacao(retorno, "estado_id", "estado_descricao");
            var ok = preencheSelectIdentificacao("#slcUfEscola", itens, estadoSelecionado);
            if(ok && estadoSelecionado && String(estadoSelecionado) !== "0"){
                carregaMunicipiosEscolaHistoricoSocial(estadoSelecionado, cidadeSelecionada);
            }
            else{
                limpaMunicipiosEscolaHistoricoSocial();
            }
        },
        error: function(){
            preencheSelectIdentificacao("#slcUfEscola", [], 0);
            limpaMunicipiosEscolaHistoricoSocial();
        }
    });
}

function carregaMunicipiosEscolaHistoricoSocial(estadoId, cidadeSelecionada){
    if(!estadoId || String(estadoId) === "0"){
        limpaMunicipiosEscolaHistoricoSocial();
        return;
    }

    $.ajax({
        type: "POST",
        url: "../public/componentes/prontuario_acolhido/model/carregaMunicipiosRegistro.php",
        dataType: "JSON",
        data: {estado_id: estadoId},
        success: function(retorno){
            var itens = normalizaItensIdentificacao(retorno, "cidade_id", "cidade_descricao");
            preencheSelectIdentificacao("#slcMunicipioEscola", itens, cidadeSelecionada);
        },
        error: function(){
            limpaMunicipiosEscolaHistoricoSocial();
        }
    });
}

function limpaMunicipiosEscolaHistoricoSocial(){
    var selectMunicipio = $("#slcMunicipioEscola");
    if(!selectMunicipio.length){
        return;
    }

    selectMunicipio.empty();
    selectMunicipio.append($("<option>").val("0").text(""));
    selectMunicipio.val("0");
    selectMunicipio.prop("disabled", true);
}

function carregaOndeCostumaDormirHistoricoSocial(valorSelecionado){
    $.ajax({
        type: "POST",
        url: "../public/componentes/prontuario_acolhido/model/carregaOndeCostumaDormir.php",
        dataType: "JSON",
        success: function(retorno){
            var itens = normalizaItensIdentificacao(retorno, "onde_costuma_dormir_id", "onde_costuma_dormir_descricao");
            var box = $("#boxOndeCostumaDormirLista");
            box.empty();

            itens.forEach(function(item, idx){
                var idOpcao = "radCostumaDormirDb" + item.id + "_" + (idx + 1);
                var descricaoNormalizada = normalizaTextoHistoricoSocial(item.descricao);
                var exibeSituacaoRua = (
                    descricaoNormalizada === "rua" ||
                    descricaoNormalizada === "albergue" ||
                    descricaoNormalizada === "outros"
                ) ? 1 : 0;
                var exibeTempoMoradia = (descricaoNormalizada === "albergue") ? 1 : 0;

                var divCheck = $("<div>").addClass("form-check form-check-inline");
                var input = $("<input>")
                    .addClass("form-check-input")
                    .attr("type", "radio")
                    .attr("name", "radCostumaDormir")
                    .attr("id", idOpcao)
                    .val(item.id)
                    .attr("data-exibe-situacao-rua", exibeSituacaoRua)
                    .attr("data-exibe-tempo-moradia", exibeTempoMoradia);
                var label = $("<label>")
                    .addClass("form-check-label")
                    .attr("for", idOpcao)
                    .text(item.descricao);

                if(valorSelecionado && String(valorSelecionado) === String(item.id)){
                    input.prop("checked", true);
                }

                divCheck.append(input).append(label);
                box.append(divCheck);
            });

            atualizaDependenciasMoradiaHistoricoSocial();
        },
        error: function(){
            $("#boxOndeCostumaDormirLista").html("");
            atualizaDependenciasMoradiaHistoricoSocial();
        }
    });
}

function carregaRotinaDiurnaHistoricoSocial(valorSelecionado){
    $.ajax({
        type: "POST",
        url: "../public/componentes/prontuario_acolhido/model/carregaRotinaDiurna.php",
        dataType: "JSON",
        success: function(retorno){
            var itens = normalizaItensIdentificacao(retorno, "rotina_diurna_id", "rotina_diurna_descricao");
            var box = $("#boxRotinaDiurnaLista");
            box.empty();

            itens.forEach(function(item, idx){
                var idOpcao = "radRotinaDb" + item.id + "_" + (idx + 1);
                var divCheck = $("<div>").addClass("form-check form-check-inline");
                var input = $("<input>")
                    .addClass("form-check-input")
                    .attr("type", "radio")
                    .attr("name", "radRotina")
                    .attr("id", idOpcao)
                    .val(item.id);
                var label = $("<label>")
                    .addClass("form-check-label")
                    .attr("for", idOpcao)
                    .text(item.descricao);

                if(valorSelecionado && String(valorSelecionado) === String(item.id)){
                    input.prop("checked", true);
                }

                divCheck.append(input).append(label);
                box.append(divCheck);
            });
        },
        error: function(){
            $("#boxRotinaDiurnaLista").html("");
        }
    });
}

function carregaMotivosRuaHistoricoSocial(valoresSelecionados){
    $.ajax({
        type: "POST",
        url: "../public/componentes/prontuario_acolhido/model/carregaMotivosRua.php",
        dataType: "JSON",
        success: function(retorno){
            var itens = normalizaItensIdentificacao(retorno, "motivo_rua_id", "motivo_rua_descricao");
            var selecionados = normalizaListaIdsHistoricoSocial(valoresSelecionados);
            var box = $("#boxMotivosRuaLista");
            box.empty();

            var colA = $("<div>").addClass("col-5");
            var colB = $("<div>").addClass("col-6");

            itens.forEach(function(item, idx){
                var idOpcao = "chkMotivosRuaDb" + item.id + "_" + (idx + 1);
                var divCheck = $("<div>").addClass("form-check");
                var input = $("<input>")
                    .addClass("form-check-input")
                    .attr("type", "checkbox")
                    .attr("name", "chkMotivosRua[]")
                    .attr("id", idOpcao)
                    .val(item.id);
                var label = $("<label>")
                    .addClass("form-check-label")
                    .attr("for", idOpcao)
                    .text(item.descricao);

                if(selecionados.indexOf(String(item.id)) !== -1){
                    input.prop("checked", true);
                }

                divCheck.append(input).append(label);
                if(idx % 2 === 0){
                    colA.append(divCheck);
                }
                else{
                    colB.append(divCheck);
                }
            });

            box.append(colA).append(colB);
        },
        error: function(){
            $("#boxMotivosRuaLista").html("");
        }
    });
}

function carregaReferenciadaHistoricoSocial(valoresSelecionados){
    $.ajax({
        type: "POST",
        url: "../public/componentes/prontuario_acolhido/model/carregaReferenciada.php",
        dataType: "JSON",
        success: function(retorno){
            var itens = normalizaItensIdentificacao(retorno, "referenciada_id", "referenciada_descricao");
            var selecionados = normalizaListaIdsHistoricoSocial(valoresSelecionados);
            var box = $("#boxReferenciadaLista");
            box.empty();

            var col = $("<div>").addClass("col-5");
            itens.forEach(function(item, idx){
                var idOpcao = "chkReferenciadaDb" + item.id + "_" + (idx + 1);
                var divCheck = $("<div>").addClass("form-check");
                var input = $("<input>")
                    .addClass("form-check-input")
                    .attr("type", "checkbox")
                    .attr("name", "chkReferenciada[]")
                    .attr("id", idOpcao)
                    .val(item.id);
                var label = $("<label>")
                    .addClass("form-check-label")
                    .attr("for", idOpcao)
                    .text(item.descricao);

                if(selecionados.indexOf(String(item.id)) !== -1){
                    input.prop("checked", true);
                }

                divCheck.append(input).append(label);
                col.append(divCheck);
            });

            box.append(col);
        },
        error: function(){
            $("#boxReferenciadaLista").html("");
        }
    });
}

function atualizaDependenciasEscolaHistoricoSocial(){
    var frequentouEscola = $("#radFrequentouEscola1").is(":checked");
    if(frequentouEscola){
        $("#boxEscola").removeClass("d-none");
    }
    else{
        $("#boxEscola").addClass("d-none");
        $("#slcGrauEscolaridade").val("0");
        $("#slcAnoSerie").val("0");
        $("#txtNomeEscola").val("");
        $("#slcUfEscola").val("0");
        limpaMunicipiosEscolaHistoricoSocial();
    }
}

function atualizaDependenciasMoradiaHistoricoSocial(){
    var selecionado = $("#tabHistoricoSocial input[name='radCostumaDormir']:checked");

    if(!selecionado.length){
        $("#boxTempoMoradia").addClass("d-none");
        $("#boxSituacaoRua").addClass("d-none");
        $("#slcTempoMoradia").val("0");
        $("#slcTempoSituacaoRua").val("0");
        $("#txtEstavaSituacaoRua").val("");
        $("input[name='radRotina']").prop("checked", false);
        $("input[name='chkMotivosRua[]']").prop("checked", false);
        return;
    }

    var exibeTempoMoradia = String(selecionado.attr("data-exibe-tempo-moradia")) === "1";
    var exibeSituacaoRua = String(selecionado.attr("data-exibe-situacao-rua")) === "1";

    if(exibeTempoMoradia){
        $("#boxTempoMoradia").removeClass("d-none");
    }
    else{
        $("#boxTempoMoradia").addClass("d-none");
        $("#slcTempoMoradia").val("0");
        $("input[name='radRotina']").prop("checked", false);
    }

    if(exibeSituacaoRua){
        $("#boxSituacaoRua").removeClass("d-none");
    }
    else{
        $("#boxSituacaoRua").addClass("d-none");
        $("#slcTempoSituacaoRua").val("0");
        $("#txtEstavaSituacaoRua").val("");
        $("input[name='chkMotivosRua[]']").prop("checked", false);
    }
}

function atualizaDependenciasAtividadeRemuneradaHistoricoSocial(){
    var atividadeRemunerada = $("#radAtividadeRemunerada1").is(":checked");

    if(atividadeRemunerada){
        $("#boxAtividadeRemunerada").removeClass("d-none");
    }
    else{
        $("#boxAtividadeRemunerada").addClass("d-none");
        $("#boxOutraAtividade").addClass("d-none");
        $("#slcTrabalhoPrincipal").val("0");
        $("#txtOutroTrabalhoPrincipal").val("");
        return;
    }

    var select = $("#slcTrabalhoPrincipal");
    var textoSelecionado = $.trim(select.find("option:selected").text());
    var selecionouOutro = textoSelecionado === "Outro" || select.val() === "Outro";

    if(selecionouOutro){
        $("#boxOutraAtividade").removeClass("d-none");
    }
    else{
        $("#boxOutraAtividade").addClass("d-none");
        $("#txtOutroTrabalhoPrincipal").val("");
    }
}

function atualizaDependenciasQualificacaoHistoricoSocial(){
    var precisaQualificacao = $("#radPrecisaQualificacao1").is(":checked");

    if(precisaQualificacao){
        $("#boxQualificacao").removeClass("d-none");
    }
    else{
        $("#boxQualificacao").addClass("d-none");
        $("#txtQualQualificacao").val("");
    }
}

function normalizaListaIdsHistoricoSocial(valor){
    var lista = [];

    if(Array.isArray(valor)){
        valor.forEach(function(item){
            var itemStr = $.trim(String(item));
            if(itemStr !== ""){
                lista.push(itemStr);
            }
        });
        return lista;
    }

    if(valor === undefined || valor === null){
        return lista;
    }

    var valorStr = $.trim(String(valor));
    if(valorStr === ""){
        return lista;
    }

    valorStr.split(",").forEach(function(item){
        var itemStr = $.trim(item);
        if(itemStr !== ""){
            lista.push(itemStr);
        }
    });

    return lista;
}

function normalizaTextoHistoricoSocial(texto){
    var valor = String(texto || "").toLowerCase();
    valor = valor.replace(/[áàãâä]/g, "a");
    valor = valor.replace(/[éèêë]/g, "e");
    valor = valor.replace(/[íìîï]/g, "i");
    valor = valor.replace(/[óòõôö]/g, "o");
    valor = valor.replace(/[úùûü]/g, "u");
    valor = valor.replace(/ç/g, "c");
    return $.trim(valor);
}

function marcaRadioBinarioHistoricoSocial(idSim, idNao, valor){
    $(idSim).prop("checked", false);
    $(idNao).prop("checked", false);

    if(String(valor) === "1"){
        $(idSim).prop("checked", true);
    }
    else if(String(valor) === "0"){
        $(idNao).prop("checked", true);
    }
}

function moedaBancoParaCampoHistoricoSocial(valor){
    if(valor === undefined || valor === null || String(valor) === ""){
        return "";
    }

    var numero = parseFloat(String(valor).replace(",", "."));
    if(isNaN(numero)){
        return String(valor);
    }

    var partes = numero.toFixed(2).split(".");
    var inteiro = partes[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    return inteiro + "," + partes[1];
}

function inicializaAbaSaudeGeral(){
    $(document)
        .off("change.saudeGeralDoencas", "#tabSaudeGeral input[name='chkPossuiDoenca[]']")
        .on("change.saudeGeralDoencas", "#tabSaudeGeral input[name='chkPossuiDoenca[]']", function(){
            atualizaSelecaoDoencasSaudeGeral($(this));
        });

    $(document)
        .off("change.saudeGeralTratamento", "#tabSaudeGeral input[name='radTratamentoMedicoAmbulatorial']")
        .on("change.saudeGeralTratamento", "#tabSaudeGeral input[name='radTratamentoMedicoAmbulatorial']", function(){
            atualizaTratamentoMedicoAmbulatorialSaudeGeral();
        });

    $(document)
        .off("click.saudeGeralSalvar", "#tabSaudeGeral #btnCadIdentificacao")
        .on("click.saudeGeralSalvar", "#tabSaudeGeral #btnCadIdentificacao", function(){
            salvaSaudeGeral();
        });

    carregaSaudeGeral();
}

function carregaSaudeGeral(){
    var entradaId = $("#hidEntrada").val();

    $.ajax({
        type: "POST",
        url: "../public/componentes/prontuario_acolhido/model/carregaSaudeGeral.php",
        dataType: "JSON",
        data: {id: entradaId},
        success: function(resultado){
            var possuiRegistro = resultado && !jQuery.isEmptyObject(resultado);
            prontuarioSaudeGeralId = possuiRegistro ? (parseInt(resultado.prontuario_saude_geral_id, 10) || 0) : 0;

            limpaFormularioSaudeGeral();

            if(possuiRegistro){
                aplicaValoresSaudeGeral(resultado);
            }

            carregaDoencasSaudeGeral(possuiRegistro ? (resultado.doencas_ids || []) : []);
            atualizaTratamentoMedicoAmbulatorialSaudeGeral();
        },
        error: function(){
            prontuarioSaudeGeralId = 0;
            limpaFormularioSaudeGeral();
            carregaDoencasSaudeGeral([]);
        }
    });
}

function aplicaValoresSaudeGeral(resultado){
    marcaRadioBinarioHistoricoSocial(
        "#radTratamentoMedicoAmbulatorial1",
        "#radTratamentoMedicoAmbulatorial2",
        resultado.realiza_tratamento_medico_ambulatorial
    );

    $("#txtOndeTratamentoMedicoAmbulatorial").val(resultado.onde_tratamento_medico_ambulatorial || "");
    $("#txtOutraDoencaSaudeGeral").val(resultado.outra_doenca_descricao || "");
}

function limpaFormularioSaudeGeral(){
    var form = $("#formSaudeGeral")[0];
    if(form){
        form.reset();
    }

    $("#txtOndeTratamentoMedicoAmbulatorial").val("");
    $("#txtOutraDoencaSaudeGeral").val("");

    $("#boxOndeTratamentoMedicoAmbulatorial").addClass("d-none");
    $("#boxOutraDoenca").hide();

    $("input[name='chkPossuiDoenca[]']").prop("checked", false);
}

function salvaSaudeGeral(){
    if(prontuarioSaudeGeralId > 0){
        editaSaudeGeral(prontuarioSaudeGeralId);
    }
    else{
        cadastraSaudeGeral();
    }
}

function cadastraSaudeGeral(){
    var entradaId = $("#hidEntrada").val();
    if(!entradaId || isNaN(parseInt(entradaId, 10)) || parseInt(entradaId, 10) <= 0){
        alert("ID de acolhimento inválido na tela de Anamnese.");
        return;
    }

    var form = $("#formSaudeGeral")[0];
    var data = new FormData(form);
    data.append("id", entradaId);

    $.ajax({
        type: "POST",
        enctype: "multipart/form-data",
        url: "../public/componentes/prontuario_acolhido/model/cadastraSaudeGeral.php",
        data: data,
        processData: false,
        cache: false,
        contentType: false,
        success: function(retorno){
            var novoId = parseInt($.trim(retorno), 10) || 0;
            if(novoId > 0){
                prontuarioSaudeGeralId = novoId;
            }
            else{
                alert("Não foi possível salvar a Saúde Geral. Retorno: " + retorno);
                return;
            }

            alert("Saúde Geral registrada");
            carregaSaudeGeral();
        },
        error: function(xhr){
            alert("Erro ao salvar Saúde Geral: " + (xhr.responseText || xhr.statusText));
        }
    });
}

function editaSaudeGeral(id){
    var entradaId = $("#hidEntrada").val();
    if(!entradaId || isNaN(parseInt(entradaId, 10)) || parseInt(entradaId, 10) <= 0){
        alert("ID de acolhimento inválido na tela de Anamnese.");
        return;
    }

    var form = $("#formSaudeGeral")[0];
    var data = new FormData(form);
    data.append("id", id);
    data.append("entrada_id", entradaId);

    $.ajax({
        type: "POST",
        enctype: "multipart/form-data",
        url: "../public/componentes/prontuario_acolhido/model/editaSaudeGeral.php",
        data: data,
        processData: false,
        cache: false,
        contentType: false,
        success: function(retorno){
            var idRetorno = parseInt($.trim(retorno), 10) || 0;
            if(idRetorno > 0){
                prontuarioSaudeGeralId = idRetorno;
            }
            else{
                alert("Não foi possível editar a Saúde Geral. Retorno: " + retorno);
                return;
            }

            alert("Alterações efetuadas");
            carregaSaudeGeral();
        },
        error: function(xhr){
            alert("Erro ao editar Saúde Geral: " + (xhr.responseText || xhr.statusText));
        }
    });
}

function carregaDoencasSaudeGeral(valoresSelecionados){
    $.ajax({
        type: "POST",
        url: "../public/componentes/prontuario_acolhido/model/carregaDoencas.php",
        dataType: "JSON",
        success: function(retorno){
            var itens = normalizaItensIdentificacao(retorno, "doenca_id", "doenca_descricao");
            var selecionados = normalizaListaIdsHistoricoSocial(valoresSelecionados);
            var box = $("#boxDoencasLista");
            box.empty();

            var colA = $("<div>").addClass("col-4");
            var colB = $("<div>").addClass("col-8");

            itens.forEach(function(item, idx){
                var idOpcao = "chkPossuiDoencaDb" + item.id + "_" + (idx + 1);
                var descricaoNormalizada = normalizaTextoHistoricoSocial(item.descricao);
                var ehOutra = descricaoNormalizada === "outra" ? 1 : 0;
                var ehNaoTenho = descricaoNormalizada === "nao tenho" ? 1 : 0;

                var divCheck = $("<div>").addClass("form-check");
                var input = $("<input>")
                    .addClass("form-check-input")
                    .attr("type", "checkbox")
                    .attr("name", "chkPossuiDoenca[]")
                    .attr("id", idOpcao)
                    .attr("data-outra", ehOutra)
                    .attr("data-nao-tenho", ehNaoTenho)
                    .val(item.id);
                var label = $("<label>")
                    .addClass("form-check-label")
                    .attr("for", idOpcao)
                    .text(item.descricao);

                if(selecionados.indexOf(String(item.id)) !== -1){
                    input.prop("checked", true);
                }

                divCheck.append(input).append(label);
                if(idx < 6){
                    colA.append(divCheck);
                }
                else{
                    colB.append(divCheck);
                }
            });

            box.append(colA).append(colB);
            atualizaSelecaoDoencasSaudeGeral();
        },
        error: function(){
            $("#boxDoencasLista").html("");
            atualizaSelecaoDoencasSaudeGeral();
        }
    });
}

function atualizaSelecaoDoencasSaudeGeral(inputAlterado){
    var todos = $("#tabSaudeGeral input[name='chkPossuiDoenca[]']");
    var naoTenho = todos.filter("[data-nao-tenho='1']");
    var outra = todos.filter("[data-outra='1']");

    if(inputAlterado && inputAlterado.length && inputAlterado.is(":checked")){
        if(String(inputAlterado.attr("data-nao-tenho")) === "1"){
            todos.not(inputAlterado).prop("checked", false);
        }
        else{
            naoTenho.prop("checked", false);
        }
    }
    else if(naoTenho.is(":checked")){
        todos.not(naoTenho).prop("checked", false);
    }

    if(outra.is(":checked")){
        $("#boxOutraDoenca").show();
    }
    else{
        $("#boxOutraDoenca").hide();
        $("#txtOutraDoencaSaudeGeral").val("");
    }
}

function atualizaTratamentoMedicoAmbulatorialSaudeGeral(){
    var realizaTratamento = $("#radTratamentoMedicoAmbulatorial1").is(":checked");
    if(realizaTratamento){
        $("#boxOndeTratamentoMedicoAmbulatorial").removeClass("d-none");
    }
    else{
        $("#boxOndeTratamentoMedicoAmbulatorial").addClass("d-none");
        $("#txtOndeTratamentoMedicoAmbulatorial").val("");
    }
}

function inicializaAbaAvaliacaoPsicossocial(){
    $(document)
        .off("change.avaliacaoEspecificidades", "#tabAvaliacaoPsicossocial input[name='chkEspecificidades[]']")
        .on("change.avaliacaoEspecificidades", "#tabAvaliacaoPsicossocial input[name='chkEspecificidades[]']", function(){
            atualizaSelecaoEspecificidadesAvaliacaoPsicossocial($(this));
        });

    $(document)
        .off("change.avaliacaoAcompanhamento", "#tabAvaliacaoPsicossocial input[name='radAcompanhamento']")
        .on("change.avaliacaoAcompanhamento", "#tabAvaliacaoPsicossocial input[name='radAcompanhamento']", function(){
            atualizaCampoOndeAcompanhamentoAvaliacaoPsicossocial();
        });

    $(document)
        .off("click.avaliacaoSalvar", "#tabAvaliacaoPsicossocial #btnCadIdentificacao")
        .on("click.avaliacaoSalvar", "#tabAvaliacaoPsicossocial #btnCadIdentificacao", function(){
            salvaAvaliacaoPsicossocial();
        });

    carregaAvaliacaoPsicossocial();
}

function carregaAvaliacaoPsicossocial(){
    var entradaId = $("#hidEntrada").val();

    $.ajax({
        type: "POST",
        url: "../public/componentes/prontuario_acolhido/model/carregaAvaliacaoPsicossocial.php",
        dataType: "JSON",
        data: {id: entradaId},
        success: function(resultado){
            var possuiRegistro = resultado && !jQuery.isEmptyObject(resultado);
            prontuarioAvaliacaoPsicossocialId = possuiRegistro ? (
                parseInt(resultado.prontuario_avaliacao_psicossocial_id, 10) || 0
            ) : 0;

            limpaFormularioAvaliacaoPsicossocial();

            if(possuiRegistro){
                aplicaValoresAvaliacaoPsicossocial(resultado);
            }

            carregaEspecificidadesAvaliacaoPsicossocial(possuiRegistro ? (resultado.especificidades_ids || []) : []);
            carregaTiposAcompanhamentoAvaliacaoPsicossocial(
                possuiRegistro ? (resultado.tipo_acompanhamento_id || 0) : 0
            );
        },
        error: function(){
            prontuarioAvaliacaoPsicossocialId = 0;
            limpaFormularioAvaliacaoPsicossocial();
            carregaEspecificidadesAvaliacaoPsicossocial([]);
            carregaTiposAcompanhamentoAvaliacaoPsicossocial(0);
        }
    });
}

function aplicaValoresAvaliacaoPsicossocial(resultado){
    $("#txtOutroTranstornoPsicossocial").val(resultado.outro_transtorno || "");
    $("#txtOndeAcompanhamento").val(resultado.onde_acompanhamento || "");
}

function limpaFormularioAvaliacaoPsicossocial(){
    var form = $("#formAvaliacaoPsicossocial")[0];
    if(form){
        form.reset();
    }

    $("#txtOutroTranstornoPsicossocial").val("");
    $("#txtOndeAcompanhamento").val("");

    $("#boxOutroTranstorno").hide();
    $("#boxOutroTranstorno").addClass("d-none");
    $("#boxOndeAcompanhamento").addClass("d-none");

    $("input[name='chkEspecificidades[]']").prop("checked", false);
    $("input[name='radAcompanhamento']").prop("checked", false);
}

function salvaAvaliacaoPsicossocial(){
    if(prontuarioAvaliacaoPsicossocialId > 0){
        editaAvaliacaoPsicossocial(prontuarioAvaliacaoPsicossocialId);
    }
    else{
        cadastraAvaliacaoPsicossocial();
    }
}

function cadastraAvaliacaoPsicossocial(){
    var entradaId = $("#hidEntrada").val();
    if(!entradaId || isNaN(parseInt(entradaId, 10)) || parseInt(entradaId, 10) <= 0){
        alert("ID de acolhimento inválido na tela de Anamnese.");
        return;
    }

    var form = $("#formAvaliacaoPsicossocial")[0];
    var data = new FormData(form);
    data.append("id", entradaId);

    $.ajax({
        type: "POST",
        enctype: "multipart/form-data",
        url: "../public/componentes/prontuario_acolhido/model/cadastraAvaliacaoPsicossocial.php",
        data: data,
        processData: false,
        cache: false,
        contentType: false,
        success: function(retorno){
            var novoId = parseInt($.trim(retorno), 10) || 0;
            if(novoId > 0){
                prontuarioAvaliacaoPsicossocialId = novoId;
            }
            else{
                alert("Não foi possível salvar a Avaliação Psicossocial. Retorno: " + retorno);
                return;
            }

            alert("Avaliação Psicossocial registrada");
            carregaAvaliacaoPsicossocial();
        },
        error: function(xhr){
            alert("Erro ao salvar Avaliação Psicossocial: " + (xhr.responseText || xhr.statusText));
        }
    });
}

function editaAvaliacaoPsicossocial(id){
    var entradaId = $("#hidEntrada").val();
    if(!entradaId || isNaN(parseInt(entradaId, 10)) || parseInt(entradaId, 10) <= 0){
        alert("ID de acolhimento inválido na tela de Anamnese.");
        return;
    }

    var form = $("#formAvaliacaoPsicossocial")[0];
    var data = new FormData(form);
    data.append("id", id);
    data.append("entrada_id", entradaId);

    $.ajax({
        type: "POST",
        enctype: "multipart/form-data",
        url: "../public/componentes/prontuario_acolhido/model/editaAvaliacaoPsicossocial.php",
        data: data,
        processData: false,
        cache: false,
        contentType: false,
        success: function(retorno){
            var idRetorno = parseInt($.trim(retorno), 10) || 0;
            if(idRetorno > 0){
                prontuarioAvaliacaoPsicossocialId = idRetorno;
            }
            else{
                alert("Não foi possível editar a Avaliação Psicossocial. Retorno: " + retorno);
                return;
            }

            alert("Alterações efetuadas");
            carregaAvaliacaoPsicossocial();
        },
        error: function(xhr){
            alert("Erro ao editar Avaliação Psicossocial: " + (xhr.responseText || xhr.statusText));
        }
    });
}

function carregaEspecificidadesAvaliacaoPsicossocial(valoresSelecionados){
    $.ajax({
        type: "POST",
        url: "../public/componentes/prontuario_acolhido/model/carregaEspecificidades.php",
        dataType: "JSON",
        success: function(retorno){
            var itens = normalizaItensIdentificacao(retorno, "especificidade_id", "especificidade_descricao");
            var selecionados = normalizaListaIdsHistoricoSocial(valoresSelecionados);
            var box = $("#boxEspecificidadesLista");
            box.empty();

            var colA = $("<div>").addClass("col-5");
            var colB = $("<div>").addClass("col-6");

            itens.forEach(function(item, idx){
                var idOpcao = "chkEspecificidadesDb" + item.id + "_" + (idx + 1);
                var descricaoNormalizada = normalizaTextoHistoricoSocial(item.descricao);
                var idItem = parseInt(item.id, 10) || 0;
                var ehOutra = (idItem === 13 || descricaoNormalizada === "outra") ? 1 : 0;
                var ehNaoSeAplica = (idItem === 14 || descricaoNormalizada === "nao se aplica") ? 1 : 0;

                var divCheck = $("<div>").addClass("form-check");
                var input = $("<input>")
                    .addClass("form-check-input")
                    .attr("type", "checkbox")
                    .attr("name", "chkEspecificidades[]")
                    .attr("id", idOpcao)
                    .attr("data-outra", ehOutra)
                    .attr("data-nao-se-aplica", ehNaoSeAplica)
                    .val(item.id);
                var label = $("<label>")
                    .addClass("form-check-label")
                    .attr("for", idOpcao)
                    .text(item.descricao);

                if(selecionados.indexOf(String(item.id)) !== -1){
                    input.prop("checked", true);
                }

                divCheck.append(input).append(label);
                if(idx % 2 === 0){
                    colA.append(divCheck);
                }
                else{
                    colB.append(divCheck);
                }
            });

            box.append(colA).append(colB);
            atualizaSelecaoEspecificidadesAvaliacaoPsicossocial();
        },
        error: function(){
            $("#boxEspecificidadesLista").html("");
            atualizaSelecaoEspecificidadesAvaliacaoPsicossocial();
        }
    });
}

function carregaTiposAcompanhamentoAvaliacaoPsicossocial(valorSelecionado){
    $.ajax({
        type: "POST",
        url: "../public/componentes/prontuario_acolhido/model/carregaTiposAcompanhamento.php",
        dataType: "JSON",
        success: function(retorno){
            var itens = normalizaItensIdentificacao(retorno, "tipo_acompanhamento_id", "tipo_acompanhamento_descricao");
            var box = $("#boxTiposAcompanhamentoLista");
            box.empty();

            itens.forEach(function(item, idx){
                var idOpcao = "radAcompanhamentoDb" + item.id + "_" + (idx + 1);
                var descricaoNormalizada = normalizaTextoHistoricoSocial(item.descricao);
                var idItem = parseInt(item.id, 10) || 0;
                var exigeOnde = (
                    idItem === 2 ||
                    idItem === 3 ||
                    descricaoNormalizada === "continua em acompanhamento" ||
                    descricaoNormalizada === "atualmente nao, mas ja fez acompanhamento"
                ) ? 1 : 0;

                var divCheck = $("<div>").addClass("form-check form-check");
                var input = $("<input>")
                    .addClass("form-check-input")
                    .attr("type", "radio")
                    .attr("name", "radAcompanhamento")
                    .attr("id", idOpcao)
                    .attr("data-exige-onde", exigeOnde)
                    .val(item.id);
                var label = $("<label>")
                    .addClass("form-check-label")
                    .attr("for", idOpcao)
                    .text(item.descricao);

                if(valorSelecionado && String(valorSelecionado) === String(item.id)){
                    input.prop("checked", true);
                }

                divCheck.append(input).append(label);
                box.append(divCheck);
            });

            atualizaCampoOndeAcompanhamentoAvaliacaoPsicossocial();
        },
        error: function(){
            $("#boxTiposAcompanhamentoLista").html("");
            atualizaCampoOndeAcompanhamentoAvaliacaoPsicossocial();
        }
    });
}

function atualizaSelecaoEspecificidadesAvaliacaoPsicossocial(inputAlterado){
    var todos = $("#tabAvaliacaoPsicossocial input[name='chkEspecificidades[]']");
    var naoSeAplica = todos.filter("[data-nao-se-aplica='1']");
    var outra = todos.filter("[data-outra='1']");

    if(inputAlterado && inputAlterado.length && inputAlterado.is(":checked")){
        if(String(inputAlterado.attr("data-nao-se-aplica")) === "1"){
            todos.not(inputAlterado).prop("checked", false);
        }
        else{
            naoSeAplica.prop("checked", false);
        }
    }
    else if(naoSeAplica.is(":checked")){
        todos.not(naoSeAplica).prop("checked", false);
    }

    if(outra.is(":checked")){
        $("#boxOutroTranstorno").removeClass("d-none").show();
    }
    else{
        $("#boxOutroTranstorno").hide();
        $("#boxOutroTranstorno").addClass("d-none");
        $("#txtOutroTranstornoPsicossocial").val("");
    }
}

function atualizaCampoOndeAcompanhamentoAvaliacaoPsicossocial(){
    var selecionado = $("#tabAvaliacaoPsicossocial input[name='radAcompanhamento']:checked");
    if(!selecionado.length){
        $("#boxOndeAcompanhamento").addClass("d-none");
        $("#txtOndeAcompanhamento").val("");
        return;
    }

    var exigeOnde = String(selecionado.attr("data-exige-onde")) === "1";
    if(exigeOnde){
        $("#boxOndeAcompanhamento").removeClass("d-none");
    }
    else{
        $("#boxOndeAcompanhamento").addClass("d-none");
        $("#txtOndeAcompanhamento").val("");
    }
}

function inicializaAbaSobreUso(){
    $(document)
        .off("click.sobreUsoSalvar", "#tabSobreUso #btnCadIdentificacao")
        .on("click.sobreUsoSalvar", "#tabSobreUso #btnCadIdentificacao", function(){
            salvaSobreUso();
        });

    $(document)
        .off("click.sobreUsoAddSubstancia", "#tabSobreUso #btnAddSubstancias")
        .on("click.sobreUsoAddSubstancia", "#tabSobreUso #btnAddSubstancias", function(){
            adicionaItemSubstanciaSobreUso();
        });

    $(document)
        .off("click.sobreUsoAddRanking", "#tabSobreUso #btnAddRanking")
        .on("click.sobreUsoAddRanking", "#tabSobreUso #btnAddRanking", function(){
            if($(this).closest("#boxRelacaoAjuda").length){
                adicionaItemAjudaEmergenciaSobreUso();
            }
            else{
                adicionaItemRankingSobreUso();
            }
        });

    $(document)
        .off("click.sobreUsoRemoveSubstancia", "#tabSobreUso .btn-remove-substancia-sobre-uso")
        .on("click.sobreUsoRemoveSubstancia", "#tabSobreUso .btn-remove-substancia-sobre-uso", function(){
            var idx = parseInt($(this).attr("data-idx"), 10);
            if(!isNaN(idx) && idx >= 0 && idx < sobreUsoListaSubstancias.length){
                sobreUsoListaSubstancias.splice(idx, 1);
                renderListaSubstanciasSobreUso();
            }
        });

    $(document)
        .off("click.sobreUsoRemoveRanking", "#tabSobreUso .btn-remove-ranking-sobre-uso")
        .on("click.sobreUsoRemoveRanking", "#tabSobreUso .btn-remove-ranking-sobre-uso", function(){
            var idx = parseInt($(this).attr("data-idx"), 10);
            if(!isNaN(idx) && idx >= 0 && idx < sobreUsoListaRanking.length){
                sobreUsoListaRanking.splice(idx, 1);
                renderListaRankingSobreUso();
            }
        });

    $(document)
        .off("click.sobreUsoRemoveAjuda", "#tabSobreUso .btn-remove-ajuda-sobre-uso")
        .on("click.sobreUsoRemoveAjuda", "#tabSobreUso .btn-remove-ajuda-sobre-uso", function(){
            var idx = parseInt($(this).attr("data-idx"), 10);
            if(!isNaN(idx) && idx >= 0 && idx < sobreUsoListaAjudaEmergencia.length){
                sobreUsoListaAjudaEmergencia.splice(idx, 1);
                renderListaAjudaEmergenciaSobreUso();
            }
        });

    $(document)
        .off("change.sobreUsoDependencias", "#tabSobreUso select, #tabSobreUso input[type='checkbox']")
        .on("change.sobreUsoDependencias", "#tabSobreUso select, #tabSobreUso input[type='checkbox']", function(){
            atualizaDependenciasSobreUso();
        });

    carregaSobreUso();
}

function carregaSobreUso(){
    var entradaId = $("#hidEntrada").val();

    $.ajax({
        type: "POST",
        url: "../public/componentes/prontuario_acolhido/model/carregaSobreUso.php",
        dataType: "JSON",
        data: {id: entradaId},
        success: function(resultado){
            var possuiRegistro = resultado && !jQuery.isEmptyObject(resultado);
            prontuarioSobreUsoId = possuiRegistro ? (parseInt(resultado.prontuario_sobre_uso_id, 10) || 0) : 0;

            limpaFormularioSobreUso();

            if(possuiRegistro){
                var dadosCarregados = resultado.dados;
                if(typeof dadosCarregados === "string"){
                    try{
                        dadosCarregados = JSON.parse(dadosCarregados);
                    }
                    catch(e){
                        dadosCarregados = {};
                    }
                }

                if(dadosCarregados && typeof dadosCarregados === "object"){
                    aplicaDadosSobreUso(dadosCarregados);
                }
                else{
                    atualizaDependenciasSobreUso();
                }
            }
            else{
                atualizaDependenciasSobreUso();
            }
        },
        error: function(){
            prontuarioSobreUsoId = 0;
            limpaFormularioSobreUso();
            atualizaDependenciasSobreUso();
        }
    });
}

function salvaSobreUso(){
    if(prontuarioSobreUsoId > 0){
        editaSobreUso(prontuarioSobreUsoId);
    }
    else{
        cadastraSobreUso();
    }
}

function cadastraSobreUso(){
    var entradaId = $("#hidEntrada").val();
    if(!entradaId || isNaN(parseInt(entradaId, 10)) || parseInt(entradaId, 10) <= 0){
        alert("ID de acolhimento inválido na tela de Anamnese.");
        return;
    }

    var data = new FormData();
    data.append("id", entradaId);
    data.append("dados_json", JSON.stringify(coletaDadosSobreUso()));

    $.ajax({
        type: "POST",
        enctype: "multipart/form-data",
        url: "../public/componentes/prontuario_acolhido/model/cadastraSobreUso.php",
        data: data,
        processData: false,
        cache: false,
        contentType: false,
        success: function(retorno){
            var novoId = parseInt($.trim(retorno), 10) || 0;
            if(novoId > 0){
                prontuarioSobreUsoId = novoId;
            }
            else{
                alert("Não foi possível salvar os Dados Sobre o Uso. Retorno: " + retorno);
                return;
            }

            alert("Dados Sobre o Uso registrados");
            carregaSobreUso();
        },
        error: function(xhr){
            alert("Erro ao salvar Dados Sobre o Uso: " + (xhr.responseText || xhr.statusText));
        }
    });
}

function editaSobreUso(id){
    var entradaId = $("#hidEntrada").val();
    if(!entradaId || isNaN(parseInt(entradaId, 10)) || parseInt(entradaId, 10) <= 0){
        alert("ID de acolhimento inválido na tela de Anamnese.");
        return;
    }

    var data = new FormData();
    data.append("id", id);
    data.append("entrada_id", entradaId);
    data.append("dados_json", JSON.stringify(coletaDadosSobreUso()));

    $.ajax({
        type: "POST",
        enctype: "multipart/form-data",
        url: "../public/componentes/prontuario_acolhido/model/editaSobreUso.php",
        data: data,
        processData: false,
        cache: false,
        contentType: false,
        success: function(retorno){
            var idRetorno = parseInt($.trim(retorno), 10) || 0;
            if(idRetorno > 0){
                prontuarioSobreUsoId = idRetorno;
            }
            else{
                alert("Não foi possível editar os Dados Sobre o Uso. Retorno: " + retorno);
                return;
            }

            alert("Alterações efetuadas");
            carregaSobreUso();
        },
        error: function(xhr){
            alert("Erro ao editar Dados Sobre o Uso: " + (xhr.responseText || xhr.statusText));
        }
    });
}

function coletaDadosSobreUso(){
    var dados = {
        campos: {},
        historico_familiar_lista: [],
        checkboxes: {
            familiar_drogas: obterValoresMarcadosSobreUso("chkFamiliarDrogas[]"),
            tratamento_dependencia: obterValoresMarcadosSobreUso("chkTratamentoDependencia[]"),
            consequencias: obterValoresMarcadosSobreUso("chkConsequencias[]")
        },
        radios: {
            rompimento_vinculos: $("#tabSobreUso input[name='radRompimentoVinculos']:checked").val() || ""
        },
        listas: {
            substancias: sobreUsoListaSubstancias,
            ranking: sobreUsoListaRanking,
            ajuda_emergencia: sobreUsoListaAjudaEmergencia
        }
    };

    sobreUsoCamposBaseIds().forEach(function(id){
        dados.campos[id] = $("#" + id).val() || "";
    });

    $("#tabSobreUso [name='slcHistoricoFamiliar']").each(function(){
        dados.historico_familiar_lista.push($(this).val() || "");
    });

    return dados;
}

function aplicaDadosSobreUso(dados){
    var campos = (dados && dados.campos && typeof dados.campos === "object") ? dados.campos : {};
    var checkboxes = (dados && dados.checkboxes && typeof dados.checkboxes === "object") ? dados.checkboxes : {};
    var radios = (dados && dados.radios && typeof dados.radios === "object") ? dados.radios : {};
    var listas = (dados && dados.listas && typeof dados.listas === "object") ? dados.listas : {};

    sobreUsoCamposBaseIds().forEach(function(id){
        if(campos.hasOwnProperty(id)){
            $("#" + id).val(campos[id] || "");
        }
    });

    if(Array.isArray(dados.historico_familiar_lista)){
        var selectsHistorico = $("#tabSobreUso [name='slcHistoricoFamiliar']");
        selectsHistorico.each(function(idx){
            $(this).val(dados.historico_familiar_lista[idx] || "");
        });
    }

    aplicaMarcacoesCheckboxSobreUso("chkFamiliarDrogas[]", checkboxes.familiar_drogas || []);
    aplicaMarcacoesCheckboxSobreUso("chkTratamentoDependencia[]", checkboxes.tratamento_dependencia || []);
    aplicaMarcacoesCheckboxSobreUso("chkConsequencias[]", checkboxes.consequencias || []);

    $("input[name='radRompimentoVinculos']").prop("checked", false);
    if(radios.rompimento_vinculos){
        $("input[name='radRompimentoVinculos']").each(function(){
            if($(this).val() === radios.rompimento_vinculos){
                $(this).prop("checked", true);
            }
        });
    }

    sobreUsoListaSubstancias = normalizaListaObjetosSobreUso(listas.substancias);
    sobreUsoListaRanking = normalizaListaObjetosSobreUso(listas.ranking);
    sobreUsoListaAjudaEmergencia = normalizaListaObjetosSobreUso(listas.ajuda_emergencia);

    renderListaSubstanciasSobreUso();
    renderListaRankingSobreUso();
    renderListaAjudaEmergenciaSobreUso();
    atualizaDependenciasSobreUso();
}

function limpaFormularioSobreUso(){
    var form = $("#formSobreUso")[0];
    if(form){
        form.reset();
    }

    sobreUsoListaSubstancias = [];
    sobreUsoListaRanking = [];
    sobreUsoListaAjudaEmergencia = [];

    renderListaSubstanciasSobreUso();
    renderListaRankingSobreUso();
    renderListaAjudaEmergenciaSobreUso();

    $("#tabSobreUso [name='slcHistoricoFamiliar']").val("");
    $("input[name='chkFamiliarDrogas[]']").prop("checked", false);
    $("input[name='chkTratamentoDependencia[]']").prop("checked", false);
    $("input[name='chkConsequencias[]']").prop("checked", false);
    $("input[name='radRompimentoVinculos']").prop("checked", false);

    atualizaDependenciasSobreUso();
}

function adicionaItemSubstanciaSobreUso(){
    var droga = $("#slcDrogas").val() || "";
    var idadeInicio = $.trim($("#txtIdadeInicio").val() || "");
    var ultimoUso = $.trim($("#txtUltimoUso").val() || "");
    var continuaUso = $("#slcContinuaUso").val() || "";
    var outraSubstancia = $.trim($("#txtOutraSubstancia").val() || "");

    if(droga === "" || idadeInicio === "" || ultimoUso === "" || continuaUso === ""){
        alert("Preencha todos os campos para adicionar a substância.");
        return;
    }

    if(droga === "Outro(a)" && outraSubstancia === ""){
        alert("Informe qual outra substância.");
        return;
    }

    sobreUsoListaSubstancias.push({
        droga: droga,
        outra_substancia: outraSubstancia,
        idade_inicio: idadeInicio,
        ultimo_uso: ultimoUso,
        continua_uso: continuaUso
    });

    $("#slcDrogas").val("");
    $("#txtIdadeInicio").val("");
    $("#txtUltimoUso").val("");
    $("#slcContinuaUso").val("");
    $("#txtOutraSubstancia").val("");
    $("#boxOutraSubstancia").addClass("d-none");

    renderListaSubstanciasSobreUso();
}

function adicionaItemRankingSobreUso(){
    var substancia = $("#slcTrajetoria").val() || "";
    var ranking = $("#slcRankeamento").val() || "";

    if(substancia === "" || ranking === ""){
        alert("Preencha substância e ranking para adicionar.");
        return;
    }

    var rankingJaUsado = false;
    sobreUsoListaRanking.forEach(function(item){
        if(String(item.ranking) === String(ranking)){
            rankingJaUsado = true;
        }
    });

    if(rankingJaUsado){
        alert("Esse ranking já foi utilizado.");
        return;
    }

    sobreUsoListaRanking.push({
        substancia: substancia,
        ranking: ranking
    });

    $("#slcTrajetoria").val("");
    $("#slcRankeamento").val("");
    renderListaRankingSobreUso();
}

function adicionaItemAjudaEmergenciaSobreUso(){
    var periodo = $.trim($("#txtPeriodoEmergencia").val() || "");
    var local = $.trim($("#txtLocalEmergencia").val() || "");
    var abstinencia = $("#slcAbstinencia").val() || "";
    var motivoSaida = $.trim($("#txtMotivoSaida").val() || "");

    if(periodo === "" || local === "" || abstinencia === "" || motivoSaida === ""){
        alert("Preencha todos os campos das informações sobre serviços de emergência.");
        return;
    }

    sobreUsoListaAjudaEmergencia.push({
        periodo: periodo,
        local: local,
        abstinencia: abstinencia,
        motivo_saida: motivoSaida
    });

    $("#txtPeriodoEmergencia").val("");
    $("#txtLocalEmergencia").val("");
    $("#slcAbstinencia").val("");
    $("#txtMotivoSaida").val("");
    renderListaAjudaEmergenciaSobreUso();
}

function renderListaSubstanciasSobreUso(){
    var box = $("#listaSubstanciasProblema");
    box.empty();

    sobreUsoListaSubstancias.forEach(function(item, idx){
        var substanciaExibicao = item.droga === "Outro(a)" && item.outra_substancia ? item.outra_substancia : item.droga;
        var texto = (idx + 1) + ". " + substanciaExibicao + " | Idade início: " + (item.idade_inicio || "") + " | Último uso: " + (item.ultimo_uso || "") + " | Continua uso: " + (item.continua_uso || "");

        var row = $("<div>").addClass("col-12 d-flex align-items-center mt-1");
        var span = $("<span>").addClass("small").text(texto);
        var btn = $("<button>")
            .attr("type", "button")
            .attr("data-idx", idx)
            .addClass("btn btn-sm btn-outline-danger ms-2 btn-remove-substancia-sobre-uso")
            .html("<i class='bi bi-trash'></i>");

        row.append(span).append(btn);
        box.append(row);
    });
}

function renderListaRankingSobreUso(){
    var box = $("#bosListaRanking");
    box.empty();

    sobreUsoListaRanking.forEach(function(item, idx){
        var texto = (idx + 1) + ". " + (item.ranking || "") + "º - " + (item.substancia || "");

        var row = $("<div>").addClass("col-12 d-flex align-items-center mt-1");
        var span = $("<span>").addClass("small").text(texto);
        var btn = $("<button>")
            .attr("type", "button")
            .attr("data-idx", idx)
            .addClass("btn btn-sm btn-outline-danger ms-2 btn-remove-ranking-sobre-uso")
            .html("<i class='bi bi-trash'></i>");

        row.append(span).append(btn);
        box.append(row);
    });
}

function renderListaAjudaEmergenciaSobreUso(){
    var box = $("#boxListaAjuda");
    box.empty();

    sobreUsoListaAjudaEmergencia.forEach(function(item, idx){
        var texto = (idx + 1) + ". Período: " + (item.periodo || "") + " | Local: " + (item.local || "") + " | Abstinência: " + (item.abstinencia || "") + " | Motivo saída: " + (item.motivo_saida || "");

        var row = $("<div>").addClass("col-12 d-flex align-items-center mt-1");
        var span = $("<span>").addClass("small").text(texto);
        var btn = $("<button>")
            .attr("type", "button")
            .attr("data-idx", idx)
            .addClass("btn btn-sm btn-outline-danger ms-2 btn-remove-ajuda-sobre-uso")
            .html("<i class='bi bi-trash'></i>");

        row.append(span).append(btn);
        box.append(row);
    });
}

function obterValoresMarcadosSobreUso(nomeCampo){
    var valores = [];
    $("#tabSobreUso input[name='" + nomeCampo + "']:checked").each(function(){
        valores.push($(this).val());
    });
    return valores;
}

function aplicaMarcacoesCheckboxSobreUso(nomeCampo, valores){
    var listaValores = Array.isArray(valores) ? valores : [];
    $("#tabSobreUso input[name='" + nomeCampo + "']").each(function(){
        $(this).prop("checked", listaValores.indexOf($(this).val()) !== -1);
    });
}

function normalizaListaObjetosSobreUso(lista){
    if(!Array.isArray(lista)){
        return [];
    }

    return lista.filter(function(item){
        return item && typeof item === "object";
    });
}

function toggleClasseDNoneSobreUso(selector, exibir){
    if(exibir){
        $(selector).removeClass("d-none");
    }
    else{
        $(selector).addClass("d-none");
    }
}

function atualizaDependenciasSobreUso(){
    var exibeOutraSubstancia = ($("#slcDrogas").val() === "Outro(a)");
    toggleClasseDNoneSobreUso("#boxOutraSubstancia", exibeOutraSubstancia);
    if(!exibeOutraSubstancia){
        $("#txtOutraSubstancia").val("");
    }

    var valorHistoricoFamilia = $("#tabSobreUso [name='slcHistoricoFamiliar']").first().val();
    toggleClasseDNoneSobreUso("#boxHistoricoFamilia", valorHistoricoFamilia === "Sim");
    if(valorHistoricoFamilia !== "Sim"){
        $("input[name='chkFamiliarDrogas[]']").prop("checked", false);
        $("#txtOutroFamilia").val("");
        $("#slcPresenciouFamiliar").val("");
    }

    var exibeOutroMembroFamilia = $("input[name='chkFamiliarDrogas[]'][value='Outros']").is(":checked");
    if(exibeOutroMembroFamilia){
        $("#boxOutroMembroFamilia").show();
    }
    else{
        $("#boxOutroMembroFamilia").hide();
        $("#txtOutroFamilia").val("");
    }

    var exibeOutroOfertou = ($("#slcQuemHistoricoFamiliar").val() === "Outros");
    toggleClasseDNoneSobreUso("#boxOutroOfertou", exibeOutroOfertou);
    if(!exibeOutroOfertou){
        $("#txtOutroOfertou").val("");
    }

    var exibeOutroExperiencia = ($("#slcExperiencia").val() === "Outros");
    toggleClasseDNoneSobreUso("#boxOutroOfertouExperiencia", exibeOutroExperiencia);
    if(!exibeOutroExperiencia){
        $("#txtOutroOfertouExperiencia").val("");
    }

    var exibeIdadeIniciou = ($("#slcIdadeIniciou").val() === "Outra");
    toggleClasseDNoneSobreUso("#boxIdadeIniciou", exibeIdadeIniciou);
    if(!exibeIdadeIniciou){
        $("#txtDataMedicacao").val("");
    }

    var exibeTraumasIndividuais = ($("#slcAcontecimentos").val() === "Traumas individuais");
    toggleClasseDNoneSobreUso("#boxTraumasIndividuais", exibeTraumasIndividuais);
    if(!exibeTraumasIndividuais){
        $("#slcAcontecimentosTrauma").val("");
        $("#txtOutroTraumaAcontecimentos").val("");
    }

    var exibeOutroTrauma = ($("#slcAcontecimentosTrauma").val() === "Outros");
    toggleClasseDNoneSobreUso("#boxOutroTrauma", exibeOutroTrauma);
    if(!exibeOutroTrauma){
        $("#txtOutroTraumaAcontecimentos").val("");
    }

    var exibeRelacaoTrauma = ($("#slcRelacaoTraumas").val() === "Sim");
    toggleClasseDNoneSobreUso("#boxRelacao", exibeRelacaoTrauma);
    if(!exibeRelacaoTrauma){
        $("#slcQualRelacaoTrauma").val("");
    }

    var exibeTipoAjuda = ($("#slcBuscouAjuda").val() === "Sim");
    toggleClasseDNoneSobreUso("#boxTipoAjuda", exibeTipoAjuda);
    if(!exibeTipoAjuda){
        $("#slcResultadoAjuda").val("");
    }

    var exibeFrequentouCenas = ($("#slcFrequentouCenasAbertas").val() === "Sim");
    toggleClasseDNoneSobreUso("#boxFrequentouCenas", exibeFrequentouCenas);
    if(!exibeFrequentouCenas){
        $("#txtQuandoFrequentouCenasAbertas").val("");
        $("#txtQuantoTempo").val("");
        $("#txtCenasLocalizacao").val("");
    }

    var exibeRelacaoAjuda = ($("#slcServicosEmergencia").val() === "Sim");
    toggleClasseDNoneSobreUso("#boxRelacaoAjuda", exibeRelacaoAjuda);
    if(!exibeRelacaoAjuda){
        sobreUsoListaAjudaEmergencia = [];
        renderListaAjudaEmergenciaSobreUso();
        $("#txtPeriodoEmergencia").val("");
        $("#txtLocalEmergencia").val("");
        $("#slcAbstinencia").val("");
        $("#txtMotivoSaida").val("");
    }

    var exibeTipoTratamento = ($("#slcFezTratamento").val() === "Sim");
    toggleClasseDNoneSobreUso("#boxTipoTratamento", exibeTipoTratamento);
    if(!exibeTipoTratamento){
        $("input[name='chkTratamentoDependencia[]']").prop("checked", false);
        $("#txtVezesTratamento").val("");
        $("#txtTempoTratamento").val("");
    }

    var exibeInternacao = ($("#slcInternacaoCompulsoria").val() === "Sim");
    toggleClasseDNoneSobreUso("#boxInternacao", exibeInternacao);
    if(!exibeInternacao){
        $("#txtVezesInternacaoCompulsoria").val("");
        $("#txtLocalInternacaoCompulsoria").val("");
    }

    var exibeQtdRecaida = ($("#slcRecaidaUsoDrogas").val() === "Sim");
    toggleClasseDNoneSobreUso("#boxQtdRecaidaUsoDrogas", exibeQtdRecaida);
    if(!exibeQtdRecaida){
        $("#txtQtdRecaidaUsoDrogas").val("");
    }

    var exibeTraumaRecaida = ($("#slcRecaida").val() === "Traumas individuais");
    toggleClasseDNoneSobreUso("#boxTraumaRecaida", exibeTraumaRecaida);
    if(!exibeTraumaRecaida){
        $("#slcTraumaRecaida").val("");
        $("#txtOutroTraumaRecaida").val("");
    }

    var exibeOutroTraumaRecaida = ($("#slcTraumaRecaida").val() === "Outros");
    toggleClasseDNoneSobreUso("#boxOutroTraumaRecaida", exibeOutroTraumaRecaida);
    if(!exibeOutroTraumaRecaida){
        $("#txtOutroTraumaRecaida").val("");
    }

    var exibeQtdInternacaoDesintoxicacao = ($("#slcInternacaoDesintoxicacao").val() === "Sim");
    toggleClasseDNoneSobreUso("#boxQtdInternacaoDesintoxicacao", exibeQtdInternacaoDesintoxicacao);
    if(!exibeQtdInternacaoDesintoxicacao){
        $("#txtQtdInternacaoDesintoxicacao").val("");
    }

    var exibeRompimentoVinculos = $("input[name='chkConsequencias[]'][value='Rompimento dos vínculos familiares']").is(":checked");
    if(exibeRompimentoVinculos){
        $("#boxRompimentoVinculos").show();
    }
    else{
        $("#boxRompimentoVinculos").hide();
        $("input[name='radRompimentoVinculos']").prop("checked", false);
        $("#txtInformacoesCompartilhadas").val("");
    }
}

function sobreUsoCamposBaseIds(){
    return [
        "slcDrogas",
        "txtIdadeInicio",
        "txtUltimoUso",
        "slcContinuaUso",
        "txtOutraSubstancia",
        "slcPresenciouFamiliar",
        "txtOutroFamilia",
        "slcPrimeiraExperimentacao",
        "slcPrimeiraDroga",
        "slcQuemHistoricoFamiliar",
        "txtOutroOfertou",
        "slcExperiencia",
        "txtOutroOfertouExperiencia",
        "slcTrajetoria",
        "slcRankeamento",
        "slcLocalExperimentacao",
        "slcIdadeIniciou",
        "txtDataMedicacao",
        "slcAcontecimentos",
        "slcAcontecimentosTrauma",
        "txtOutroTraumaAcontecimentos",
        "slcRelacaoTraumas",
        "slcQualRelacaoTrauma",
        "slcBuscouAjuda",
        "slcResultadoAjuda",
        "slcFrequentouCenasAbertas",
        "txtQuandoFrequentouCenasAbertas",
        "txtQuantoTempo",
        "txtCenasLocalizacao",
        "slcServicosEmergencia",
        "txtPeriodoEmergencia",
        "txtLocalEmergencia",
        "slcAbstinencia",
        "txtMotivoSaida",
        "slcFezTratamento",
        "txtVezesTratamento",
        "txtTempoTratamento",
        "slcInternacaoCompulsoria",
        "txtVezesInternacaoCompulsoria",
        "txtLocalInternacaoCompulsoria",
        "slcRecaidaUsoDrogas",
        "txtQtdRecaidaUsoDrogas",
        "slcRecaida",
        "slcTraumaRecaida",
        "txtOutroTraumaRecaida",
        "slcInternacaoDesintoxicacao",
        "txtQtdInternacaoDesintoxicacao",
        "txtInformacoesCompartilhadas"
    ];
}

function inicializaAbaMedicacao(){
    $(document)
        .off("click.medicacaoSalvar", "#tabMedicacao #btnCadMedicacao")
        .on("click.medicacaoSalvar", "#tabMedicacao #btnCadMedicacao", function(){
            cadastraMedicacao();
        });

    listaMedicacoes();
}

function cadastraMedicacao(){
    var id = $("#hidEntrada").val();
    if(!id || isNaN(parseInt(id, 10)) || parseInt(id, 10) <= 0){
        alert("ID de acolhimento inválido na tela de Medicação.");
        return;
    }

    var dataRegistro = $.trim($("#txtDataMedicacaoRegistro").val() || "");
    var nomeMedicacao = $.trim($("#txtNomeMedicacao").val() || "");

    if(dataRegistro === "" || nomeMedicacao === ""){
        alert("Preencha pelo menos Data e Nome da medicação.");
        return;
    }

    var form = $("#formMedicacao")[0];
    var data = new FormData(form);
    data.append("id", id);

    $.ajax({
        type: "POST",
        enctype: "multipart/form-data",
        url: "../public/componentes/prontuario_acolhido/model/cadastraMedicacao.php",
        data: data,
        processData: false,
        cache: false,
        contentType: false,
        success: function(retorno){
            var novoId = parseInt($.trim(retorno), 10) || 0;
            if(novoId > 0){
                alert("Medicação registrada com sucesso");
                limpaFormularioMedicacao();
                listaMedicacoes();
                $("#colMedicacao").removeClass("show");
            }
            else{
                alert("Não foi possível registrar a medicação. Retorno: " + retorno);
            }
        },
        error: function(xhr){
            alert("Erro ao registrar medicação: " + (xhr.responseText || xhr.statusText));
        }
    });
}

function limpaFormularioMedicacao(){
    var form = $("#formMedicacao")[0];
    if(form){
        form.reset();
    }
}

function listaMedicacoes(){
    var id = $("#hidEntrada").val();
    if(!id || isNaN(parseInt(id, 10)) || parseInt(id, 10) <= 0){
        $("#boxListaMedicacoes").html("");
        return;
    }

    $.ajax({
        type: "POST",
        url: "../public/componentes/prontuario_acolhido/model/listaMedicacoes.php",
        data: {id:id},
        success: function(retorno){
            $("#boxListaMedicacoes").html(retorno);
        },
        error: function(){
            $("#boxListaMedicacoes").html("");
        }
    });
}
