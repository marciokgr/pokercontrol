<?php
	Class FormataSQL
	{
		function formata($sValor, $cTipo)
		{
			$sRetorno = "";
			
			if ($sValor == "")
				$sRetorno = "null";
			else
			{
				if ($cTipo == "s")
					$sRetorno = "'" . $sValor . "'";
				
				if ($cTipo == "i")
					$sRetorno = $sValor;
				
				
				if ($cTipo ==  "d")
				{
					list($d, $m, $a) = preg_split('/\//', $sValor);
					$sRetorno = sprintf("'%4d-%02d-%02d'", $a, $m, $d);
				}
			}
			
			return $sRetorno;
		}
	}
?>