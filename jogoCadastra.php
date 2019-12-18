<?php
header("Content-Type: text/html; charset=iso-8859-1");

//Includes
require_once('objetos/sitemap.php');	
require_once('objetos/menu.php');
require_once('objetos/conexao.php');
require_once('includes/validaSessao.php');

//Objetos
$oSiteMap = new SiteMap();
$oMenu = new Menu();

$iSessaoID = session_id();
$iUsuID = $_SESSION['UsuarioID'];
$cUsuTipo = $_SESSION['UsuarioTipo'];
$iAcessoID = $_SESSION['AcessoID'];
$sNome = $_SESSION['Nome'];
$sNivel = $_SESSION['Nivel'];	

$iJogoID = addslashes($_GET["jogoID"]);

if ($iJogoID != "")
{
	$oConexao = new Conexao();
	
	$sSQL = "SELECT ".
				"Data, ".
				"IDLocal, ".
				"ValorBuyIn, ".
				"FichasBuyIn, ".
				"ValorReBuy, ".
				"FichasReBuy ".
			"FROM ".
				"Partida ".
			"WHERE ".
				"ID = ". $iJogoID .";";
	$rsJogo = $oConexao->sql($sSQL);
	
	$iLinhas = mysql_num_rows($rsJogo);
	
	if ($iLinhas > 0)
	{
		$lsJogo = mysql_fetch_array($rsJogo);
		
		$sData = explode(' ', $lsJogo[Data]);
		$sData = explode('-', $sData[0]);
		$sData = $sData[2].'/'.$sData[1].'/'.$sData[0];
		
		$iIDLocal = $lsJogo[IDLocal];
		$dValorBuyIn = $lsJogo[ValorBuyIn];
		$iFichasBuyIn = $lsJogo[FichasBuyIn];
		$dValorReBuy = $lsJogo[ValorReBuy];
		$iFichasReBuy = $lsJogo[FichasReBuy];
		
		
	}
	else
	{
		$sData = "";
		$iIDLocal = "";
		$dValorBuyIn = "";
		$iFichasBuyIn = "";
		$dValorReBuy = "";
		$iFichasReBuy = "";
	}
}
else
{
	$sData = "";
	$iIDLocal = "";
	$dValorBuyIn = "";
	$iFichasBuyIn = "";
	$dValorReBuy = "";
	$iFichasReBuy = "";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-br" lang="pt-br">	
	<head>
    	<link rel="stylesheet" type="text/css" href="css/default.css"/>
        <link rel="shortcut icon" HREF="favicon.ico"> 
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title><?php echo $oMenu->getVersao()?> - Jogos / Cadastro </title> 
	</head>	
	<body>
    	<input type="hidden" id="hidJogoID" value="<?= $iJogoID ?>" />
		<input type="hidden" id="hidIDLocal" value="<?= $iIDLocal ?>" />
		<input type="hidden" id="hidTotalFichas" value="0" />
        
		<div class="geral">
    	<?php $oMenu->createTop()?>        
		<?php $oSiteMap->getSiteMap($_SERVER['SCRIPT_NAME'])?>
        <?php $oMenu->createMenu()?>
            <div class="tituloPagina"><img src="images/jogos.png"/>&nbsp;<b>Jogos</b></div>
            <div id="conteudo" name="conteudo" class="divConteudo"> 
            
                <div style="float:left; height:250px;">
					<div style="margin-bottom:1px;"><label>Data:</label></div>
					<input type="text" id="txtData" name="txtData" value="<?= $sData ?>" size="12" maxlength="10"  onkeypress="return mascaraData(this, event);"  />
					
					<div style="margin-top:2px; margin-bottom:2px;"><label>Local:</label></div>
					<select name="selLocal" id="selLocal" style="width:150px;">
						<option value="0">-- Selecione --</option>
					</select>  
										  
					<div style="margin-top:2px; margin-bottom:2px;"><label>Valor Buy-In:</label></div>
					<input type="text" id="txtValorBuyin" name="txtValorBuyin" value="<?= $dValorBuyIn ?>" size="12" maxlength="12" onkeypress="return formatar_moeda(this,'.',',',event);" /> 
					
					<div style="margin-top:2px; margin-bottom:2px;"><label>Fichas Buy-In:</label></div>
					<input type="text" id="txtFichasBuyin" name="txtFichasBuyin" value="<?= $iFichasBuyIn ?>" size="12" maxlength="12" onkeypress="return isNumeric(this, event);" /> 
					
					<div style="margin-top:2px; margin-bottom:2px;"><label>Valor Re-Buy:</label></div>
					<input type="text" id="txtValorRebuy" name="txtValorRebuy" value="<?= $dValorReBuy ?>" size="12" maxlength="12" onkeypress="return formatar_moeda(this,'.',',',event);" />
					
					<div style="margin-top:2px; margin-bottom:2px;"><label>Fichas Re-Buy:</label></div>
					<input type="text" id="txtFichasRebuy" name="txtFichasRebuy" value="<?= $iFichasReBuy ?>" size="12" maxlength="12" onkeypress="return isNumeric(this, event);" />
				</div>
               			
				<div style="height:250px; display:table-cell;">
					<div style="margin-left:50px;">
						<label>Participantes:</label>&nbsp;&nbsp;&nbsp;&nbsp;<label id="TotalFichas"></label>
						<div id="divParticipantes" style="margin-top:5px;"></div>
					</div>
				</div>                
                <span>
                	<div style="display:none;margin-top:20px;" id="divVencedores"></div>
                </span>	
				<div style="float:none;">
					<div style="margin-top:5px;">
						<button id="btnCadastrar" moderador style="margin-top:4px;width:85px" onclick="validaCampos('c');"><u>C</u>adastrar</button>
						<button id="btnAlterar" moderador style="margin-top:4px;width:85px" onclick="validaCampos('a');"><u>A</u>lterar</button>
                        <button id="btnNovo" moderador style="margin-top:4px;width:85px;;" onclick="novo();" accesskey="A"><u>N</u>ovo</button>
					</div>
				<div style="float:left;"></div>
                </div> <br />				
				<a href="jogo.php">Voltar</a> 		               
				<br />	
            </div>           
        <?php $oMenu->createBase()?>
        </div>  
		<script type="text/javascript" src="scripts/ajax.js"></script>
		<script type="text/javascript" src="scripts/include.js"></script>		
		<script type="text/javascript" src="scripts/jquery-min.js"></script>
		<script type="text/javascript" src="scripts/jquery.blockUI.js"></script>	
		<script type="text/javascript" src="scripts/jogoCadastra.js"></script>
		<script type="text/javascript" src="scripts/cronometro.js"></script>
	</body>
</html>