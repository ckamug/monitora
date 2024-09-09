$(document).ready(function(){
	listaUsuarios();
})

function listaUsuarios(){
	
	$.ajax({
		type: "POST",
		url: "public/componentes/usuarios/model/listaUsuarios.php",
		success: function (retorno) {
		  $("#boxListaUsuarios").html(retorno);
		  
		  const dataTable = new simpleDatatables.DataTable("#tblUsuarios", {
			fixedHeight: false
		  })
		}
	});

}

function usuario(id){
	location.href='cadastro-usuario/'+id;
}