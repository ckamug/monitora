$(document).ready(function(){

	carregaTiposRegistros(0);
	carregaPerfis(0);
	carregaUsuario($("#hidIdUsuario").val());

	$('#txtCpf').mask('000.000.000-00');
	$("#txtCpf").blur(function() {consultaCpf()});

	$("#formUsuario").validate({
		invalidHandler: function() {
			alert('Preencha todos os campos obrigatórios');
		},
		submitHandler: function(){

			alert("Informações registradas com sucesso");
			cadastraUsuario();

		},
		rules:{
		  slcPerfil:{
			required:true,
		  },
		  txtNome:{
			required:true,
		  },
		  txtCpf:{
			required:true,
		  },
		  txtEmail:{
			required:true,
		  },

		},
  
	  }

	);

})

function validaCpf(){

    myCPF = $('#txtCpf').val().replace('.', '').replace('.', '').replace('-', '');
    var numeros, digitos, soma, i, resultado, digitos_iguais;
    digitos_iguais = 1;

    for (i = 0; i < myCPF.length - 1; i++)
        if (myCPF.charAt(i) != myCPF.charAt(i + 1)) {
            digitos_iguais = 0;
            break;
        }
    if (!digitos_iguais) {
        numeros = myCPF.substring(0, 9);
        digitos = myCPF.substring(9);
        soma = 0;
        for (i = 10; i > 1; i--)
            soma += numeros.charAt(10 - i) * i;
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(0))
        {
            return false;
        }
        numeros = myCPF.substring(0, 10);
        soma = 0;
        for (i = 11; i > 1; i--)
            soma += numeros.charAt(11 - i) * i;
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(1))
        {
            return false;
        }
        return true;
    }
    else
    {
        return false;
    }

}

function consultaCpf(){

    if(validaCpf()){

		var cpf = $("#txtCpf").val();

        $.ajax({
            url: "https://portal.seds.sp.gov.br/coed/public/componentes/cadastro-usuario/model/consultaCpf.php",
            type: "POST",
            data:  {'cpf':cpf},
            success: function(retorno){
				if(retorno>0){
					alert("CPF Já cadastrado");
				}
            }

        });
    
	}
    else{
        alert("CPF Inválido");
    }

}

function carregaUsuario(id){
	$.ajax({
		url:'https://portal.seds.sp.gov.br/coed/public/componentes/cadastro-usuario/model/carregaUsuario.php',
		dataType: 'JSON',
		type: 'POST',
		data: {'id':id},
		success: function(resultado){
				
			if(resultado.usuario_id>0){
				carregaPerfis(0);
				$("#txtNome").val(resultado.usuario_nome);
				$("#txtCpf").val(resultado.usuario_cpf);
				$("#txtEmail").val(resultado.usuario_email);
				carregaTiposRegistros(resultado.tipo_registro_id);
				$("#txtNumeroRegistro").val(resultado.numero_registro);
				$("#boxBotoes").html('<button type="button" class="btn btn-success" id="btnEditar">Alterar Informações</button>');
				$("#btnEditar").click(function() {editaUsuario(resultado.usuario_id)});
				$('#boxVinculoUsuario').removeClass('d-none');
				listaVinculos();

			}
			else{
				$("#boxBotoes").html('<button type="submit" class="btn btn-success" id="btnRegistrar">Cadastrar Usuário</button>');
			}
		},
		complete: function(){}
	 });
}

function carregaTiposRegistros(id){
	$.ajax({
	  type: "POST",
	  url: "https://portal.seds.sp.gov.br/coed/public/componentes/cadastro-usuario/model/carregaTipoRegistro.php",
	  data: {'id':id},
	  success: function (retorno) {
		$("#boxTiposRegistros").html(retorno);
	  }
	});
}

function cadastraUsuario(){
	var form = $("#formUsuario").serialize();
	$.ajax({
	  type: "POST",
	  url: "https://portal.seds.sp.gov.br/coed/public/componentes/cadastro-usuario/model/cadastraUsuario.php",
	  data: form,
	  success: function (retorno) {
		location.href = "/coed/cadastro-usuario/" + retorno;
	  }
	});
}

function editaUsuario(id){
	var form = $("#formUsuario").serialize();
	form += "&id="+id;
	$.ajax({
	  type: "POST",
	  url: "https://portal.seds.sp.gov.br/coed/public/componentes/cadastro-usuario/model/editaUsuario.php",
	  data: form,
	  success: function (retorno) {
		alert('Informações alteradas com sucesso');
		carregaUsuario(id);
	  }
	});
}

function carregaPerfis(id){
	$.ajax({
	  type: "POST",
	  url: "https://portal.seds.sp.gov.br/coed/public/componentes/cadastro-usuario/model/carregaPerfis.php",
	  data: {'id':id},
	  success: function (retorno) {
		$("#boxPerfis").html(retorno);
	  }
	});
}

function perfilVinculo(id,vinculo_id){
	$.ajax({
		type: "POST",
		url: "https://portal.seds.sp.gov.br/coed/public/componentes/cadastro-usuario/model/carregaPerfilVinculo.php",
		data: {'id':id, 'vinculo_id':vinculo_id},
		success: function (retorno) {
		  $("#boxVinculoPerfil").html(retorno);
		  $('#boxVinculo').removeClass('d-none');
		  $('#btnVincular').removeClass('d-none');
		  
		  if(id==1 || id==5 || id==7 || id==8){
			$('#boxVinculo').addClass('d-none');
		  }
		  else if(id==0){
			$('#boxVinculo').addClass('d-none');
			$('#btnVincular').addClass('d-none');
		  }
		  else{}
		  
		}
	});
}

function carregaCasas(id){
	$.ajax({
		type: "POST",
		url: "https://portal.seds.sp.gov.br/coed/public/componentes/cadastro-usuario/model/carregaCasas.php",
		data: {'id':id},
		success: function (retorno) {
			if(retorno != 0){
				$("#boxVinculoCasas").html(retorno);
				$('#boxVinculo').removeClass('d-none');
				$('#boxVinculoCasas').removeClass('d-none');
				$('#btnVincular').removeClass('d-none');
			}
		}
	});
}

function cadastraVinculo(){
	var id = $("#hidIdUsuario").val();
	var perfil = $("#slcPerfis").val();
	var subperfil = $("#slcPerfilVinculo").val();

	$.ajax({ 
		type: "POST",
		url: "https://portal.seds.sp.gov.br/coed/public/componentes/cadastro-usuario/model/cadastraVinculo.php",
		data: {'id':id, 'perfil':perfil, 'subperfil':subperfil},
		success: function (retorno) {
			if(retorno==0){
				alert('Vinculo já efetuado para este usuário');
			}
			else{
				alert('Usuário vinculado');
				listaVinculos();
			}
		}
	});
}

function listaVinculos(){
	var id = $("#hidIdUsuario").val();

	$.ajax({
		type: "POST",
		url: "https://portal.seds.sp.gov.br/coed/public/componentes/cadastro-usuario/model/listaVinculos.php",
		data: {'id':id},
		success: function (retorno) {
		  $("#boxListaVinculos").html(retorno);
		  
		  const dataTable = new simpleDatatables.DataTable("#tblVinculos", {
			fixedHeight: false,
			searchable: false,
			fixedHeight: false,
			sortable: false,
			perPageSelect: false
		  })
		}
	});

}

function criaPergunta(id){
	
	var modalConfirmacao = new bootstrap.Modal(document.getElementById('confirmacaoModal'), {
		keyboard: false
	})

	modalConfirmacao.show();
	$("#tituloModal").html('<h5 class="modal-title" id="tituloModal">Desvincular usuário</h5>');
	$("#corpoModal").html('<p>Deseja desvincular o usuário da OSC?</p>');
	$("#boxBotoesModal").html('<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Não Desvincular</button><button type="button" data-bs-dismiss="modal" class="btn btn-success" onclick="desvincularUsuario('+id+');">Desvincular</button>');	

}

function desvincularUsuario(id){
	
	$.ajax({
		type: "POST",
		url: "https://portal.seds.sp.gov.br/coed/public/componentes/cadastro-usuario/model/desvincularUsuario.php",
		data:{id:id},
		success: function(){
			alert('Usuário desvinculado');
			listaVinculos();
		}
	});

}
