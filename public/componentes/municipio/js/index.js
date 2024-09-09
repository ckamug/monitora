$(document).ready(function(){
	listaMunicipios();
})

function listaMunicipios(){
	
	$.ajax({
		type: "POST",
		url: "public/componentes/municipio/model/listaMunicipios.php",
		success: function (retorno) {
		  $("#boxListaMunicipios").html(retorno);
		  
		  const dataTable = new simpleDatatables.DataTable("#tblMunicipios", {
			fixedHeight: false
		  })
		}
	});

}

function municipio(id){
	location.href='cadastro-municipio/'+id;
}