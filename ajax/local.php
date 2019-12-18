<?php
header("Content-Type: text/html; charset=iso-8859-1");

require_once('../objetos/conexao.php');
require_once('../objetos/log.php');
session_start();

$sOrigem = addslashes($_GET["origem"]);

switch ($sOrigem)
{
	case "lista": lista(); break;
	case "cadastra": cadastra(); break;
	case "busca": busca(); break;
	case "altera": altera(); break;
	case "exclui": exclui(); break;
	case "validaExclusao": validaExclusao(); break;
}

function lista()
{
	$oConexao = new Conexao();
	
	$sSQL = "select ".
				"ID, ".
				"descricao ".
			"from ".
				"Local ".
			"order by ".
				"descricao asc;";
	$rsLocalizacao = $oConexao->sql($sSQL);
	
	$sRetorno = "";
	
	while($lsLocalizacao = mysql_fetch_array($rsLocalizacao))
	{
		$sRetorno .= "<tr>".
						"<td style='width:180px;'><label>" . $lsLocalizacao[descricao] . "</label></td>".						
						"<td style='width:55px;'><a href='#' onclick='alteraLocal(". $lsLocalizacao[ID] .");'>Alterar</a></td>".
						"<td align=center> <a href='#' onclick='excluiLocal(". $lsLocalizacao[ID] .");'>Excluir</a></td>".
					"</tr>";
	}
	
	if ($sRetorno != "")
	{
		$sRetorno = "<table id='ListTable'>".
						"<tr class='cabecalhoRelatorio'>".
							"<td style='width:150px;'><label><b>Local</b></label></td>".
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

function cadastra()
{
	$sNome = addslashes($_GET["descricao"]);
	
	$oConexao = new Conexao();	
	
	$sNome = "'". $sNome ."'";
	
	$sSQL = "INSERT INTO Local".
			"(".
				"Descricao ".				
			") values (".
				$sNome ." ".				
			")";
	$oConexao->sql($sSQL);

	$sRetorno = $oConexao->id();
	
	$oConexao->fechar();
	
	//<Log>
	$oLog = new Log();
	$oLog->gravar("Local",$_SESSION['Nome'],1,"","",$sNome);
	//</Log>
	
	// Retorno AJAX
	echo $sRetorno;
}

function busca()
{
	$sID = addslashes($_GET["id"]);
	
	$sRetorno = "";
	
	$oConexao = new Conexao();
	
	$sSQL = "SELECT id,descricao FROM Local WHERE ID = ". $sID .";";
	$rsLocalizacao = $oConexao->sql($sSQL);
	
	$iLinhas = mysql_num_rows($rsLocalizacao);
	
	if ($iLinhas > 0)
	{
		$lsLocalizacao = mysql_fetch_array($rsLocalizacao);
		
		$sRetorno .= $lsLocalizacao[id] . "||" . $lsLocalizacao[descricao];

	}
	
	$oConexao->fechar();
	
	// Retorno AJAX
	echo $sRetorno;
}

function validaExclusao()
{
	$sID = addslashes($_GET["id"]);
	
	$sRetorno = "0";
	
	$oConexao = new Conexao();
	
	$sSQL = "SELECT IDLocal FROM Partida WHERE IDLocal = ". $sID .";";
	$rsLocal = $oConexao->sql($sSQL);
	
	$oConexao->fechar();
	
	$sRetorno = mysql_num_rows($rsLocal);
	
	// Retorno AJAX
	echo $sRetorno;
}

function altera()
{
	$sID = addslashes($_GET["id"]);
	$sDescricao = addslashes($_GET["descricao"]);
	$sDescricaoAtual = "";
	$iLinhas = 0;
	
	//<LOG>
	$oLog = new Log();
	$sDescricaoAtual = $oLog->getValorAtual("Local","Descricao",$sID);
	$oLog->gravar("Local",$_SESSION['Nome'],2,"Descricao",$sDescricaoAtual,$sDescricao);
	//</LOG>
	
	$oConexao = new Conexao();
		
	$sDescricao = "'". $sDescricao ."'";	

	$sSQL = "UPDATE ".
				"Local ".
			"SET ".
				"Descricao = ". $sDescricao ." ".
			"WHERE ".
				"ID = ". $sID .";";
				
	$oConexao->sql($sSQL);
	
	$oConexao->fechar();	

	// Retorno AJAX
	echo "OK";
}

function exclui()
{
	$sID = addslashes($_GET["id"]);
	$iLinhas = 0;

	//<LOG>
	$oLog = new Log();
	$sDescricaoAtual = $oLog->getValorAtual("Local","Descricao",$sID);
	$oLog->gravar("Local",$_SESSION['Nome'],3,"",$sDescricaoAtual,"");
	//</LOG>
	
	$oConexao = new Conexao();	
		
	//Exclusão	
	$sSQL = "DELETE FROM Local WHERE ID = ". $sID .";";
	$oConexao->sql($sSQL);
	
	$oConexao->fechar();
	
	// Retorno AJAX
	echo "OK";
}
?>