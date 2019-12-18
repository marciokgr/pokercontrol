/*---------------------------------------------------------------------
Formatar Moeda
Exemplo:
	onkeypress="return formatar_moeda(this,','.',',event);"
---------------------------------------------------------------------*/
function formatar_moeda(campo, separador_milhar, separador_decimal, tecla) {	
	var sep = 0;
	var key = '';
	var i = j = 0;
	var len = len2 = 0;
	var strCheck = '0123456789';
	var aux = aux2 = '';
	var whichCode = (window.Event) ? tecla.which : tecla.keyCode;
	
	
	if (whichCode == 13 || whichCode == 8 || whichCode == 0) {return true;} // Tecla Enter, Delete ou Backspace
	
	key = String.fromCharCode(whichCode); 			// Pegando o valor digitado
	if (strCheck.indexOf(key) == -1) return false;  // Valor inválido (não inteiro)
	
	len = campo.value.length;
	
	//Tamanho campo maior que 12
	if (len > 12) return false;
	
	for(i = 0; i < len; i++)
	if ((campo.value.charAt(i) != '0') && (campo.value.charAt(i) != separador_decimal)) break;
	aux = '';
	for(; i < len; i++)
	if (strCheck.indexOf(campo.value.charAt(i))!=-1) aux += campo.value.charAt(i);
	aux += key;
	len = aux.length;
	if (len == 0) campo.value = '';
	if (len == 1) campo.value = '0'+ separador_decimal + '0' + aux;
	if (len == 2) campo.value = '0'+ separador_decimal + aux;

	if (len > 2) {
		aux2 = '';

		for (j = 0, i = len - 3; i >= 0; i--) {
			if (j == 3) {
				aux2 += separador_milhar;
				j = 0;
			}
			aux2 += aux.charAt(i);
			j++;
		}

		campo.value = '';
		len2 = aux2.length;
		for (i = len2 - 1; i >= 0; i--)
		campo.value += aux2.charAt(i);
		campo.value += separador_decimal + aux.substr(len - 2, len);
	}

	return false;
}

/*---------------------------------------------------------------------
Não deixa ver o nome dos fontes
Exemplo:
---------------------------------------------------------------------*/
function bloquear()
{
	document.onmouseover = escondeStatus;
	document.onmouseout = escondeStatus;
}

/*---------------------------------------------------------------------
Utilizada no menu
Exemplo: 
	linkMenu("teste.php")
---------------------------------------------------------------------*/
function linkMenu(url)
{
	window.location = url;	
}

/*---------------------------------------------------------------------
Validação de e-mail
Exemplo: 
	if (!validaEmail("txtEmail"))
---------------------------------------------------------------------*/
function validaEmail(obj)
{
	var txt = document.getElementById(obj).value;
  
	if ( (txt.length != 0) && (txt.indexOf("@") < 1) )
	{
		return false;
	}else{
		return true;
	}  
}

/*---------------------------------------------------------------------
Abre o ChangeLog
Exemplo: 
	novidades()
---------------------------------------------------------------------*/

function novidades()
{
	window.location = "novidades.php";
}

/*---------------------------------------------------------------------
Formatar campo Data com máscara
Exemplo: 
	onkeypress="return dateMask(this, event);" 
---------------------------------------------------------------------*/
function mascaraData(inputData, e)
{
	if(document.all) // Internet Explorer
		var tecla = event.keyCode;
	else //Outros Browsers
		var tecla = e.which;

	if(tecla >= 47 && tecla < 58)
	{ 
		// numeros de 0 a 9 e "/"
		var data = inputData.value;
		if (data.length == 2 || data.length == 5)
		{
			data += '/';
			inputData.value = data;
		}
	}
	else if(tecla == 8 || tecla == 0) // Backspace, Delete e setas direcionais(para mover o cursor, apenas para FF)
		return true;
	else
		return false;
}

/*---------------------------------------------------------------------
Alterna com uma linha branca e outra cinza nas tabelas
Exemplo: 
	alternaRelatorio('idTabela')
---------------------------------------------------------------------*/
function alternaRelatorio(id)
{
	if (id != undefined)
	{
		if (document.getElementById(id)) //Se existe o ID na tela.
		{
			//Alterna as cores das linhas		
			for (var x = 1; x <= (document.getElementById(id).rows.length - 1 ); x += 2)
			{
				document.getElementById(id).rows[x].className = "alternaRelatorio";
			}
		}
	}else{
		if (document.getElementById("ListTable")) //Se existe o ID na tela.
		{
			//Alterna as cores das linhas		
			for (var x = 1; x <= (document.getElementById("ListTable").rows.length - 1 ); x += 2)
			{
				document.getElementById("ListTable").rows[x].className = "alternaRelatorio";
			}
		}
	}
}

function loading()
{
	$.blockUI({ message: '<img src="images/poker.gif"><h2><label>Aguarde..</label></h2>' });	
}

function unloading()
{
	setTimeout(function(){$.unblockUI();},300);	
}

/*---------------------------------------------------------------------
valida campos númericos
Exemplo: 
	onkeypress="isNumeric(event);" 
---------------------------------------------------------------------*/
function IsNumeric(campo,strString)
 {
	var strValidChars = "0123456789.-";
	var strChar;
	var blnResult = true;
	
	if (strString.length == 0) return false;

	//  test strString consists of valid characters listed above
	for (i = 0; i < strString.length && blnResult == true; i++)
	{
	strChar = strString.charAt(i);
	if (strValidChars.indexOf(strChar) == -1)
	{
	blnResult = false;
	}
	}
	return blnResult;
   
}



/*---------------------------------------------------------------------
Desabilita os campos que o usuário não tem permissão
Exemplo: 
	verificaPermissao('U')
---------------------------------------------------------------------*/
function verificaPermissao(nivel)
{
	if (nivel == "U")
	{
		button = document.getElementsByTagName("button");
		
		if (button != null)
		{
			for (i=0; i < button.length; i++)
			{ 
				if (button[i].getAttribute("moderador") != null)
				{
					button[i].disabled = true;
				}
			}
		}	
		
		a = document.getElementsByTagName("a");		
		
		if (a != null)
		{
			for (i=0; i < a.length; i++)
			{ 
				if (a[i].getAttribute("moderador") != null)
				{
					a[i].disabled = true;					
				}
			}
		}			
	}
}
