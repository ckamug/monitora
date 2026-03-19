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

function criaPergunta(id){
	
	var modalConfirmacao = new bootstrap.Modal(document.getElementById('confirmacaoModal'), {
		keyboard: false
	})

	modalConfirmacao.show();
	$("#tituloModal").html('<h5 class="modal-title" id="tituloModal">Reset de senha</h5>');
	$("#corpoModal").html('<p>Confirma o reset da senha?</p>');
	$("#boxBotoesModal").html('<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Não Confirmar</button><button type="button" data-bs-dismiss="modal" class="btn btn-success" onclick="resetSenha('+id+');">Confirmar</button>');	

}

function resetSenha(id){
	
	$.ajax({
		type: "POST",
		url: "public/componentes/usuarios/model/resetSenha.php",
		data:{id:id},
		success: function(){
			alert('Reset de senha efetuado');
			listaUsuarios();
		}
	});

}