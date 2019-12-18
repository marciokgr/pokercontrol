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
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang=”pt-br” lang=”pt-br”>	
	<head>
    	<link rel="stylesheet" type="text/css" href="css/default.css"/>
        <link rel="shortcut icon" HREF="favicon.ico"> 
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title><?php echo $oMenu->getVersao()?> - Local</title>		
	</head>	
	<body>
    <div class="geral">
    	<?php $oMenu->createTop()?>        
		<?php $oSiteMap->getSiteMap($_SERVER['SCRIPT_NAME'])?>
        <?php $oMenu->createMenu()?>
            <div class="tituloPagina"><img src="images/local.png"/>&nbsp;<b>Local do Jogo</b></div>
            <div id="conteudo" name="conteudo" class="divConteudo">   
                 <input type="hidden" id="hidLocalID" value="" />	
                         
                <label>Local:</label><br />
                <input type="text" id="txtLocal" name="txtLocal" size="30" maxlength="128" /> <br />                       
                                        
                <button id="btnCadastrar" style="margin-top:4px;width:85px" onclick="validaCampos('c');" moderador><u>C</u>adastrar</button>
                <button id="btnAlterar" style="margin-top:4px;width:85px" onclick="validaCampos('a');" moderador><u>A</u>lterar</button>
                <button id="btnNovo" style="margin-top:4px;width:85px;;" onclick="novo();" accesskey="A" moderador><u>N</u>ovo</button>
                <br/><br/>
                <div id="listagem" style="overflow:auto">&nbsp;</div>
                 <br /> 
                <a href="principal.php">Voltar</a>  	
                                                 
            </div>  	
         <?php $oMenu->createBase()?>
        </div>
		<script type="text/javascript" src="scripts/ajax.js"></script>
		<script type="text/javascript" src="scripts/include.js"></script>
        <script language="javascript">
			window.onload=function()
			{
				document.getElementById("btnAlterar").style.display = "none";
				document.getElementById("btnNovo").style.display = "none";
				
				listaLocal();
				document.getElementById("txtLocal").value = "";
				document.getElementById("txtLocal").focus();
								
				//Desabilita Campos Cadastro quando usuário.
				verificaPermissao("<?=$sNivel?>"); 		
				
			};			
			
        	function validaCampos(acao)
			{
				if (document.getElementById("txtLocal").value == "")	
				{
					alert("Você deve informar uma descrição para o Local")	
					document.getElementById("txtLocal").focus();
					return false;
				}
				
				if (acao == 'c')
					cadastraUsuario();
				
				if (acao == 'a')
					editaLocal();
			}
			
			function listaLocal()
			{							
				sURL  = "ajax/local.php?origem=lista";				
				enviaDadosAJAX(sURL, 'retornoListaLocal','divCarregando',false);				
			}
			
			function retornoListaLocal(ret) {
				document.getElementById("listagem").innerHTML  = ret;
				alternaRelatorio();
			}			
						
			function cadastraUsuario()
			{
				
				sURL  = "ajax/local.php?origem=cadastra"		+
						"&descricao="	+ document.getElementById("txtLocal").value;
							
				enviaDadosAJAX(sURL,"retornoCadastro",'divCarregando',false);
			}
			
			function editaLocal()
			{
				sURL  = "ajax/local.php?origem=altera"+
						"&id="			+ document.getElementById("hidLocalID").value	+
						"&descricao="	+ document.getElementById("txtLocal").value;

				enviaDadosAJAX(sURL, "retornoCadastro",'divCarregando',false);
			}
			
			function editaUsuario()
			{
				sURL  = "ajax/local.php?origem=altera"+
						"&id="			+ document.getElementById("hidLocalID").value	+
						"&descricao="	+ document.getElementById("txtLocal").value;		
				enviaDadosAJAX(sURL,"retornoCadastro",undefined, false);
			}
			
			function retornoCadastro()
			{
				
				document.getElementById("hidLocalID").value = "";
				document.getElementById("txtLocal").value  = "";				
				
				document.getElementById("btnAlterar").style.display = "none";
				document.getElementById("btnCadastrar").style.display = "";
				document.getElementById("btnNovo").style.display = "none";
				
				listaLocal();
			}
			
			function alteraLocal(id)
			{
				sURL  = "ajax/local.php?origem=busca&id=" + id;
				enviaDadosAJAX(sURL, "retornoAlteracao", undefined, false);
			}
			
			function retornoAlteracao(retorno)
			{
				document.getElementById("btnCadastrar").style.display = "none";
				
				document.getElementById("hidLocalID").value = retorno.split("||")[0];
				document.getElementById("txtLocal").value  = retorno.split("||")[1];		
				
				document.getElementById("btnAlterar").style.display = "";
				document.getElementById("btnNovo").style.display = "";
			}
			
			function retornoValidaExclusao(retorno)
			{					
				if (retorno == "0")
				{					
					if(confirm("Deseja realmente excluir este Local?"))
				{
					sURL  = "ajax/local.php?origem=exclui&id=" + $("hidLocalID").value;
					enviaDadosAJAX(sURL, "retornoExclusao",undefined, false);
				}
				}
				else
				{
					alert("Este Local não pode ser excluído pois já foram realizadas partidas.");
					document.getElementById("txtLocal").focus();					
					return false;
				}				
			}	
						
			function validaExclusao(id)
			{	
				
				sURL  = "ajax/local.php?origem=validaExclusao&id=" + id;
				enviaDadosAJAX(sURL, "retornoValidaExclusao", undefined, false);

			}
			
			function retornoExclusao()
			{
				listaUsuarios();
			}	
			
			function excluiLocal(id)
			{
				if ("<?=$sNivel?>" == "U")
				{
					alert("Você não tem permissão para exclusão de Local");
					return false;
					
				}else{
					
					document.getElementById("hidLocalID").value = id;
					validaExclusao(id);	
				}
			}
			
			function retornoExclusao()
			{
				listaLocal();
			}
			
			function novo()
			{
				window.location.href = "local.php";
			}
        </script>
	</body>
</html>