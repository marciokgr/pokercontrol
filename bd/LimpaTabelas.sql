DELETE FROM Acesso;
DELETE FROM Participante;
DELETE FROM Usuario;
DELETE FROM Partida;
DELETE FROM Local;
DELETE FROM Log;

INSERT INTO Usuario (Nome, Login, Senha, Tipo) VALUES ('Administrador', 'admin', '7307564d46039181382b91df8d582128', 'A');