<?php
//Menu
Class Menu
{		
	var $retorno = "";
	var $estilo = "";	

	//Cria o menu do site
	function createMenu()
	{	

		$retorno = "";
		$carregando = "";	

		$estilo = " nowrap=true width='120' valign=middle class='mouseOut' onmouseover=this.className='mouseOver' onmouseout=this.className='mouseOut' style=cursor:pointer; ";


		$retorno = "<div id='menu' class='menu'>".
					   "<table width='100%'>" .
							"<tr>" . 
								"<td ". $estilo ." onclick=linkMenu('principal.php'); >&nbsp;<img src='images/home.png'>&nbsp;<label><b>Principal</b></label></td>".
							"</tr>".
							"<tr>" . 
								"<td ". $estilo ." onclick=linkMenu('usuario.php');>&nbsp;<img src='images/user.png'>&nbsp;<label><b>Usuário</b></label></td>".
							"<tr>" . 
							 "<tr>" . 
								"<td ". $estilo ." onclick=linkMenu('local.php');>&nbsp;<img src='images/local.png'>&nbsp;<label><b>Local</b></label></td>".
							 "<tr>" . 
							 "<tr>" . 
								"<td ". $estilo ." onclick=linkMenu('jogo.php');>&nbsp;<img src='images/jogos.png'>&nbsp;<label><b>Jogos</b></label></td>".
							 "<tr>" . 
								"<td ". $estilo ." onclick=linkMenu('ranking.php');>&nbsp;<img src='images/ranking.png'>&nbsp;<label><b>Ranking</b></label></td>".
							"</tr>".
							 "<tr>" . 
								"<td ". $estilo ." onclick=linkMenu('jogo.php');>&nbsp;<img src='images/jogos.png'>&nbsp;<label><b>Jogos</b></label></td>".
							 "<tr>" . 
							"<tr>" . 
								"<td ". $estilo ." onclick=linkMenu('regras.php');>&nbsp;<img src='images/jogos.png'>&nbsp;<label><b>Regras</b></label></td>".
							"</tr>".
							"<tr>" . 
								"<td ". $estilo ." onclick=linkMenu('log.php');>&nbsp;<img src='images/log.png'>&nbsp;<label><b>Log</b></label></td>".
							"</tr>".
							"<tr>" . 
								"<td ". $estilo ." onclick=linkMenu('novidades.php');>&nbsp;<img src='images/note.png'>&nbsp;<label><b>Novidades</b></label></td>".
							"</tr>".
							"<tr>" . 
								"<td ". $estilo ." onclick=linkMenu('acessos.php');>&nbsp;<img src='images/acesso.png'>&nbsp;<label><b>Acessos</b></label></td>".
							"</tr>".
							"<tr>" . 
								"<td ". $estilo ." onclick=linkMenu('logoff.php?sair=true');>&nbsp;<img src='images/sair.png'>&nbsp;<label><b>Sair</b></label></td>".
							"</tr>".
						"</table>".
					"</div>";		

		echo $retorno;
	}	

	

	function getVersao()
	{

		return "POKER Control 1.0.19 ";	
	}

	

	function getData()
	{

		return "Data 18/12/2019";	
	}

	

	function createBase()
	{

		$retorno = "";
		//$retorno = "0800net";
		$retorno = '<div class="basePagina"><img src="images/note.png" title="Novidades da Versão" onclick="novidades();" style="cursor:pointer">&nbsp;&nbsp;<b>'. $this->getVersao() .' - '. $this->getData() .'</b></div>';
		echo $retorno;	

	}	

	function createTop()
	{

		$retorno = "";
		//$retorno = "0800net";
		$retorno = '<div id="topo" class="topo"><img src="images/topo.gif" title="'. $this->getVersao() .'"/></div>';		

		echo $carregando . $retorno;		

	}	
}
?>