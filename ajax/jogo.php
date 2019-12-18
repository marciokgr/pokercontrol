<?php
header("Content-Type: text/html; charset=iso-8859-1");

require_once('../objetos/conexao.php');
require_once('../objetos/formataSQL.php');
require_once('../objetos/log.php');
session_start();

$sOrigem = addslashes($_GET["origem"]);

switch ($sOrigem)
{
	case "lista":					lista();					break;
	case "buscaLocais":				buscaLocais();				break;
	case "buscaParticipantes":		buscaParticipantes(); 		break;
	case "cadastra":				cadastra();					break;
	case "edita":					edita();					break;
	case "excluir" : 				excluir();					break;
	case "atualizaParticipantes":	atualizaParticipantes();	break;
	case "listaVencedores":			listaVencedores();			break;
	case "proximaPartida" : 		proximaPartida();			break;
	case "buscaTotalFichas" : 		buscaTotalFichas();			break;
	case "calculaFichasRestantes" : calculaFichasRestantes();	break;
}

function lista()
{
	$oConexao = new Conexao();
	
	$sSQL = "SELECT ".
				"P.ID, ".
				"P.Data, ".
				"L.Descricao as 'Local', ".
				"P.ValorBuyIn, ".
				"P.ValorReBuy, ".
				"(SELECT COUNT(ID) FROM Participante WHERE IDPartida = P.ID) TotalParticipantes, ".
				"(SELECT SUM(Rebuys) FROM Participante WHERE IDPartida = P.ID) TotalRebuy ".
			"FROM ".
				"Partida P ".
					"INNER JOIN Local L ".
						"on (P.IDLocal = L.ID) ".
			"ORDER BY ".
				"P.Data DESC;";
	$rsJogo = $oConexao->sql($sSQL);
	
	$sRetorno = "";
	
	while($lsJogo = mysql_fetch_array($rsJogo))
	{
		$sRetorno .= "<tr>".
						"<td><label>" . date('d/m/Y', strtotime($lsJogo[Data])) . "</label></td>".
						"<td><label>" . $lsJogo[Local] . "</label></td>".
						"<td><label>R$" . $lsJogo[ValorBuyIn] . "</label></td>".
						"<td><label>R$" . $lsJogo[ValorReBuy] . "</label></td>".
						"<td><label>" . $lsJogo[TotalParticipantes] . "</label></td>".
						"<td><label>" . $lsJogo[TotalRebuy] . "</label></td>".
						"<td align=center><a href='jogoCadastra.php?jogoID=" . $lsJogo[ID] . "'>Alterar</a></td>".
						"<td align=center><a href='#' onclick='excluiJogo(". $lsJogo[ID] .");'>Excluir</a></td>".
					"</tr>";
	}
	
	if ($sRetorno != "")
	{
		$sRetorno = "<table id='ListTable'>".
						"<tr class='cabecalhoRelatorio'>".
							"<td style='width:85px;'><label><b>Data</b></label></td>".
							"<td style='width:150px;'><label><b>Local</b></label></td>".
							"<td style='width:95px;'><label><b>Buy-In (R$)</b></label></td>".
							"<td style='width:95px;'><label><b>Re-Buy (R$)</b></label></td>".
							"<td style='width:95px;'><label><b>Participantes</b></label></td>".
							"<td style='width:50px;'><label><b>Rebuys</b></label></td>".
							"<td style='width:50px;' align=center><img src='images/editar.png'></td>".
							"<td style='width:50px;' align=center><img src='images/delete.png'></td>".
						"<tr>".
						$sRetorno . 
					"</table>";
	}
	else
		$sRetorno = "&nbsp;";
	
	$oConexao->fechar();
	
	// Retorno AJAX
	echo $sRetorno;
}


function listaVencedores()
{
	$oConexao = new Conexao();
	
	$sJogoID = addslashes($_GET["jogoID"]);
	
	if ($sJogoID == "")
		$sJogoID = 0;
		
	$sSQL = "SELECT ".
				"U.Nome, ".
				"Participante.Fichas FichaVenc, ".
				"( ".
					"(".
						 "P.FichasBuyIn  ".
						"* ".
						"( ".
							"SELECT  ".							
								"COUNT(ID) ".							
							"FROM ".
								"Participante ".
							"WHERE ".
								"IDPartida=P.ID ".
						")".
					") +  ".
					"(".
						"P.FichasReBuy  ".
						"* ".
						"( ".
							"SELECT  ".							
								"sum(ReBuys) ".							
							"FROM ".
								"Participante ".
							"WHERE ".
								"IDPartida=P.ID ".
						")".
					") ".
				") TotalFichas, ".
				"(	".		 		
					"P.ValorBuyIn * ".
					"( ".
						"SELECT ".
							"COUNT(ID) ".						
						"FROM ".
							"Participante ".
						"WHERE ".
							"IDPartida=P.ID ".
					") + ".
					"( ".
						"SELECT ".
							"SUM(Rebuys * P.ValorRebuy) ".							
						"FROM ".
							"Participante ".
						"WHERE ".
							"IDPartida=P.ID ".
					") 	".
				") TotalValor ".
			"FROM ".
				"Partida P ".
				"INNER JOIN Participante ON (Participante.IDPartida=P.ID) ".
				"INNER JOIN Usuario U ON (U.ID = Participante.IDUsuario) ".
			"WHERE ".
				"Participante.Vencedor=1 AND ".
				"P.ID=". $sJogoID . " ".
			"ORDER BY FichaVenc DESC;";
				
	$rsLista = $oConexao->sql($sSQL);
	
	$sRetorno = "";
	
	$iPosicao = 1;
	
	$iLinhas = mysql_num_rows($rsLista);

	if ($iLinhas > 0)
	{			
		while($lsLista = mysql_fetch_array($rsLista))
		{
			$sRetorno .= "<tr>".
							"<td>". getClassificacao($iPosicao) ."</td>".
							"<td><label>" . $lsLista[Nome] . "</label></td>".
							"<td><label>R$ ". number_format(($lsLista[TotalValor] * $lsLista[FichaVenc])  / $lsLista[TotalFichas], 2, '.', '') ."</label></td>".
						"</tr>";
			$iPosicao = $iPosicao + 1;
		}
	}
	
	if ($sRetorno != "")
	{
		$sRetorno = "<table id='ListTable'>".
						"<tr class='cabecalhoRelatorio'>".
							"<td></td>".
							"<td style='width:150px;'><label><b>Nome Vencedor</b></label></td>".
							"<td style='width:150px;'><label><b>Valor recebido</b></label></td>".							
						"<tr>".
						$sRetorno . 
					"</table>";
	}
	else
		$sRetorno = "&nbsp;";
	
	$oConexao->fechar();
	
	// Retorno AJAX
	echo $sRetorno;
}

function buscaLocais()
{
	$oConexao = new Conexao();
	
	$sSQL = "SELECT ".
				"ID, ".
				"Descricao ".
			"FROM ".
				"Local ".
			"ORDER BY ".
				"Descricao ASC;";
	$rsLocal = $oConexao->sql($sSQL);
	
	$sRetorno = "";
	
	while($lsLocal = mysql_fetch_array($rsLocal))
	{
		if ($sRetorno != "")
			$sRetorno .= "|@|@|";
		
		$sRetorno .= $lsLocal[ID] . "||" . $lsLocal[Descricao];
	}
	
	$oConexao->fechar();
	
	// Retorno AJAX
	echo $sRetorno;
}

function buscaParticipantes()
{
	$sJogoID = addslashes($_GET["jogoID"]);
	
	$oConexao = new Conexao();
	
	if ($sJogoID == "0")
	{
		$sSQL = "SELECT ".
					"ID, ".
					"Nome,  ".
					"0 as ReBuys, ".
					"0 as Vencedor, ".
					"0 as Fichas, ".
					"0 as Participante ".
				"FROM ".
					"Usuario ".
				"WHERE ".
					"Tipo <> 'A' AND ".
					"Ativo = 1 ".
				"ORDER BY ".
					"Nome ASC;";
	}
	else
	{
		$sSQL = "SELECT ".
					"U.ID, ".
					"U.Nome as Nome, ".
					"P.ReBuys, ".
					"P.Vencedor, ".
					"P.Fichas, ".
					"1 as Participante ".
				"FROM ".
					"Participante P ".
						"INNER JOIN Usuario U ".
							"on (U.ID = P.IDUsuario) ".
				"WHERE ".
					"P.IDPartida = ". $sJogoID ." ".
			"UNION ".
				"SELECT ".
					"ID, ".
					"Nome, ".
					"0 as ReBuys, ".
					"0 as Vencedor, ".
					"0 as Fichas, ".
					"0 as Participante ".
				"FROM ".
					"Usuario ".
				"WHERE ".
					"not exists(".
						"SELECT ".
							"ID ".
						"FROM ".
							"Participante P ".
						"WHERE ".
							"P.IDUsuario = Usuario.ID AND ".
							"P.IDPartida = ". $sJogoID ."".
					") AND ".
					"Tipo <> 'A' AND ".
					"Ativo = 1 ".
			"ORDER BY ".
				"Nome;";
	}
	
	$rsUsuario = $oConexao->sql($sSQL);
	
	$sRetorno = "";
	
	while($lsUsuario = mysql_fetch_array($rsUsuario))
	{
		//Padr�o n�o mostra.
		$sCheckedParticipante = "";
		$sDisplayReBuys = "display:none;";
		$sQtdeReBuys = "";
		$sDisplayVencedor = "display:none;";
		$sCheckedVencedor = "";
		$sQtdeFichas = "";
		$sDisplayFichas = "display:none;";
		
		//Se for Participante de Jogo
		if ($lsUsuario[Participante] == "1")
		{
			$sCheckedParticipante = "checked";
			$sDisplayReBuys = "display:'';";
			$sDisplayVencedor = "display:'';";
		}
		
		//Se tiver ReBuys
		if ($lsUsuario[ReBuys] != "0")
			$sQtdeReBuys = $lsUsuario[ReBuys];
		
		//Se for edi��o e � vencedor mostra
		if ($lsUsuario[Vencedor] != "0")
		{
			$sCheckedVencedor = "checked";
			$sDisplayFichas = "display:'';";
		}
		
		//Se tiver Fichas
		if ($lsUsuario[Fichas] != "0")
			$sQtdeFichas = $lsUsuario[Fichas];
		
		$sRetorno .= "<tr>".
						"<td><input type='checkbox' ". $sCheckedParticipante ." onclick='selecionaParticipante(this)' name='chbPart' id='chbPart' value='". $lsUsuario[ID] ."' /></td>".
						"<td><label>". $lsUsuario[Nome] ."</label></td>".
						"<td align='center'><input type='text' id='txtRebuys". $lsUsuario[ID] ."' onkeypress='return isNumeric(this, event);' value='". $sQtdeReBuys ."' onblur='controlaTotalFichas(this,2);' size='1' maxlength='1' valorAnterior='". $sQtdeReBuys ."' style='". $sDisplayReBuys ."' /></td>".
						"<td align='center'><input type='checkbox' ". $sCheckedVencedor ." onclick='selecionaVencedor(this, ". $lsUsuario[ID] .")' id='chbVencedor" . $lsUsuario[ID] ."' value='' style='". $sDisplayVencedor ."' /></td>".
						"<td align='center'><input type='text' id='txtFichas". $lsUsuario[ID] ."' value='". $sQtdeFichas ."' valorAnterior='". $sQtdeFichas ."' size='5' maxlength='5' value='". $lsUsuario[Fichas] ."' style='". $sDisplayFichas ."' onblur='controlaTotalFichas(this,1);'  /></td>".
					"</tr>";
	}
	
	$oConexao->fechar();
	
	if ($sRetorno != "")
	{
		$sRetorno = "<table id='ListTableVenc'>".
						"<tr class='cabecalhoRelatorio'>".
							"<td align='center'><img src='images/check.png'></td>".
							"<td style='width:150px;'><label><b>Nome</b></label></td>".
							"<td align='center' style='width:65px;'><label><b>Re-Buy's</b></label></td>".
							"<td align='center' style='width:65px;'><label><b>Vencedor</b></label></td>".
							"<td align='center' style='width:65px;'><label><b>Fichas</b></label></td>".
						"<tr>".
						$sRetorno . 
					"</table>";
	}
	else
		$sRetorno = "&nbsp;";
	
	// Retorno AJAX
	echo $sRetorno;
}

//Excluir partida;
function excluir()
{
	$iID = addslashes($_GET["id"]);
	
	//<Log>
	$oLog = new Log();
	$sDescricaoAtual = $oLog->getValorAtual("Partida","Data",$iID);
	$oLog->gravar("Partida",$_SESSION['Nome'],3,"",$sDescricaoAtual,"");
	//</Log>
	
	$oConexao = new Conexao();
	
	$sSQL = "DELETE FROM Participante WHERE IDPartida = ". $iID;
	$oConexao->sql($sSQL);
	
	$sSQL = "DELETE FROM Partida WHERE ID = ". $iID;
	$oConexao->sql($sSQL);
	
	$sRetorno = $oConexao->id();
	
	$oConexao->fechar();
	
	// Retorno AJAX
	echo "OK";
	
}

function cadastra()
{
	$sData = addslashes($_GET["data"]);
	$iLocal = addslashes($_GET["local"]);
	$dValorBuyin = addslashes($_GET["valorBuyin"]);
	$iFichasBuyin = addslashes($_GET["fichasBuyin"]);
	$dValorRebuy = addslashes($_GET["valorRebuy"]);
	$iFichasRebuy = addslashes($_GET["fichasRebuy"]);
	
	$oFormataSQL = new FormataSQL();
	
	$sData = $oFormataSQL->formata($sData, "d");
	
	$dValorBuyin = str_replace(",", ".", $dValorBuyin);
	$dValorRebuy = str_replace(",", ".", $dValorRebuy);
	
	$oConexao = new Conexao();
	
	$sSQL = "INSERT INTO Partida (".
				"Data, ".
				"IDLocal, ".
				"ValorBuyIn, ".
				"FichasBuyIn, ".
				"ValorReBuy, ".
				"FichasReBuy ".
			") VALUES (".
				$sData .", ".
				$iLocal .", ".
				$dValorBuyin .", ".
				$iFichasBuyin .", ".
				$dValorRebuy .", ".
				$iFichasRebuy .
			")";
	$oConexao->sql($sSQL);
	
	$sRetorno = $oConexao->id();
	
	$oConexao->fechar();
	
	//<Log>
	$oLog = new Log();
	$oLog->gravar("Partida",$_SESSION['Nome'],1,"","",$sData);
	//</Log>
	
	// Retorno AJAX
	echo $sRetorno;
}

function edita()
{
	$iJogoID = addslashes($_GET["idJogo"]);
	$sData = addslashes($_GET["data"]);
	$iLocal = addslashes($_GET["local"]);
	$dValorBuyin = addslashes($_GET["valorBuyin"]);
	$iFichasBuyin = addslashes($_GET["fichasBuyin"]);
	$dValorRebuy = addslashes($_GET["valorRebuy"]);
	$iFichasRebuy = addslashes($_GET["fichasRebuy"]);
	
	$oFormataSQL = new FormataSQL();
	
	$sData = $oFormataSQL->formata($sData, "d");
	
	$dValorBuyin = str_replace(",", ".", $dValorBuyin);
	$dValorRebuy = str_replace(",", ".", $dValorRebuy);
	
	//<Log>
	$oLog = new Log();

	//Data
	$sDescricaoAtual = $oLog->getValorAtual("Partida","Data",$iJogoID);
	$oLog->gravar("Partida",$_SESSION['Nome'],2,"Data",$sDescricaoAtual,$sData);
	
	//ValorBuyIn
	$sDescricaoAtual = $oLog->getValorAtual("Partida","ValorBuyIn",$iJogoID);
	$oLog->gravar("Partida",$_SESSION['Nome'],2,"ValorBuyIn",$sDescricaoAtual,$dValorBuyin);
	
	//FichasBuyIn
	$sDescricaoAtual = $oLog->getValorAtual("Partida","FichasBuyIn",$iJogoID);
	$oLog->gravar("Partida",$_SESSION['Nome'],2,"FichasBuyIn",$sDescricaoAtual,$iFichasBuyin);
	
	//ValorReBuy
	$sDescricaoAtual = $oLog->getValorAtual("Partida","ValorReBuy",$iJogoID);
	$oLog->gravar("Partida",$_SESSION['Nome'],2,"ValorReBuy",$sDescricaoAtual,$dValorRebuy);
	
	//ValorReBuy
	$sDescricaoAtual = $oLog->getValorAtual("Partida","FichasReBuy",$iJogoID);
	$oLog->gravar("Partida",$_SESSION['Nome'],2,"FichasReBuy",$sDescricaoAtual,$iFichasRebuy);
	
	//</Log>
	
	$oConexao = new Conexao();
	
	$sSQL = "UPDATE ".
				"Partida ".
			"SET ".
				"Data = ". $sData .", ".
				"IDLocal = ". $iLocal .", ".
				"ValorBuyIn = ". $dValorBuyin .", ".
				"FichasBuyIn = ". $iFichasBuyin .", ".
				"ValorReBuy = ". $dValorRebuy .", ".
				"FichasReBuy = ". $iFichasRebuy ." ".
			"WHERE ".
				"ID = ". $iJogoID .";";
	$oConexao->sql($sSQL);
	
	$oConexao->fechar();
	
	$sRetorno = $iJogoID;
	
	// Retorno AJAX
	echo $sRetorno;
}

function atualizaParticipantes()
{
	$iIDJogo = addslashes($_GET["idJogo"]);
	$sDados = addslashes($_GET["dados"]);
	
	$oConexao = new Conexao();
	
	$sSQL = "DELETE FROM Participante WHERE IDPartida = " . $iIDJogo . ";";
	$oConexao->sql($sSQL);
	
	$aParticipantes = explode("|@|@|", $sDados);
	
	for ($i = 0; $i < count($aParticipantes); $i++)
	{
		$aDados = explode("||", $aParticipantes[$i]);
		
		$sSQL = "INSERT INTO Participante (".
					"IDPartida, ".
					"IDUsuario, ".
					"ReBuys, ".
					"Vencedor, ".
					"Fichas".
				") VALUES (".
					$iIDJogo . ", ".
					$aDados[0] .", ".
					$aDados[1] .", ".
					$aDados[2] .", ".
					$aDados[3] .
				");";
		$oConexao->sql($sSQL);
	}
	
	$oConexao->fechar();
	
	// Retorno AJAX
	echo $sRetorno;
}

function proximaPartida()
{
	$idPartida = 0;
	
	$oConexao = new Conexao();
	
	$sSQL = "SELECT ".
				"P.ID ID, ".
				"P.Data Data, ".
				"L.Descricao Local ".
			"FROM ".
				"Partida P ".
				"INNER JOIN Local L ON (L.ID = P.IDLocal) ".
			"WHERE ".
				"P.Data >= CURRENT_DATE();";
	$oConexao->sql($sSQL);
	
	$rsProximaPartida = $oConexao->sql($sSQL);
	
	$sRetorno = "<label>Nenhuma partida prevista para os próximos dias.</label>";
	
	while($lsPartida = mysql_fetch_array($rsProximaPartida))
	{
		$idPartida = $lsPartida[ID];
		
		$sRetorno = "<label>Data: <b>". date('d/m/Y', strtotime($lsPartida[Data])) ."</b>.</label><br>".
					"<label>Local: <b>". $lsPartida[Local] ."</b>.</label><br>".
					"<label>Participantes Confirmados: <br><b>";
		
			$sSQL = "SELECT ".
						"U.Nome ".
					"FROM ".
						"Participante P ".
						"INNER JOIN Usuario U ON (U.ID = P.IDUsuario) ".
					"WHERE ".
						"P.IDPartida = ". $idPartida ." ".
					"ORDER BY U.Nome;";
			
			$oConexao->sql($sSQL);
		
			$rsParticipante = $oConexao->sql($sSQL);
			
			while($lsParticipantes = mysql_fetch_array($rsParticipante))
			{
				$sRetorno = $sRetorno . "&nbsp;&nbsp;&nbsp;-&nbsp;". $lsParticipantes[Nome] . "<br>";
				
			}
			$sRetorno = $sRetorno  . "</b></label><br>";
			
	}
	
	$oConexao->fechar();
	
	// Retorno AJAX
	echo $sRetorno;
}

function buscaTotalFichas()
{
	$iIDJogo = addslashes($_GET["jogoID"]);
	
	if ($iFichasAtuais = "")
		$iFichasAtuais = 0;
		
	$iFichasRestantes = 0;
	
	$oConexao = new Conexao();
	
	$sSQL = "SELECT ".
				"(".
					"( ".
						"FichasBuyIn ".
						"* ".
						"( ".
							"SELECT IFNULL(COUNT(*),0) FROM Participante WHERE IDPartida = P.ID ".
						")".
					") ".
					" + ".
					"( ".
						"FichasRebuy ".
						"* ".
						"( ".
							"SELECT IFNULL(SUM(Rebuys),0) FROM Participante WHERE IDPartida = P.ID ".
						")".
					") ".
				") Total ".
			"FROM ".
				"Partida P ".
			"WHERE ".
				"ID = ". $iIDJogo;

	$rsTotalFichas = $oConexao->sql($sSQL);
		
	$lsTotalFichas = mysql_fetch_array($rsTotalFichas);
	
	$sRetorno = $lsTotalFichas[Total];
	
	$oConexao->fechar();
	
	// Retorno AJAX
	echo $sRetorno;
	
}

function calculaFichasRestantes()
{
	
}

//Existe uma igual em Ranking.php
function getClassificacao($iPosicao)
{
	$sIconeTrofeu = "";
	
	switch ($iPosicao)
	{
		case 1 : 
		
			$sIconeTrofeu = "<img src='images/trofeu_ouro.png' title='Vencedor'>";
			break;	
			
		case 2: 
		
			$sIconeTrofeu = "<img src='images/trofeu_prata.png' title='2º Lugar'>";
			break;
			
		case 3: 
		
			$sIconeTrofeu = "<img src='images/trofeu_bronze.png' title='3º Lugar'>";
			break;	
			
		case 4: 
		
			$sIconeTrofeu = "<img src='images/medalha_ouro.png' title='4º Lugar'>";
			break;	
			
		case 5: 
		
			$sIconeTrofeu = "<img src='images/medalha_prata.png' title='5º Lugar'>";
			break;	
		
		case 6: 
		
			$sIconeTrofeu = "<img src='images/medalha_bronze.png' title='6º Lugar'>";
			break;	
		
		default:
		
			$sIconeTrofeu = "<img src='images/triste.png'>";
			break;
			
	}	
	
	return 	$sIconeTrofeu;
}
?>