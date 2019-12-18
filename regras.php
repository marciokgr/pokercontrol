<?php
	//Sessão
	require_once('includes/validaSessao.php');
	$iSessaoID = session_id();
	$iUsuID = $_SESSION['UsuarioID'];
	$cUsuTipo = $_SESSION['UsuarioTipo'];
	$iAcessoID = $_SESSION['AcessoID'];
	$sNome = $_SESSION['Nome'];	
	
	//Includes
	require_once('objetos/sitemap.php');
	require_once('objetos/menu.php');	
	//Objetos
	$oSiteMap = new SiteMap();
	$oMenu = new Menu();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-br" lang="pt-br">
	<head>
    	<link rel="stylesheet" type="text/css" href="css/default.css"/>
        <link rel="shortcut icon" HREF="favicon.ico">
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title><?php echo $oMenu->getVersao()?> - Regras</title>
		<script type="text/javascript" src="scripts/include.js"></script>
	</head>	<body oncontextmenu="return false;">
    <div class="geral">
    	<?php $oMenu->createTop()?>
		<?php $oSiteMap->getSiteMap($_SERVER['SCRIPT_NAME'])?>
        <?php $oMenu->createMenu()?>
            <div class="tituloPagina"><img src="images/jogos.png"/>&nbsp;<b>Regras Poker</b></div>
            <div id="conteudo" name="conteudo" class="divConteudo">
                <label>
					<img src="images/poker_cola.PNG">               </label>
            </div>
        <?php $oMenu->createBase()?> 
        </div>
	</body>
</html>