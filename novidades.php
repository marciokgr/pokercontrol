<?php
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-br" lang="pt-br">
	<head>
    	<link rel="stylesheet" type="text/css" href="css/default.css"/>
        <link rel="shortcut icon" HREF="favicon.ico">
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title><?php echo $oMenu->getVersao()?> - Novidades</title>
        <!-- Includes -->
		<script type="text/javascript" src="scripts/include.js"></script>
	</head>	<body oncontextmenu="return false;">
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
					&nbsp; Build 17 (09/06/2016) - Criado mensagem de carregamento (Márcio)<br>					&nbsp; Build 17 (09/06/2016) - Reativação e Correção de pequenos erros (Márcio)<br>
                	&nbsp; Build 16 (22/10/2010) - Criado a coluna saldo no ranking (Márcio)<br>
                	&nbsp; Build 15 (23/09/2010) - Ao informar um vencedor e informar a quantidade de fichas ao marcar o segundo vencedor este já vem com o restante das fichas informado (Márcio)<br>                	&nbsp; Build 14 (17/09/2010) - Corrigido o problema no ranking aproveitamento, só deve contar partidas que participou (Márcio)<br>                	&nbsp; Build 13 (26/08/2010) - Padronizado a versão (Márcio)<br>                	&nbsp; Build 12 (25/08/2010) - Corrigido a função de máscara de data (Márcio)<br>                	&nbsp; Build 11 (24/08/2010) - Criado o Log de Alteração (Márcio)<br>                	&nbsp; Build 10 (23/08/2010) - Criado o nível de Acesso (Usuário Readonly) (Márcio)<br>                	&nbsp; Build 10 (23/08/2010) - Criado a exclusão de Partida (Márcio)<br>                	&nbsp; Build 09 (20/08/2010) - Criado o Ranking Geral do Poker (Márcio)<br>                	&nbsp; Build 08 (18/08/2010) - Corrigido um erro na edição do jogo (Márcio)<br>                	&nbsp; Build 08 (18/08/2010) - Criado os botões Novo em todos os cadastros (Márcio)<br>	                &nbsp; Build 08 (18/08/2010) - Criado o próxima partida que traz o resumo da próxima partida cadastrada (Márcio)<br>                	&nbsp; Build 07 (18/08/2010) - Criado o resumo de acessos, última partida e última vitória (Márcio)<br>                	&nbsp; Build 06 (10/08/2010) - Criado o CSS para exibição de dados "AlternaRelatorio" (Márcio)<br>                	&nbsp; Build 06 (10/08/2010) - Validação de exclus de usuários (Márcio)<br>                	&nbsp; Build 06 (10/08/2010) - Validação de exclusão de Local (Márcio)<br>                	&nbsp; Build 06 (10/08/2010) - Alterado o Carregando.. (Márcio)<br>	                &nbsp; Build 05 (06/08/2010) - Criado a estrutura de Resumo do Site (Márcio)<br>                	&nbsp; Build 04 (06/08/2010) - Corrigido o ranking. último Vencedores (Márcio)<br>                	&nbsp; Build 03 (05/08/2010) - Criado a Criptografia das senhas dos usuários (MD5) (Rafael)<br />                	&nbsp; Build 03 (05/08/2010) - Criado o Resumo dos vencedores e valor ganho (Márcio)<br />                	&nbsp; Build 03 (05/08/2010) - Criado a alteração de Jogos (Rafael/Marcio)<br />                	&nbsp; Build 02 (04/08/2010) - Implementado o logoff (Rafael)<br />                	&nbsp; Build 01 (03/08/2010) - Criada a formatação de campos valores no cadastro de Jogos (Márcio)<br />                	&nbsp; Build 01 (03/08/2010) - Criada a validação de e-mail no cadastro de usuários (Márcio)<br />                	&nbsp; Build 01 (03/08/2010) - Criado o relatório de acessos (Márcio)<br />                    &nbsp; Build 01 (03/08/2010) - Criado o novidades da versão (Isto =) ) (Márcio)<br />               </label>
            </div>
        <?php $oMenu->createBase()?> 
        </div>
	</body>
</html>