<?php
	/* Descrição: Objeto para se conectar com o banco de dados mysql */
	Class Conexao{
		//////////////// Atributos da class //////////////////
		var $banco = "";
		var $servidor = "";
		var $usuario = "";
		var $senha = "";
		var $query = "";
		var $link = "";
		
		//////////////// Metodos da classe ///////////////
		/* Metodo Contrutor */
		function Conexao()
		{
			$this->dadosConexao();
			$this->abrirConexao();
		}
		
		/* Metodo inicia as variáveis para conexao com o banco */
		function dadosConexao()
		{
			if ($_SERVER["SERVER_NAME"] == "localhost" || $_SERVER["SERVER_NAME"] == "192.168.195.132")
			{
				// se estiver rodando locammente
				$this->banco="poker";
				$this->servidor="localhost";
				$this->usuario="root";
				$this->senha="";
			} else {
				// se estiver rodando no servidor
				$this->banco="localdb";
				$this->servidor="127.0.0.1:50621";
				$this->usuario="azure";
				$this->senha="6#vWHD_$";
				
				// se estiver rodando no servidor
				/*$this->banco="acsm_9a970396de47a91";
				$this->servidor="br-cdbr-azure-south-b.cloudapp.net";
				$this->usuario="be3ce7ab99d15b";
				$this->senha="076f7926";*/
				
			}
		}
		
		/* Metodo conexao com o banco */
		function abrirConexao()
		{
			$this->link = mysql_connect($this->servidor, $this->usuario, $this->senha);
			if (!$this->link) 
			{
				die("Erro na conexao". mysql_error());
			} elseif (!mysql_select_db($this->banco, $this->link))
			{
				die("Banco de Dados não encontrado");
			}
			//mysql_set_charset($this->link,'utf8');
		}
		
		/* Metodo sql */
		function sql($query){
			$this->query = $query;
			if ($result = mysql_query($this->query,$this->link)){
				return $result;
			} else {
				return 0;
			}
		}
		
		/* Metodo que retorna o ultimo id de um inserção */
		function id(){
			return mysql_insert_id($this->link);
		}
		
		/* Metodo fechar conexao */
		function fechar(){
			return mysql_close($this->link);
		}
	}
	///////////////// FIM DA CLASSE ////////////////
?>