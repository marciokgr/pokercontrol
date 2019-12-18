<?php
header("Content-Type: text/html; charset=iso-8859-1");

require_once('../objetos/conexao.php');
require_once('../objetos/sessao.php');

$sOrigem = addslashes($_GET["origem"]);

switch ($sOrigem) {
	case "logon": logon(); break;
}

function logon()
{
	$sLogin = addslashes($_GET["login"]);
	$sSenha = addslashes($_GET["senha"]);
	
	$oConexao = new Conexao();
	$oSessao = new Sessao();
	
	$sSQL = "SELECT ".
				"ID, ".
				"Tipo, ".
				"Nome ".				
			"FROM ".
				"Usuario ".
			"WHERE ".
				"Login = '". $sLogin ."' and ".
				"Senha = '". md5($sSenha) ."'";
	$rsUsuario = $oConexao->sql($sSQL);
	
	$iLinhas = mysql_num_rows($rsUsuario); 
	if ($iLinhas > 0)
	{
		$aLista = mysql_fetch_array($rsUsuario);
		$iUsuID = $aLista[ID];
		$cUsuTipo = $aLista[Tipo];
		$sNome = $aLista[Nome];		
		
		$sSQL = "INSERT INTO Acesso ".
					"(IDUsuario) ".
				"VALUES ".
					"('". $iUsuID ."')";
		$oConexao->sql($sSQL);
		
		$iAcessoID = $oConexao->id();
		
		$oSessao->iniciaSessao($iUsuID, $cUsuTipo, $iAcessoID,$sNome);
		
		$iRetorno = $iUsuID;
	} else {
		$iRetorno = 0;
	}
	
	// Retorno AJAX
	echo $iRetorno;
	
	$oConexao->fechar();
}		

?>