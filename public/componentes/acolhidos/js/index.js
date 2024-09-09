$(document).ready(function(){
	listaAcolhidos();
})

function listaAcolhidos(){
	
	$.ajax({
		type: "POST",
		url: "public/componentes/acolhidos/model/listaAcolhidos.php",
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

function acolhido(id){
	location.href='cadastro-acolhido/'+id;
}

function prontuario(id){
	location.href='prontuario/'+id;
}