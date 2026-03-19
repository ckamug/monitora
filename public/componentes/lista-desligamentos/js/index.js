$(document).ready(function(){
	listaDesligamentos();
})

function listaDesligamentos(){
	
	$.ajax({
		type: "POST",
		url: "public/componentes/lista-desligamentos/model/listaDesligamentos.php",

		success: function (retorno) {
		  $("#boxListaDesligamentos").html(retorno);
		  
		  const dataTable = new simpleDatatables.DataTable("#tblDesligamentos", {
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

function desligamento(id){
	location.href='desligamento/'+id;
}
