<?php
session_start();

$iUsuarioID = $_SESSION["UsuarioID"];

if ((int)("0".$iUsuarioID) == 0)
{
	header("Location: index.php");
}

?>