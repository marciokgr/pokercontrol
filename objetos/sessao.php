<?php
	/* Descrição: Objeto para criar e encerrar a sessão do usuário */
	Class Sessao{
		
		/* Metodo Cria Sessão do Usuário */
		function iniciaSessao($iUsuID, $cUsuTipo, $iAcessoID, $sNome)
		{
			$this->usuID = $iUsuID;
			$this->acessoID = $iAcessoID;
			$this->Nome = $sNome;
			$this->Nivel = $cUsuTipo;
			
			session_start();
			
			//U - Usuario, A - Administrador, M -Moderador
			
			$_SESSION['UsuarioID'] = $this->usuID;
			$_SESSION['AcessoID'] = $this->acessoID;
			$_SESSION['Nome'] = $this->Nome;
			$_SESSION['Nivel'] = $this->Nivel;
		}
		
		/* Método Para Encerrar a Sessão do Usuário */
		function encerraSessao()
		{
			session_destroy();		
		}
	}
?>