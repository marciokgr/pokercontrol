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
	
	$sSQL = "select ".
				"A.ID, ".
				"A.DataLogin, ".
				"IFNULL(A.DataLogoff,'') DataLogoff, ".
				"U.Nome ".
			"from ".
				"Acesso A ".
				"inner join Usuario U on (U.ID = A.IDUsuario)".
			"order by ".
				"A.DataLogin Desc;";
	$rsAcesso = $oConexao->sql($sSQL);
	
	$sRetorno = "";
	
	while($lsAcesso = mysql_fetch_array($rsAcesso))
	{
		$sRetorno .= "<tr>".
						"<td style='width:150px;'><label>" . date('d/m/y H:i:s', strtotime($lsAcesso[DataLogin])) . "</label></td>".
						"<td style='width:150px;'><label>" . trataValor($lsAcesso[DataLogoff]) . "</label></td>".						
						"<td style='width:150px;'><label>" . $lsAcesso[Nome] . "</label></td>".						
					"</tr>";
	}
	
	if ($sRetorno != "")
	{
		$sRetorno = "<table id='ListTable'>".
						"<tr class='cabecalhoRelatorio'>".
							"<td style='width:150px;'><label><b>Data Login</b></label></td>".
							"<td style='width:150px;'><label><b>Data Logoff</b></label></td>".	
							"<td style='width:150px;'><label><b>Usuário</b></label></td>".
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

function isDate($date)
{	
	$date_array = explode("-",$date);

    if(count($date_array) != 3) 
		return false;
	else
		return true;
}

function trataValor($str)
{
	$sRetorno = $str;
	
	if (isDate($str))	
		$sRetorno = date('d/m/y H:i:s', strtotime($str));
	else
		$sRetorno = "Timeout";	

	return $sRetorno;
}
?>