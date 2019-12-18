<?php
header("Content-Type: text/html; charset=iso-8859-1");

require_once('../objetos/conexao.php');

$sOrigem = addslashes($_GET["origem"]);

switch ($sOrigem)
{
	case "lista": lista(); break;
}

function lista()
{
	$oConexao = new Conexao();
	
	$sSQL = "SELECT ".
				"ID, ".
				"Data, ".
				"Responsavel, ".
				"Local, ".
				"Acao, ".
				"IFNULL(Campo,'-') Campo, ".
				"IFNULL(De,'-') De,".
				"IFNULL(Para,'-') Para ".
			"FROM ".
				"Log ".
			"ORDER BY ".
				"Data Desc;";
	$rsLog = $oConexao->sql($sSQL);
	
	$sRetorno = "";
	
	while($lsLog = mysql_fetch_array($rsLog))
	{
		$sRetorno .= "<tr>".
						"<td style='width:140px;' nowrap><label>" . date('d/m/Y h:m', strtotime($lsLog[Data])) . 		"</label></td>".						
						"<td style='width:90px;' nowrap><label>" . $lsLog[Local] . 			"</label></td>".
						"<td style='width:65px;' nowrap><label>" . getAcao($lsLog[Acao]) .	"</label></td>".
						"<td style='width:150px;' nowrap><label>" . $lsLog[Responsavel] . 		"</label></td>".
						"<td style='width:150px;' nowrap><label>" . $lsLog[Campo] . 			"</label></td>".
						"<td style='width:150px;' nowrap><label>" . trataValor($lsLog[De]) . 	"</label></td>".
						"<td style='width:150px;' nowrap><label>" . trataValor($lsLog[Para]). 	"</label></td>".
					"</tr>";
	}
	
	if ($sRetorno != "")
	{
		$sRetorno = "<table id='ListTable'>".
						"<tr class='cabecalhoRelatorio'>".
							"<td style='width:140px;' nowrap><label><b>Data</b></label></td>".
							"<td style='width:90px;' nowrap><label><b>Local</b></label></td>".
							"<td style='width:65px;' nowrap><label><b>Ação</b></label></td>".
							"<td style='width:150px;' nowrap><label><b>Responsavel</b></label></td>".
							"<td style='width:150px;' nowrap><label><b>Campo</b></label></td>".
							"<td style='width:150px;' nowrap><label><b>De</b></label></td>".
							"<td style='width:150px;' nowrap><label><b>Para</b></label></td>".
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

//Retorn a string da ação.
function getAcao($iAcao)
{
	$sRetorno = "";
	
	switch ($iAcao)
	{
		case 1 : 
		
			$sRetorno = "Incluiu";
			break;	
			
		case 2: 
		
			$sRetorno = "Alterou";
			break;
			
		case 3: 
		
			$sRetorno = "Excluiu";
			break;	
			
	}
	
	return $sRetorno;
}

function trataValor($str)
{
	$sRetorno = $str;
	
	if (isDate($str))
	{
		$sRetorno = date('d/m/Y', strtotime($str));
	}	

	return $sRetorno;
}

function isDate($date)
{	
	$date_array = explode("-",$date);

    if(count($date_array)!=3) 
		return false;
	else
		return true;
}
?>