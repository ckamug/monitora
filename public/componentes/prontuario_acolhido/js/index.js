$(document).ready(function(){
	listaAcoes('Et');
    carregaTiposDesligamentos(0);
    carregaDesligamento();
    carregaDadosSensiveis();

    $("[name='radViolenciaSexual'] , [name='radViolenciaParceiros'] , [name='radSuporte'] , [name='radAutorViolencia'] , [name='radResponsabilizado'] , [name='radPenaAplicada'] , [name='radEgresso'] , [name='radEgressoPena']").on('click',function(){

		switch(this.name){
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
				}
				else{
					$("#boxViolenciaParceiros").addClass("d-none");
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

		}

	});

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
	  data: {},
	  success: function (retorno) {
		$("#boxTiposAtendimentosPsicologia").html(retorno);
        $("#boxTiposAtendimentosServicoSocial").html(retorno);
	  }
	});
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
        }
    });

}

function cadastraDadosSensiveis(){
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
        alert('Registros efetuados');

        $("#boxBotaoDadosSensiveis").html('<button type="button" class="btn btn-success mt-5 mb-3 mx-0" id="btnEditar">Alterar dados sensíveis</button>');
		    $("#btnEditar").click(function() {editaDadosSensiveis(retorno)});
	    }

	});
}

function editaDadosSensiveis(id){
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
        alert('Alterações efetuadas');
      }

	});
}

function carregaDadosSensiveis(){
	id = $("#hidEntrada").val();
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
                if (resultado.desligamento_impactos.includes('autossustento')){
                    $("#chkImpactos12").prop('checked',true);
                }
                if (resultado.desligamento_impactos.includes('Bancarização')){
                    $("#chkImpactos13").prop('checked',true);
                }
                if (resultado.desligamento_impactos.includes('Não houve')){
                    $("#chkImpactos14").prop('checked',true);
                }

                setTimeout("$('#formDesligamento input[type=text], #formDesligamento input[type=date], #formDesligamento input[type=radio], #formDesligamento input[type=checkbox], #formDesligamento textarea, #formDesligamento select').prop('disabled', true)",1000);
				$("#btnCadDesligamento").addClass('d-none');
                $("#boxInfoDesligamentos").removeClass('d-none');
			}
		},
		complete: function(){}
	 });
}