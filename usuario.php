<?php
	header("Content-Type: text/html; charset=iso-8859-1");

	require_once('includes/validaSessao.php');

	//Includes
	require_once('objetos/sitemap.php');	
	require_once('objetos/menu.php');
	
	//Objetos
	$oSiteMap = new SiteMap();
	$oMenu = new Menu();
	
		
	$iSessaoID = session_id();
	$iUsuID = $_SESSION['UsuarioID'];
	$cUsuTipo = $_SESSION['UsuarioTipo'];
	$iAcessoID = $_SESSION['AcessoID'];
	$sNome = $_SESSION['Nome'];
	$sNivel = $_SESSION['Nivel'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-br" lang="pt-br">	
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title><?php echo $oMenu->getVersao()?> - Usuários</title>
        <link rel="shortcut icon" HREF="favicon.ico"> 
		<link rel="stylesheet" type="text/css" href="css/default.css"/>				
	</head>	
	<body>
     <div class="geral">     	
    	<input type="hidden" id="hidUsuID" value="" />	
		
		<?php $oMenu->createTop()?>        
		<?php $oSiteMap->getSiteMap($_SERVER['SCRIPT_NAME'])?>
        <?php $oMenu->createMenu()?>
            <div class="tituloPagina"><img src="images/user.png"/>&nbsp;<b>Usuário</b></div>            
            <div id="conteudo" name="conteudo" class="divConteudo">
                <label>Nome:</label><br />
                <input type="text" id="txtNome" name="txtNome" size="30" maxlength="128" /> <br />                        
                
                <label>E-mail:</label><br />
                <input type="text" id="txtEmail" name="txtEmail" size="30" maxlength="256" /> <br />
                
                <label>Login:</label><br />
                <input type="text" id="txtLogin" name="txtLogin" size="30" maxlength="64" /> <br />
                
                <label>Senha:</label><br />
                <input type="password" id="txtSenha" name="txtSenha" size="30" maxlength="64" /> <br />
                
                <label>Nivel:</label><br />
                <select id="nivel" name="nivel" style="width:205px">
                	<option value="">--Selecione--</option>
                    <option value="M">Moderador</option>
                    <option value="U">Usuário</option>
                </select> <br />
                
                <button id="btnCadastrar" moderador style="margin-top:4px;width:85px;" onclick="validaCampos('c');" accesskey="C"><U>C</U>adastrar</button>
                <button id="btnAlterar" moderador style="margin-top:4px;width:85px;;" onclick="validaCampos('a');" accesskey="A"><u>A</u>lterar</button>
                <button id="btnNovo" moderador style="margin-top:4px;width:85px;;" onclick="novo();" accesskey="A"><u>N</u>ovo</button>
                
                <div id="listagem" style="margin-top:10px; margin-bottom:10px;">&nbsp;</div>
                
                <a href="principal.php">Voltar</a>
            </div> 
        <?php $oMenu->createBase()?>
        </div>  	
		<script type="text/javascript" src="scripts/ajax.js"></script>
		<script type="text/javascript" src="scripts/include.js"></script>			
		<script type="text/javascript" src="scripts/jquery-min.js"></script>
		<script type="text/javascript" src="scripts/jquery.blockUI.js"></script>
		<script type="text/javascript" src="scripts/usuario.js"></script>	
		<script>
			sNivel = "<?=$sNivel?>";
		</script>
	</body>
</html>
