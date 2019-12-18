<?php
require_once('../objetos/conexao.php');

$oConexao = new Conexao();

$sSQL = "select ID, Nome from Usuario;";
$rsTeste = $oConexao->sql($sSQL);

while($lsTeste = mysql_fetch_array($rsTeste)){
	echo $lsTeste[ID] ." - ". $lsTeste[Nome] ."<br />";
}

$oConexao->fechar();
?>