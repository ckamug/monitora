$(document).ready(function(){
	listaAcolhidos();
})

function listaAcolhidos(){
	
	$.ajax({
		type: "POST",
		url: "public/componentes/hub/model/listaAcolhidos.php",
		success: function (retorno) {
		  $("#boxListaAcolhidos").html(retorno);
		  
		  const dataTable = new simpleDatatables.DataTable("#tblAcolhidos", {
			columns:[
				{select:5, sortable:false}
			]
		  })
		}
	});

}

function acolhido_hub(id){
	location.href='cadastro-hub/'+id;
}