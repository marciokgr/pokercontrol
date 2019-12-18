<?php
	//Includes
	require_once('objetos/sitemap.php');	
	require_once('objetos/menu.php');
	
	//Objetos
	$oSiteMap = new SiteMap();
	$oMenu = new Menu();
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-br" lang="pt-br">	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <link rel="stylesheet" type="text/css" href="css/default.css"/>
        <link rel="shortcut icon" HREF="favicon.ico"> 
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title><?php echo $oMenu->getVersao()?> - Login</title>
	</head>	
	<body>
    <div class="Geral">
	    <table cellpadding="0" cellspacing="0" width="100%">
        	<tr>
            	<td colspan="2"><?php $oMenu->createTop()?></td>
            </tr>
            <tr>
            	<td colspan="2">
                	<?php $oSiteMap->getSiteMap($_SERVER['SCRIPT_NAME'])?>
                </td>
            </tr>
            <tr class="fundoIndex">
            	<td colspan="2" height="465">					
                    <table align="center" cellpadding="2" cellspacing="0" style="border:#ccc solid 1px; background-color:#f9f9f9" width="260px">
                        <tr>
                            <td colspan="2" background="images/fundoBotao.gif" height="22" align="center"><img src="images/login.png" /> <label><b>Login</b></label></td>
                        </tr>
                        <tr>
                            <td colspan="2" height="2" align="left"></td>
                        </tr>
                        <tr>
                            <td><label>Usuário:</label></td>
                            <td><input type="text" name="login" id="login" width="200px"  /></td>
                        </tr>
                        <tr>
                            <td><label>Senha:</label></label></td>
                            <td><input type="password" name="senha" id="senha" width="200px" onkeyup="capturaTecla(event);"/><br /></td>
                        </tr>
                        <tr>
                            <td colspan="2" height="2" align="left"></td>
                        </tr>
                        <tr>
                            <td colspan="2" align="right">
                                <button onclick="logon();" id="entrar" width="80px" accesskey="E"><b><u>E</u>ntrar</b></button>&nbsp;
                            </td>
                        </tr>
                	</table> 				
                </td>
            </tr>
        </td>
       </table>
		<?php $oMenu->createBase()?>
   		</div>
		<script type="text/javascript" src="scripts/include.js"></script>
		<script type="text/javascript" src="scripts/ajax.js"></script>
		<script type="text/javascript" src="scripts/jquery-min.js"></script>
		<script type="text/javascript" src="scripts/jquery.blockUI.js"></script>		
		<script type="text/javascript" src="scripts/login.js"></script>
	</body>
</html>