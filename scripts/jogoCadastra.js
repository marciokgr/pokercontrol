window.onload = function()
{
	loading();
	buscaLocais();
	
	if (document.getElementById("hidJogoID").value == "")
	{
		document.getElementById("btnAlterar").style.display = "none";
		document.getElementById("btnNovo").style.display = "none";
		buscaParticipantes(0);
	}
	else
	{
		document.getElementById("btnCadastrar").style.display = "none";
		document.getElementById("btnNovo").style.display = "";

		buscaParticipantes(document.getElementById("hidJogoID").value);
		listaVencedores(document.getElementById("hidJogoID").value)
		buscaTotalFichas(document.getElementById("hidJogoID").value)
	}
	
	document.getElementById("txtData").focus();
	
	//Desabilita Campos Cadastro quando usu�rio.
	verificaPermissao("<?=document.getElementByIdsNivel?>"); 
	
	unloading();
};

function buscaLocais()
{	
	sURL  = "ajax/jogo.php?origem=buscaLocais";
	enviaDadosAJAX(sURL, "carregaLocais", undefined, false);
}

function carregaLocais(retorno)
{
	if (retorno != "")
	{
		aLocais = retorno.split("|@|@|");
		
		for (var i = 0; i < aLocais.length; i++)
		{
			var local = document.createElement("option");
			local.value = aLocais[i].split("||")[0];
			local.text = aLocais[i].split("||")[1];
			
			if (document.getElementById("hidIDLocal").value == local.value)
			{
				local.selected = "selected";
			}
			
			try { //IE
				document.getElementById("selLocal").add(local); 
			} catch(e) {//FF
				document.getElementById("selLocal").add(local, null); 
			}
		}
	}
}

function buscaParticipantes(jogoID)
{
	sURL  = "ajax/jogo.php?origem=buscaParticipantes&jogoID="+ jogoID;
	enviaDadosAJAX(sURL, "carregaParticipantes", undefined, false);
}

function carregaParticipantes(retorno)
{
	document.getElementById("divParticipantes").innerHTML = retorno;
	alternaRelatorio("ListTableVenc");
}

function buscaTotalFichas(jogoID)
{
	sURL  = "ajax/jogo.php?origem=buscaTotalFichas&jogoID="+ jogoID;
	enviaDadosAJAX(sURL, "carregaTotalFichas",undefined, false);
}

function carregaTotalFichas(retorno)
{
	document.getElementById("hidTotalFichas").value = retorno;
	//document.getElementById("TotalFichas").innerHTML = document.getElementById("hidTotalFichas").value
}

function listaVencedores(jogoID)
{
	sURL  = "ajax/jogo.php?origem=listaVencedores&jogoID="+ jogoID;
	enviaDadosAJAX(sURL, "carregaVencedores",undefined, false);
}

function carregaVencedores(retorno)
{
	if (retorno != "")
	{
		document.getElementById("divVencedores").style.display = '';
		document.getElementById("divVencedores").innerHTML = retorno;
	}else{
		document.getElementById("divVencedores").style.display = 'none';
	}
	
	alternaRelatorio();
}			

function validaCampos(acao)
{
	if (document.getElementById("txtData").value == "")
	{
		alert("Informe a Data");
		document.getElementById("txtData").focus();
		return false;
	}
	
	if (document.getElementById("selLocal").value == "0")
	{
		alert("Selecione o Local");
		document.getElementById("selLocal").focus();
		return false;
	}
	
	if (document.getElementById("txtValorBuyin").value == "")
	{
		alert("Informe o valor do Buy-In");
		document.getElementById("txtValorBuyin").focus();
		return false;
	}
	
	if (document.getElementById("txtFichasBuyin").value == "")
	{
		alert("Informe a quantidade de fichas do Buy-In");
		document.getElementById("txtFichasBuyin").focus();
		return false;
	}
	
	if (document.getElementById("txtValorRebuy").value == "")
	{
		alert("Informe o valor do Re-Buy");
		document.getElementById("txtValorRebuy").focus();
		return false;
	}
	
	if (document.getElementById("txtFichasRebuy").value == "")
	{
		alert("Informe a quantidade de fichas do Re-Buy");
		document.getElementById("txtFichasRebuy").focus();
		return false;
	}
	
	loading();
	if (acao == 'c')
		cadastraJogo();
	
	if (acao == 'a')
		editaJogo();
}

function cadastraJogo()
{					
	sURL  = "ajax/jogo.php?origem=cadastra"				+
			"&data="		+ document.getElementById("txtData").value		+
			"&local="		+ document.getElementById("selLocal").value		+
			"&valorBuyin="	+ document.getElementById("txtValorBuyin").value	+
			"&fichasBuyin="	+ document.getElementById("txtFichasBuyin").value	+
			"&valorRebuy="	+ document.getElementById("txtValorRebuy").value	+
			"&fichasRebuy="	+ document.getElementById("txtFichasRebuy").value	;		
	enviaDadosAJAX(sURL, "retornoCadastro",undefined, false);
	
}

function retornoCadastro(retorno)
{
	dadosParticipantes = "";
	
	var campos = document.getElementsByName("chbPart");
	for (var i = 0; i < campos.length; i++)
	{
		if (campos[i].checked)
		{
			if (dadosParticipantes != "")
				dadosParticipantes += "|@|@|";
			
			/* IDParticipante || Re-Buy's || Vencedor || Fichas */
			dadosParticipantes +=   campos[i].value + "||";
			
			if (document.getElementById("txtRebuys" + campos[i].value).value != "")
				dadosParticipantes += document.getElementById("txtRebuys" + campos[i].value).value +"||";
			else
				dadosParticipantes += "0||";
			
			if (document.getElementById("chbVencedor" + campos[i].value).checked)
				dadosParticipantes += "1||";
			else
				dadosParticipantes += "0||";
									
			if (document.getElementById("chbVencedor" + campos[i].value).checked && document.getElementById("txtFichas" + campos[i].value).value != "")
				dadosParticipantes += document.getElementById("txtFichas" + campos[i].value).value;
			else
				dadosParticipantes += "0";
		}
	}
	
	sURL  = "ajax/jogo.php?origem=atualizaParticipantes"+
			"&idJogo="	+ retorno 						+
			"&dados="	+ dadosParticipantes;
	enviaDadosAJAX(sURL, "retornoParticipantes",undefined, false);
	
	document.getElementById("hidJogoID").value = retorno
	
	unloading();
}

function retornoParticipantes()
{
	listaVencedores(document.getElementById("hidJogoID").value);
	
	document.getElementById("btnAlterar").style.display = "";
	document.getElementById("btnCadastrar").style.display = "none";
	
}

function selecionaParticipante(obj)
{
	if (obj.checked)
	{
		document.getElementById("txtRebuys"+ obj.value).style.display = "";
		document.getElementById("chbVencedor"+ obj.value).style.display = "";	

		//Aumenta as fichas totais
		document.getElementById("hidTotalFichas").value = (pInt(document.getElementById("hidTotalFichas").value) + pInt(document.getElementById("txtFichasBuyin").value))
	}
	else
	{
		
		document.getElementById("txtRebuys"+ obj.value).value = "";
		document.getElementById("txtRebuys"+ obj.value).style.display = "none";
		
		document.getElementById("chbVencedor"+ obj.value).checked = false;
		document.getElementById("chbVencedor"+ obj.value).style.display = "none";
		
		document.getElementById("txtFichas"+ obj.value).value = "";
		document.getElementById("txtFichas"+ obj.value).style.display = "none";
		
		//Diminui as fichas totais
		document.getElementById("hidTotalFichas").value = (pInt(document.getElementById("hidTotalFichas").value) - pInt(document.getElementById("txtFichasBuyin").value))
							
	}			
	
	//document.getElementById("TotalFichas").innerText = document.getElementById("hidTotalFichas").value

}
			
//Para manter o total de fichas da partida.
function controlaTotalFichas(obj,tipo)
{	
		
	if (tipo == 2)
	{
		if (obj.value != "")	
		{
			if (pInt(obj.getAttribute("valorAnterior")) != pInt(obj.value))
			{
				//Diminui o valor atual						
				document.getElementById("hidTotalFichas").value = (pInt(document.getElementById("hidTotalFichas").value) - (pInt(document.getElementById("txtFichasRebuy").value) * pInt(obj.getAttribute("valorAnterior"))));
				
				//Atualiza o novo valor						
				document.getElementById("hidTotalFichas").value = (pInt(document.getElementById("hidTotalFichas").value) + (pInt(document.getElementById("txtFichasRebuy").value) * pInt(obj.value)));
				
				//Atualiza o atributo pois já calculou
				obj.setAttribute("valorAnterior",obj.value);
			}
		}
		else
		{
			if (pInt(obj.getAttribute("valorAnterior")) > 0)
			{
				//Diminui o valor atual
				document.getElementById("hidTotalFichas").value = (pInt(document.getElementById("hidTotalFichas").value) - (pInt(document.getElementById("txtFichasRebuy").value) * pInt(obj.getAttribute("valorAnterior"))));

				//Atualiza o atributo pois j� calculou
				obj.setAttribute("valorAnterior","");
			}
		}		
				
	}else{
		
		if (pInt( obj.getAttribute("valorAnterior")) == 0)	
		{
			if (pInt(obj.getAttribute("valorAnterior")) != pInt(obj.value))
			{
				//Diminui o valor atual
				document.getElementById("hidTotalFichas").value = (pInt(document.getElementById("hidTotalFichas").value) - (pInt( document.getElementById("txtFichasBuyin").value) * pInt( obj.getAttribute("valorAnterior"))));
				
				//Atualiza o novo valor						
				document.getElementById("hidTotalFichas").value = (pInt( document.getElementById("hidTotalFichas").value) - pInt( obj.value));
				
				//Atualiza o atributo pois já calculou
				obj.setAttribute("valorAnterior",obj.value);
			}
		}
		else
		{
			if (pInt( obj.getAttribute("valorAnterior")) > 0)
			{
				//Soma o valor atual para restaurar..
				document.getElementById("hidTotalFichas").value = (pInt( document.getElementById("hidTotalFichas").value) + pInt( obj.getAttribute("valorAnterior")));
				
				//Subtrai o valor informado no momento.
				document.getElementById("hidTotalFichas").value = (pInt( document.getElementById("hidTotalFichas").value) - pInt( obj.value));
				
				//Atualiza o atributo pois já calculou
				obj.setAttribute("valorAnterior","");
			}
		}
	}
	
	//Coloca no label de referencia
	//document.getElementById("TotalFichas").innerHTML = document.getElementById("hidTotalFichas").value
}		

function pInt(valor)
{				
	return parseInt(0 + valor,10);
}

function editaJogo()
{	
	sURL  = "ajax/jogo.php?origem=edita"				+
			"&idJogo="		+ document.getElementById("hidJogoID").value		+
			"&data="		+ document.getElementById("txtData").value		+
			"&local="		+ document.getElementById("selLocal").value		+
			"&valorBuyin="	+ document.getElementById("txtValorBuyin").value	+
			"&fichasBuyin="	+ document.getElementById("txtFichasBuyin").value	+
			"&valorRebuy="	+ document.getElementById("txtValorRebuy").value	+
			"&fichasRebuy="	+ document.getElementById("txtFichasRebuy").value	;		
	enviaDadosAJAX(sURL, "retornoCadastro",undefined, false);
}

function selecionaVencedor(obj, id)
{
	if (obj.checked)
	{
		document.getElementById("txtFichas"+ id).style.display = "";
		
		//Joga o total de fichas ainda dispon�vel pro campo de fichas do vencedor.
		document.getElementById("txtFichas"+ id).value = pInt( document.getElementById("hidTotalFichas").value);
	}
	else
	{
		document.getElementById("txtFichas"+ id).value = "";
		document.getElementById("txtFichas"+ id).style.display = "none";
	}
}			

function novo()
{
	window.location.href = "jogoCadastra.php";
}