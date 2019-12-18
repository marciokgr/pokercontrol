window.onload = function() 
{
	$('login').focus();
	return false;
}

function logon()
{				
	var login = "";
	var senha = "";
	
	login = document.getElementById("login").value;
	senha = document.getElementById("senha").value;
	
	if (login == ""){
		alert('Você deve informar um login!');
		document.getElementById("login").focus()
		return false;
	}
	
	if (senha == ""){
		alert('Você deve informar uma senha!');
		document.getElementById("senha").focus();
		return false;
	}
	
	loading();
	
	sURL  = "ajax/logon.php?origem=logon"	+
			"&login="	+ login				+
			"&senha="	+ senha				;
	enviaDadosAJAX(sURL, "valida", "divCarregando", false);				
}

function valida(retorno)
{
	if (retorno > 0){
		window.location.href = "principal.php"
	} else {
		alert('Login ou senha inválidos!');
	}
	unloading();
}		

//Ao pressionar enter na senha
function capturaTecla(e)
{
	var e = e || window.event
	var keyCode = e.wich || e.keyCode;		
	if (keyCode == 13){logon();}else{return true;}
}