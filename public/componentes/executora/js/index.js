$(document).ready(function(){
	listaExecutoras();
})

function listaExecutoras(){
	
	$.ajax({
		type: "POST",
		url: "public/componentes/executora/model/listaExecutoras.php",
		success: function (retorno) {
		  $("#boxListaExecutoras").html(retorno);
		  
		  const dataTable = new simpleDatatables.DataTable("#tblExecutoras", {
			fixedHeight: false,
		  })
		}
	});

}

function executora(id){
	location.href='cadastro-executora/'+id;
}