$(document).ready(function(){
	listaEncaminhamentosOscs();
});

function listaEncaminhamentosOscs(){
	$.ajax({
		type: "POST",
		url: "public/componentes/encaminhamentos-oscs/model/listaEncaminhamentosOscs.php",
		success: function (retorno) {
			$("#boxListaEncaminhamentosOscs").html(retorno);

			if ($("#tblEncaminhamentosOscs").length) {
				new simpleDatatables.DataTable("#tblEncaminhamentosOscs");
			}
		}
	});
}
