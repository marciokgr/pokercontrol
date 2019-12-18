<?php
header("Content-Type: text/html; charset=iso-8859-1");

require_once('../objetos/conexao.php');

$sOrigem = addslashes($_GET["origem"]);

switch ($sOrigem)
{
	case "listaAcesso": listaAcesso(); break;	
}

function listaAcesso()
{
	$oConexao = new Conexao();
	$iQtdAcesso = 0;
	$sNome = "";
	$sRetorno = "";	
	$sUltimoAcesso = "";
	$sDataUltimaPartida = "";
	$iAux = 0;
	$sDataUltimaVitoria = "";
	$iVitorias = 0;
		
	$sUsuID = addslashes($_GET["usuid"]);

	$sSQL = "SELECT ".
				"COUNT(ID) totalAcesso ".
			"FROM  ".
				"Acesso ".				
			"WHERE ".
				"IDUsuario = ". $sUsuID;
				
	$rsAcesso = $oConexao->sql($sSQL);	
	
	//<Quantidade acessos>
	$lsAcesso = mysql_fetch_array($rsAcesso);
	$iQtdAcesso = $lsAcesso[totalAcesso];
	//</Quantidade acessos>
	
	//<Nome do usuario>
	$sSQL = "SELECT ".
				"Nome ".
			"FROM  ".
				"Usuario ".				
			"WHERE ".
				"ID = ". $sUsuID;
				
	$rsAcesso = $oConexao->sql($sSQL);
	$lsAcesso = mysql_fetch_array($rsAcesso);
	$sNome =  $lsAcesso[Nome];	
	//</Nome do usuario>	
	
	//<Ultimo Acesso>
	$sSQL = "SELECT ".
				"Nome ".
			"FROM  ".
				"Usuario ".				
			"WHERE ".
				"ID = ". $sUsuID;
				
	$rsAcesso = $oConexao->sql($sSQL);
	$lsAcesso = mysql_fetch_array($rsAcesso);
	$sNome =  $lsAcesso[Nome];	
	//</Ultimo Acesso>
	
	//<Ultima partida>
	$sSQL = "SELECT ".
				"P.Data Data ".
			"FROM  ".
				"Partida P ".
				"INNER JOIN Participante Par ON (Par.IDPartida = P.ID) ".
			"WHERE ".
				"Par.IDUsuario = ". $sUsuID . " ".
				"AND P.Data <= Now() ".
			"ORDER BY P.Data DESC ";
				
	$rsAcesso = $oConexao->sql($sSQL);

	$iAux = mysql_num_rows($rsAcesso);	
	
	if ($iAux > 0)
	{
		$lsAcesso = mysql_fetch_array($rsAcesso);
		$sDataUltimaPartida =  date('d/m/Y', strtotime($lsAcesso[Data]));	
		$sDataUltimaPartida = "<label>Sua última partida foi em <b>". $sDataUltimaPartida ."</b>.</label><br>";		
	}
	//<Ultima partida>
	
	//<Ultima vitória>
	$sSQL = "SELECT ".
				"P.Data Data ".
			"FROM  ".
				"Partida P ".
				"INNER JOIN Participante Par ON (Par.IDPartida = P.ID) ".
			"WHERE ".
				"Par.IDUsuario = ". $sUsuID . " ".
				"AND Par.Vencedor = 1 ".
			"ORDER BY P.Data DESC ";
				
	$rsAcesso = $oConexao->sql($sSQL);

	$iAux = mysql_num_rows($rsAcesso);	
	
	if ($iAux > 0)
	{
		$lsAcesso = mysql_fetch_array($rsAcesso);
		$sDataUltimaVitoria =  date('d/m/Y', strtotime($lsAcesso[Data]));	
		$sDataUltimaVitoria = "<label>Sua última vitória foi em <b>". $sDataUltimaVitoria ."</b>.</label><br>";		
	}else{
		$sDataUltimaVitoria = "<label>Você nunca venceu nenhuma partida.</label><br>";	
	}
	//<Ultima vitória>
	
	//<vitórias>
	$sSQL = "SELECT ".
				"COUNT(P.ID) total ".
			"FROM  ".
				"Partida P ".
				"INNER JOIN Participante Par ON (Par.IDPartida = P.ID) ".
			"WHERE ".
				"Par.IDUsuario = ". $sUsuID . " ".
				"AND Par.Vencedor = 1 ";
						
				
	$rsAcesso = $oConexao->sql($sSQL);

	$lsAcesso = mysql_fetch_array($rsAcesso);
	$iVitorias =  $lsAcesso[total];		
	
	//<vitórias>
	
	//Retorno
	$sRetorno = "<label>Olá <b>". $sNome."</b>, você acessou este site <b>". $iQtdAcesso . "</b> ". trataPlural("vez",$iQtdAcesso) .".</label><br>".
				"<label>Você já venceu <b>". $iVitorias ."</b> ". trataPlural("partida",$iVitorias) .".</label><br>".
				$sDataUltimaPartida .
				$sDataUltimaVitoria;
				
	
	$oConexao->fechar();
	
	// Retorno AJAX
	echo $sRetorno;
}

//Para tratar Plural
function trataPlural ($str, $qtd)
{
	if ($qtd > 1)
	{	
		if ($str = "vez")
		{
			$str = "vezes";
		}else{		
			$str =  $str . "s";
		}
	}
	
	return 	$str;
}
?>