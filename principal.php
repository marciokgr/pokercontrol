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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-br" lang="pt-br">	
	<head>
    	<link rel="stylesheet" type="text/css" href="css/default.css"/>
        <link rel="shortcut icon" HREF="favicon.ico"> 
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title><?php echo $oMenu->getVersao()?> - Principal</title>        
		
	</head>	
	<body oncontextmenu="return false;">
    <div class="geral" >    	
    	<?php $oMenu->createTop()?>        
		<?php $oSiteMap->getSiteMap($_SERVER['SCRIPT_NAME'])?>
        <?php $oMenu->createMenu()?>
            <div class="tituloPagina"><img src="images/home.png"/>&nbsp;<b>Principal</b></div>
            <div id="conteudo" name="conteudo" class="divConteudo">
            	<table border="0" cellpadding="0" cellspacing="0" width="100%">
                	<tr>
                    	<td width="50%">
                        	<div id="Portal1" class="divPortalLeft">
                                <div id="Titulo" class="tituloItemPortal"><label><b>&nbsp;Acessos</b></label></div>
                                <div id="Conteudo1" class="divConteudoItemPortal"></div>
                            </div>    
                        </td>
                        <td width="50%">
                        	<div id="Portal2" class="divPortalRight">
                                <div id="Titulo" class="tituloItemPortal"><label><b>&nbsp;PrÃ³xima Partida</b></label></div>
                                <div id="Conteudo2" class="divConteudoItemPortal">                                	
                                </div>
                            </div>                        	
                        </td>
                    </tr>
                    <tr><td colspan="2" height="3"></td></tr> 
                    <tr>
                    	<td width="50%">
                        	<div id="Portal3" class="divPortalLeft">
                                <div id="Titulo" class="tituloItemPortal"><label><b>&nbsp;Ãšltimos Vencedores</b></label></div>
                                <div id="Conteudo3" class="divConteudoItemPortal">                                	
                                </div>
                            </div>    
                        </td>
                        <td width="50%"><!--
                        	<div id="Portal4" class="divPortalRight">
                                <div id="Titulo" class="tituloItemPortal"><label><b>&nbsp;Título 4</b></label></div>
                                <div id="Conteudo4" class="divConteudoItemPortal">
                                	Texto Aqui
                                </div>
                            </div>-->                        	
                        </td>
                    </tr>                         
                </table>                        
        </div>        
        <?php $oMenu->createBase()?> 	
		<script type="text/javascript" src="scripts/include.js"></script>
		<script type="text/javascript" src="scripts/ajax.js"></script> 
		<script type="text/javascript" src="scripts/include.js"></script>
		<script type="text/javascript" src="scripts/jquery-min.js"></script>
		<script type="text/javascript" src="scripts/jquery.blockUI.js"></script>	
        <script language="javascript">
			window.onload=function()
			{
				loading();
				
				listaDadosVisitante();
				listaProximaPartida();				
				listaUltimoVenc();					
				
				unloading();
			};			
			
			function listaUltimoVenc()
			{					
				sURL  = "ajax/ranking.php?origem=listaUltimoVenc";				
				enviaDadosAJAX(sURL, 'retornoListaLocal',undefined,false);
			}
			
			function retornoListaLocal(ret) 
			{
				document.getElementById("Conteudo3").innerHTML  = ret;
			}			
			
			
			function listaDadosVisitante()
			{					
				sURL  = "ajax/principal.php?origem=listaAcesso&usuid=<?=$_SESSION["UsuarioID"]?>";				
				enviaDadosAJAX(sURL, 'retornoListaAcesso',undefined,false);
			}
			
			function retornoListaAcesso(ret) 
			{
				document.getElementById("Conteudo1").innerHTML  = ret;
			}		

			function listaProximaPartida()
			{					
				sURL  = "ajax/jogo.php?origem=proximaPartida";				
				enviaDadosAJAX(sURL, 'retornoListaProximaPartida',undefined,false);
			}
			
			function retornoListaProximaPartida(ret) 
			{
				document.getElementById("Conteudo2").innerHTML  = ret;
			}		

		</script>
	</body>
</html>