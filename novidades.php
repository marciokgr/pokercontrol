﻿<?php
	//Sess�o
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<head>
    	<link rel="stylesheet" type="text/css" href="css/default.css"/>
        <link rel="shortcut icon" HREF="favicon.ico">
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title><?php echo $oMenu->getVersao()?> - Novidades</title>
        <!-- Includes -->
		<script type="text/javascript" src="scripts/include.js"></script>
	</head>
    <div class="geral">
    	<?php $oMenu->createTop()?>
		<?php $oSiteMap->getSiteMap($_SERVER['SCRIPT_NAME'])?>
        <?php $oMenu->createMenu()?>
            <div class="tituloPagina"><img src="images/note.png"/>&nbsp;<b>Novidades</b></div>
            <div id="conteudo" name="conteudo" class="divConteudo">
                <label>	
					&nbsp; Build 19 (18/12/2019) - Correções erros para funcionar no Azure (Márcio)<br>
					&nbsp; Build 18 (21/06/2016) - Ajustes para funcionamento Microsoft Azure (Márcio)<br>
					&nbsp; Build 18 (21/06/2016) - Corrigido cadastro do Usuário que não permitia edição de Usuário (Márcio)<br>
					&nbsp; Build 17 (09/06/2016) - Criado mensagem de carregamento (Márcio)<br>
                	&nbsp; Build 16 (22/10/2010) - Criado a coluna saldo no ranking (Márcio)<br>
                	&nbsp; Build 15 (23/09/2010) - Ao informar um vencedor e informar a quantidade de fichas ao marcar o segundo vencedor este já vem com o restante das fichas informado (Márcio)<br>
            </div>
        <?php $oMenu->createBase()?> 
        </div>
	</body>
</html>