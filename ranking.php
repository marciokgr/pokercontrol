<?php
	require_once('includes/validaSessao.php');
	$iSessaoID = session_id();
	$iUsuID = $_SESSION['UsuarioID'];
	$cUsuTipo = $_SESSION['UsuarioTipo'];
	$iAcessoID = $_SESSION['AcessoID'];
	$sNome = $_SESSION['Nome'];
	
	require_once('objetos/sitemap.php');	
	require_once('objetos/menu.php');

	$oSiteMap = new SiteMap();
	$oMenu = new Menu();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-br" lang="pt-br">
	<head>
    	<link rel="stylesheet" type="text/css" href="css/default.css"/>
        <link rel="shortcut icon" HREF="favicon.ico"> 
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title><?php echo $oMenu->getVersao()?> - Ranking</title>		
	</head>	
	<body>
     <div class="geral">
    	<?php $oMenu->createTop()?>        
		<?php $oSiteMap->getSiteMap($_SERVER['SCRIPT_NAME'])?>
        <?php $oMenu->createMenu()?>
            <div class="tituloPagina"><img src="images/ranking.png"/>&nbsp;<b>Ranking</b></div>
            <div id="conteudo" name="conteudo" class="divConteudo">
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                	<tr>
                    	<td width="50%">
                        	  <div id="Portal1" class="divPortalLeft">
                                <div id="Titulo" class="tituloItemPortal">&nbsp;<img src="images/atualizar.png" onclick="listaUltimoVenc()" title="Atualizar" style="cursor:pointer"/><label><b>&nbsp;&nbsp;&nbsp;Últimos Vencedores</b></label></div>
                                <div id="listagem" class="divConteudoItemPortal"  style="height:110px;">&nbsp;</div>
                            </div>   
                        </td>
                        <td width="50%">&nbsp;                        	                  	
                        </td>
                    </tr> 
                    <tr><td height="3"></td></tr>                  
               		<tr>
                    	<td colspan="2">
                        	  <div id="Portal1" class="divPortalLeft">
                                <div id="Titulo" class="tituloItemPortal">&nbsp;<img src="images/atualizar.png" onclick="listaRanking()" title="Atualizar" style="cursor:pointer"/><label><b>&nbsp;&nbsp;&nbsp;Ranking Geral do Poker</b></label></div>
                                <div id="listagemRanking" class="divConteudoItemPortal" style="height:230px;">&nbsp;</div>
                            </div>   
                        </td>                       
                    </tr>                    	
                </table>
                <a href="principal.php">Voltar</a>                    
            </div>
         <?php $oMenu->createBase()?>
        </div>
		<script type="text/javascript" src="scripts/include.js"></script>    
		<script type="text/javascript" src="scripts/jquery-min.js"></script>
		<script type="text/javascript" src="scripts/jquery.blockUI.js"></script>	
		<script type="text/javascript" src="scripts/ajax.js"></script>		    
        <script language="javascript">
			window.onload=function()
			{
				loading();
				
				listaUltimoVenc();	
				listaRanking("");
				
				unloading();
			};
			
			
			function listaUltimoVenc()
			{					
				sURL  = "ajax/ranking.php?origem=listaUltimoVenc";				
				enviaDadosAJAX(sURL, 'retornoListaLocal','divCarregando',false);
			}
			
			function retornoListaLocal(ret) {
				document.getElementById("listagem").innerHTML  = ret;
			}			
			
			function listaRanking(order)
			{					
				sURL  = "ajax/ranking.php?origem=rankingVitorias&order="+ order;				
				enviaDadosAJAX(sURL, 'retornoListaRanking','divCarregando',false);
			}
			
			function retornoListaRanking(ret)
			{
				document.getElementById("listagemRanking").innerHTML  = ret;
				alternaRelatorio()
			}			
		</script>
	</body>
</html>