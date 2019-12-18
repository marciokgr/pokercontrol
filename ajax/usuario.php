<?php
header("Content-Type: text/html; charset=iso-8859-1");

require_once('../objetos/conexao.php');
require_once('../objetos/log.php');
session_start();

$sOrigem = addslashes($_GET["origem"]);

switch ($sOrigem)
{
	case "lista": lista(); break;
	case "validaLogin": validaLogin(); break;
	case "validaExclusao": validaExclusao(); break;
	case "cadastra": cadastra(); break;
	case "busca": busca(); break;
	case "altera": altera(); break;
	case "exclui": exclui(); break;
}

function lista()
{
	$oConexao = new Conexao();
	
	$sSQL = "SELECT ".
				"ID, ".
				"Nome, ".
				"ifnull(Email, '&nbsp;') as 'Email', ".
				"ifnull(Login, '&nbsp;') as 'Login', ".
				"ifnull(Tipo, '&nbsp;') as 'Tipo' ".
			"FROM ".
				"Usuario ".
			"WHERE ".
				"Tipo <> 'A' ".
			"ORDER BY ".
				"Nome;";
	$rsUsuario = $oConexao->sql($sSQL);
	//echo $sSQL;
	$sRetorno = "";
	
	while($lsUsuario = mysql_fetch_array($rsUsuario))
	{
		$sRetorno .= "<tr>".
						"<td><label>" . $lsUsuario['Nome'] . "</label></td>".
						"<td><label>" . $lsUsuario['Email'] . "</label></td>".
						"<td><label>" . $lsUsuario['Login'] . "</label></td>".
						"<td><label>" . getNivel($lsUsuario['Tipo']) . "</label></td>".
						"<td><a href='#' onclick='alteraUsuario(". $lsUsuario['ID'] .");' >Alterar</a></td>".
						"<td><a href='#' onclick='excluiUsuario(". $lsUsuario['ID'] .");'>Excluir</a></td>".
					"</tr>";
	}
	
	if ($sRetorno != "")
	{
		$sRetorno = "<table id='ListTable'>".
						"<tr class='cabecalhoRelatorio'>".
							"<td style='width:200px;'><label><b>Nome</b></label></td>".
							"<td style='width:200px;'><label><b>E-mail</b></label></td>".
							"<td style='width:100px;'><label><b>Login</b></label></td>".
							"<td style='width:75px;'><label><b>Nivel</b></label></td>".
							"<td align=center><img src='images/editar.png'></td>".
							"<td align=center><img src='images/delete.png'></td>".
						"</tr>". 	
						$sRetorno .
					"</table>";
					
	}
	else
		$sRetorno = "&nbsp;";
	
	$oConexao->fechar();
	
	// Retorno AJAX
	echo $sRetorno;
}

//Criada por Rafael 06/08/2010
function validaLogin()
{
	$sLogin = addslashes($_GET["login"]);
	$sID = addslashes($_GET["id"]);
	
	$sRetorno = "";
	
	$oConexao = new Conexao();
	
	$sSQL = "SELECT ID FROM Usuario WHERE Login = '". $sLogin ."' AND ID != ". $sID;
	$rsUsuario = $oConexao->sql($sSQL);
	
	$oConexao->fechar();
	
	$sRetorno = mysql_num_rows($rsUsuario);
	
	// Retorno AJAX
	echo $sRetorno;
}

//Criada por Márcio 10/08/2010
function validaExclusao()
{
	$sID = addslashes($_GET["id"]);
	
	$sRetorno = "0";
	
	$oConexao = new Conexao();
	
	$sSQL = "SELECT IDUsuario FROM Participante WHERE IDUsuario = ". $sID .";";
	$rsUsuario = $oConexao->sql($sSQL);
	
	$oConexao->fechar();
	
	$sRetorno = mysql_num_rows($rsUsuario);
	
	// Retorno AJAX
	echo $sRetorno;
}

function cadastra()
{
	$sNome = addslashes($_GET["nome"]);
	$sEmail = addslashes($_GET["email"]);
	$sLogin = addslashes($_GET["login"]);
	$sSenha = addslashes($_GET["senha"]);
	$sNivel = addslashes($_GET["nivel"]);
	
	$oConexao = new Conexao();

	$sNome = "'". $sNome ."'";
	
	if ($sEmail == "")
		$sEmail = "null";
	else
		$sEmail = "'". $sEmail ."'";
	
	if ($sLogin == "")
		$sLogin = "null";
	else
		$sLogin = "'". $sLogin ."'";
	
	if ($sSenha == "")
		$sSenha = "null";
	else
		$sSenha = "'". md5($sSenha) ."'";
	
	if ($sNivel == "")
		$sNivel = "'U'";
	else
		$sNivel = "'". $sNivel ."'";
		
	$sSQL = "INSERT INTO Usuario (".
				"Nome, ".
				"Email, ".
				"Login, ".
				"Senha, ".
				"Tipo ".
			") VALUES (".
				$sNome .", ".
				$sEmail .", ".
				$sLogin .", ".
				$sSenha .", ".
				$sNivel .
			")";
	$oConexao->sql($sSQL);
	
	$sRetorno = $oConexao->id();
	
	$oConexao->fechar();
	
	//<Log>
	$oLog = new Log();
	$oLog->gravar("Usuario",$_SESSION['Nome'],1,"","",$sNome);
	//</Log>
	
	// Retorno AJAX
	echo $sRetorno;
}

function busca()
{
	$sID = addslashes($_GET["id"]);
	
	$sRetorno = "";
	
	$oConexao = new Conexao();
	
	$sSQL = "SELECT ".
				"ID, ".
				"Nome, ".
				"Email, ".
				"Login, ".
				"Tipo ".
			"FROM ".
				"Usuario ".
			"WHERE ".
				"ID = ". $sID .";";
	$rsUsuario = $oConexao->sql($sSQL);
	
	$iLinhas = mysql_num_rows($rsUsuario);
	
	if ($iLinhas > 0)
	{
		$lsUsuario = mysql_fetch_array($rsUsuario);
		
		$sRetorno .= $lsUsuario['ID'] . "||" . $lsUsuario['Nome'] . "||" . $lsUsuario['Email'] . "||" . $lsUsuario['Login'] . "||" . $lsUsuario['Tipo'];
	}
	
	$oConexao->fechar();
	
	// Retorno AJAX
	echo $sRetorno;
}

function altera()
{
	$sID = addslashes($_GET["id"]);
	$sNome = addslashes($_GET["nome"]);
	$sEmail = addslashes($_GET["email"]);
	$sLogin = addslashes($_GET["login"]);
	$sSenha = addslashes($_GET["senha"]);
	$sNivel = addslashes($_GET["nivel"]);
	
	$sNome = "'". $sNome ."'";	
	
	if ($sEmail == "")
		$sEmail = "null";
	else
		$sEmail = "'". $sEmail ."'";
	
	if ($sLogin == "")
		$sLogin = "null";
	else
		$sLogin = "'". $sLogin ."'";
	
	if ($sSenha == "")
		$sSenha = "";
	else
		$sSenha = " Senha = ". "'". md5($sSenha) ."', ";
	
	if ($sNivel == "")
		$sNivel = "'U'";
	else
		$sNivel = "'". $sNivel ."'";
	
	//<LOG>
	$oLog = new Log();

	//Nome
	$sDescricaoAtual = $oLog->getValorAtual("Usuario","Nome",$sID);
	$oLog->gravar("Usuario",$_SESSION['Nome'],2,"Nome",$sDescricaoAtual,$sNome);
	
	$sDescricaoAtual = $oLog->getValorAtual("Usuario","Email",$sID);
	$oLog->gravar("Usuario",$_SESSION['Nome'],2,"Email",$sDescricaoAtual,$sEmail);
	
	$sDescricaoAtual = $oLog->getValorAtual("Usuario","Login",$sID);
	$oLog->gravar("Usuario",$_SESSION['Nome'],2,"Login",$sDescricaoAtual,$sLogin);
	
	$sDescricaoAtual = $oLog->getValorAtual("Usuario","Tipo",$sID);
	$oLog->gravar("Usuario",$_SESSION['Nome'],2,"Tipo",$sDescricaoAtual,$sNivel);
	
	//</LOG>
	
	$oConexao = new Conexao();
	
	$sSQL = "UPDATE ".
				"Usuario ".
			"SET ".
				"Nome = ". $sNome .", ".
				"Email = ". $sEmail .", ".
				"Login = ". $sLogin .", ".
				$sSenha . 		
				"Tipo = ". $sNivel ." ".
			"WHERE ".
				"ID = ". $sID .";";
	$oConexao->sql($sSQL);
	
	$oConexao->fechar();
	
	// Retorno AJAX
	echo $sRetorno;
}

function exclui()
{
	$sID = addslashes($_GET["id"]);
	
	//<Log>
	$oLog = new Log();
	$sDescricaoAtual = $oLog->getValorAtual("Usuario","Nome",$sID);
	//</Log>
	
	$oConexao = new Conexao();
	
	$sSQL = "DELETE FROM Usuario WHERE ID = ". $sID .";";
	$oConexao->sql($sSQL);
	
	$oConexao->fechar();
	
	//<Log>	
	$oLog->gravar("Usuario",$_SESSION['Nome'],3,"",$sDescricaoAtual,"");
	//</Log>
	
	// Retorno AJAX
	echo $sRetorno;
}

function getNivel($sNivel)
{
	switch ($sNivel)
	{
		case "U" : 
		
			$sNivel = "Usuário";
			break;	
			
		case "M": 
		
			$sNivel = "Moderador";
			break;	
		
		default:
		
			$sNivel = "Usuário";
			break;
			
	}	
	
	return 	$sNivel;		
}
?>