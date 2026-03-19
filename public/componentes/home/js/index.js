function gravarForm()
{

	var campo1 = $("#txtCampo1").val();
	var campo2 = $("#txtCampo2").val();
	var campo3 = $("#txtCampo3").val();
	
	$.post('../public/componentes/home/model/backend.php',{campo1:campo1,campo2:campo2,campo3:campo3},function(data){
		if(data==0){
			showDialog(0,'Erro na inclusão de dados');
		}
		else{
			showDialog(0,data);
		}
	});
}

function showDialog(tipo,msg)
{
	$("#dialog:ui-dialog").show();
	$("#dialog:ui-dialog").dialog("destroy");
	$("#dialog-message").html(msg);
	switch(tipo)
	{
		case 0:
	    	$("#dialog-message").dialog(
			{
		  		modal: true,
		  		buttons:
				{
					OK: function()
					{
						$( this ).dialog("close");
					}
				}
			});
		break;
	}
}