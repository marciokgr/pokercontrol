<?php
	//Sessão
	require_once('includes/validaSessao.php');
	$iSessaoID = session_id();
	$iUsuID = $_SESSION['UsuarioID'];
	$cUsuTipo = $_SESSION['UsuarioTipo'];
	$iAcessoID = $_SESSION['AcessoID'];
	$sNome = $_SESSION['Nome'];	
	$sNivel = $_SESSION['Nivel'];
	
	//Includes
	require_once('objetos/sitemap.php');	
	require_once('objetos/menu.php');
	
	//Objetos
	$oSiteMap = new SiteMap();
	$oMenu = new Menu();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-br" lang="pt-br">
	<head>
    	<link rel="stylesheet" type="text/css" href="css/default.css"/>
        <link rel="shortcut icon" HREF="favicon.ico"> 
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title><?php echo $oMenu->getVersao()?> - Jogos</title>		
	</head>	
	<body>
    <div class="geral">
    	<?php $oMenu->createTop()?>        
		<?php $oSiteMap->getSiteMap($_SERVER['SCRIPT_NAME'])?>
        <?php $oMenu->createMenu()?>
            <div class="tituloPagina"><img src="images/jogos.png"/>&nbsp;<b>Jogos</b></div>
            <div id="conteudo" name="conteudo" class="divConteudo" >             
               <button id="btnNovo" style="margin-top:4px;width:85px;;" moderador onclick="novo();" accesskey="A"><u>N</u>ovo Jogo</button>                
                <div id="listagem" style="margin-top:10px; margin-bottom:10px;">&nbsp;</div>                
                <a href="principal.php">Voltar</a>                                                  
            </div>
         <?php $oMenu->createBase()?>
        </div>   	
		<script type="text/javascript" src="scripts/ajax.js"></script>
		<script type="text/javascript" src="scripts/include.js"></script>		
		<script type="text/javascript" src="scripts/jquery-min.js"></script>
		<script type="text/javascript" src="scripts/jquery.blockUI.js"></script>	
		<script type="text/javascript">
			window.onload = function()
			{
				buscaJogos();
				
				//Desabilita Campos Cadastro quando usuário.
				verificaPermissao("<?=$sNivel?>"); 
			};
			
			function buscaJogos()
			{
				loading();
				
				sURL  = "ajax/jogo.php?origem=lista";				
				enviaDadosAJAX(sURL, 'exibeJogos',undefined, false);
			}
			
			function exibeJogos(retorno)
			{
				document.getElementById("listagem").innerHTML  = retorno;				
				alternaRelatorio();
				unloading();
			}
			
			function novo()
			{
				window.location.href = "jogoCadastra.php"	
			}
			
			function excluiJogo(id)
			{
				if ("<?=$sNivel?>" == "U")
				{
					alert("Você não tem permissão para exclusão de Jogos");
					return false;
					
				}else{
					if (confirm("Deseja realmente excluir esta partida?\nVocê perderá todo o histórico e estará alterando o Ranking geral do Poker."))
					{
						loading();
						sURL  = "ajax/jogo.php?origem=excluir&id="+ id;				
						enviaDadosAJAX(sURL, 'buscaJogos',undefined, false);
						unloading();
					}
				}
			}			
			
		</script>		
	</body>
</html>