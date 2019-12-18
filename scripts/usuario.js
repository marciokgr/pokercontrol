var sNivel = ""

window.onload=function()
{
	loading();
	
	document.getElementById("btnAlterar").style.display = "none";
	document.getElementById("btnNovo").style.display = "none";
	
	listaUsuarios();
	
	document.getElementById("txtNome").focus();
	
	//Desabilita Campos Cadastro quando usuário.
	verificaPermissao(sNivel);
	
	unloading();
};

function listaUsuarios()
{	
	sURL  = "ajax/usuario.php?origem=lista";				
	enviaDadosAJAX(sURL, 'listaUsuario',undefined, false);						
}

function listaUsuario(ret) {
	document.getElementById("listagem").innerHTML  = ret;
	alternaRelatorio();
}

function validaCampos(acao)
{
	if (document.getElementById("txtNome").value == "")
	{
		alert("Informe o nome");
		document.getElementById("txtNome").focus();
		return false;
	}
	
	if (document.getElementById("txtEmail").value == "")
	{
		alert("Informe o email");
		document.getElementById("txtEmail").focus();
		return false;
	}
	
	if (!validaEmail("txtEmail"))
	{
		alert("E-mail informado é inválido");
		document.getElementById("txtEmail").focus();
		return false;
	}
	
	if (document.getElementById("nivel").value == "")
	{
		alert("Você deve selecionar um Nível de Acesso ao Usuário");
		document.getElementById("nivel").focus();
		return false;
	}
	
	if (document.getElementById("txtLogin").value != "")
	{
		validaLogin();					
	}
	
	loading();
	if (acao == 'c')
		cadastraUsuario();
	
	if (acao == 'a')
		editaUsuario();
}

function validaLogin()
{
	sURL  = "ajax/usuario.php?origem=validaLogin&login=" + document.getElementById("txtLogin").value + "&id="+ document.getElementById("hidUsuID").value;
	enviaDadosAJAX(sURL, "retornoValidaLogin", undefined, false);
	
	return true;				
}

function retornoValidaLogin(retorno)
{				
	if (retorno != "")
	{
		if (retorno == "0")
			return true;
		else
		{
			alert("O Login informado já está sendo utilizado por outro usuário!");
			document.getElementById("txtLogin").focus();
			return false;
		}
	}
}

function cadastraUsuario()
{
	sURL  = "ajax/usuario.php?origem=cadastra"	+
			"&nome="	+ document.getElementById("txtNome").value	+
			"&email="	+ document.getElementById("txtEmail").value	+
			"&login="	+ document.getElementById("txtLogin").value	+
			"&senha="	+ document.getElementById("txtSenha").value	+
			"&nivel="	+ document.getElementById("nivel").value		;		
	enviaDadosAJAX(sURL, "retornoCadastro",undefined, false);
}

function editaUsuario()
{
	sURL  = "ajax/usuario.php?origem=altera"	+
			"&id="		+ document.getElementById("hidUsuID").value	+
			"&nome="	+ document.getElementById("txtNome").value	+
			"&email="	+ document.getElementById("txtEmail").value	+
			"&login="	+ document.getElementById("txtLogin").value	+
			"&senha="	+ document.getElementById("txtSenha").value	+
			"&nivel="	+ document.getElementById("nivel").value		;		
	enviaDadosAJAX(sURL, "retornoCadastro",undefined, false);
}

function retornoCadastro(retorno)
{
	document.getElementById("hidUsuID").value = "";
	document.getElementById("txtNome").value  = "";
	document.getElementById("txtEmail").value = "";
	document.getElementById("txtLogin").value = "";
	document.getElementById("txtSenha").value = "";
	document.getElementById("nivel").value 	= "";
	
	document.getElementById("btnAlterar").style.display = "none";
	document.getElementById("btnNovo").style.display = "none";
	
	document.getElementById("btnCadastrar").style.display = "";
	
	listaUsuarios();
	
	unloading();
}

function alteraUsuario(id)
{
	sURL  = "ajax/usuario.php?origem=busca&id=" + id;
	enviaDadosAJAX(sURL, "retornoAleracao", undefined, false);
}

function retornoAleracao(retorno)
{
	document.getElementById("btnCadastrar").style.display = "none";				
	
	document.getElementById("hidUsuID").value = retorno.split("||")[0]; //ID
	document.getElementById("txtNome").value  = retorno.split("||")[1]; //Nome
	document.getElementById("txtEmail").value = retorno.split("||")[2]; //E-mail
	document.getElementById("txtLogin").value = retorno.split("||")[3]; //Login
	document.getElementById("txtSenha").value = "";
	document.getElementById("nivel").value 	= retorno.split("||")[4]; //Nivel
	
	document.getElementById("btnNovo").style.display = "";
	document.getElementById("btnAlterar").style.display = "";
	
	unloading();
}			

function retornoValidaExclusao(retorno)
{					
	if (retorno == "0")
	{					
		if(confirm("Tem certeza que deseja excluir este usuário?"))
		{
			
			sURL  = "ajax/usuario.php?origem=exclui&id=" + document.getElementById("hidUsuID").value; //Gambi
			enviaDadosAJAX(sURL, "retornoExclusao",undefined, false);
		}
	}
	else
	{
		alert("Este usuário não pode ser excluído pois ele já participou de partidas.");
		document.getElementById("txtLogin").focus();					
		return false;
	}				
}	

function excluiUsuario(id)
{	

	if (sNivel == "U")
	{
		alert("Você tem permissão para exclusão de Usuários");
		return false;
		
	}else{
		document.getElementById("hidUsuID").value = id;
		validaExclusao(id);	
	}
}

function validaExclusao(id)
{	
	
	sURL  = "ajax/usuario.php?origem=validaExclusao&id=" + id;
	enviaDadosAJAX(sURL, "retornoValidaExclusao", undefined, false);

}

function retornoExclusao()
{
	listaUsuarios();
}	

function novo()
{
	window.location.href = "usuario.php"	;
}