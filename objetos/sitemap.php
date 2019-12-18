<?php
//Site Map
Class SiteMap
{		
	var $retorno = "";
	var $str = "";
	var $css = "";
	
	//Retorna o mapa do site
	function getSiteMap($sPagina)
	{	
		//Padrão
		$str = "<a href='index.php'>Principal</a>"; 
		
		switch (strtolower($sPagina))
		{
			//Index
			case "/poker/index.php":
			
				$str = "<a href='index.php'><label><b>Login</b></label></a>"; 
				break;
			
			//Index
			case "/poker/principal.php":
			
				$str = "<a href='principal.php'><label><b>Principal</b></label></a>"; 
				break;
				
				
			//Usuario
			case "/poker/usuario.php":
				
				$str = "<a href='principal.php'<label>Principal</label></a> / <a href='usuario.php'><label><b>Usuários</b></label></a>"; 
				break;
			
			//Jogo
			case "/poker/jogo.php":
			
				$str = "<a href='principal.php'><label>Principal</label></a> / <a href='jogo.php'><label><b>Jogos</b></label></a>"; 
				break;	
			
			//Novo Jogo
			case "/poker/jogocadastra.php":
			
				$str = "<a href='principal.php'><label>Principal</label></a> / <a href='jogo.php'><label>Jogos</label></a> / <a href='jogoCadastra.php'><label><b>Novo Jogo</b></label></a>"; 
				break;	
				
			//Ranking
			case "/poker/ranking.php":
			
				$str = "<a href='principal.php'><label>Principal</label></a> / <a href='ranking.php'><label><b>Ranking</b></label></a>"; 
				break;	
			
			//Novidades
			case "/poker/novidades.php":
			
				$str = "<a href='principal.php'><label>Principal</label></a> / <a href='novidades.php'><label><b>Novidades</b></label></a>"; 
				break;	
				
		}

		$retorno = "<div class='sitemap'>&nbsp;<label>Você está em:</label> " . $str . " <img src='images/note.png' title='Novidades da Versão' align='right' onclick='novidades();' style='cursor:pointer'>&nbsp;</div>";		
		
		echo $retorno;
	}	
}
?>