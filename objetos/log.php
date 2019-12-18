<?php
require_once('../objetos/conexao.php');

Class Log
{	
	var $sRetorno = "";
	
	//Grava��o do LOG
	function gravar($sLocal,$sResponsavel,$sAcao,$sCampo,$sDe,$sPara)
	{			
		$oConexaoLog = new Conexao();
								
		if ($sLocal == "")
			$sLocal = "null";
		else
			$sLocal = "'". $sLocal ."'";	
		
		if ($sResponsavel == "")
			$sResponsavel = "null";
		else
			$sResponsavel = "'". $sResponsavel ."'";
		
		if ($sAcao == "")
			$sAcao = "null";
		else
			$sAcao = "'". $sAcao ."'";	
		
		if ($sCampo == "")
			$sCampo = "null";
		else
			$sCampo = "'". $sCampo ."'";	
			
		if ($sDe == "")
		{
			$sDe = "null";
			
		}else{
			
			if (strpos($sDe, "'") === false)
			{
				$sDe = "'". $sDe ."'";
			}
		}
		
		if ($sPara == "")
		{
			$sPara = "null";
			
		}else{
			
			if (strpos($sPara, "'") === false)
			{
				$sPara = "'". $sPara ."'";
			}
		}
		
		/*
			A��o	
				1 - Inclus�o
				2 - Altera��o
				3 - Exlus�o
		*/
		
		//No caso de altera��o. Se os valores forem diferentes grava.
		if ($sDe <> $sPara)
		{
			$sSQL = "INSERT INTO Log ".
						"(".						
							"Local,".
							"Responsavel,".
							"Acao,".
							"Campo,".
							"De,".
							"Para ".
						") ".
					"VALUES ".
						"(".
							$sLocal .",".
							$sResponsavel .",".
							$sAcao .",".
							$sCampo . ",".
							$sDe .",".
							$sPara ." ".
						")";
						
			$oConexaoLog->sql($sSQL);
		}
		
		$oConexaoLog->fechar();
		
		return "OK";
	}
	
	//Busca o valor atual quando altera��o e exclus�o
	function getValorAtual($sTabela,$sCampo,$sID)
	{
		$oConexaoLog = new Conexao();
		$sValorAtual = "";		

		$sSQL = "SELECT ".
					$sCampo . " campo ".
				"FROM ".
					$sTabela . " ".
				"WHERE ".
					"ID = ". $sID .";";
								
		$rsLog = $oConexaoLog->sql($sSQL);
		$iLinhas = mysql_num_rows($rsLog);

		if ($iLinhas > 0)
		{
			$lsLog = mysql_fetch_array($rsLog);		
			$sValorAtual = $lsLog[campo];
		}	

		$oConexaoLog->fechar();
		
		return $sValorAtual;
	}
}
?>