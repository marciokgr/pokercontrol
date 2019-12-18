<?php
require_once('includes/validaSessao.php');
require_once('objetos/conexao.php');
require_once('objetos/sessao.php');

$oConexao = new Conexao();
$oSessao = new Sessao();

$iAcessoID = $_SESSION['AcessoID'];

$sSQL = "update Acesso set DataLogoff = CURRENT_TIMESTAMP where ID = '". $iAcessoID ."'";
$oConexao->sql($sSQL);

$oSessao->encerraSessao();

header("Location: index.php");
?>