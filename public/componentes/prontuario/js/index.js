$(document).ready(function(){
    carregaAcolhido();
    listaEntradas();
})

function carregaAcolhido(){

	var id = $("#hidIdOrigem").val();
    
    $.ajax({
		url:'../public/componentes/prontuario/model/carregaAcolhido.php',
		dataType: 'JSON',
		type: 'POST',
		data: {'id':id},
		success: function(resultado){

            $("#txtAcolhido").html("Prontuário Eletrônico de " + resultado.acolhido_nome_completo);

		},
		complete: function(){}
	 });
}

function listaEntradas(){

    var id = $("#hidIdOrigem").val();

	$.ajax({
		type: "POST",
		url: "../public/componentes/prontuario/model/listaEntradas.php",
		data:{'id':id},
		success: function (retorno) {
		  $("#boxListaEntradas").html(retorno);
		}
	});

}