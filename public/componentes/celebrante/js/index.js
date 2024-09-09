$(document).ready(function(){
	listaCandidatos();
})

function listaCandidatos(){
	
	$.ajax({
		type: "POST",
		url: "public/componentes/celebrante/model/listaCelebrantes.php",
		success: function (retorno) {
		  $("#boxListaCelebrantes").html(retorno);
		  
		  const dataTable = new simpleDatatables.DataTable("#tblCelebrantes", {
			searchable: false,
			fixedHeight: false,
			sortable: false,
			perPageSelect: false
		  })
		}
	});

}

function celebrante(id){
	location.href='cadastro-celebrante/'+id;
}