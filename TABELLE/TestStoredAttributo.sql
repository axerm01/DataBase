/*-- Test per la procedura 'CreateAttributo'
CALL CreateTabella('mario.Bianchi@example.com', 'Tabella2', CURDATE(), 10);
SELECT * FROM tabella WHERE MailProfessore = 'mario.Bianchi@example.com' AND Nome = 'Tabella1';

-- Test per la procedura 'DropTabella'
-- Prima, otteniamo l'ID della tabella che abbiamo appena creato
SET @id = (SELECT ID FROM tabella WHERE MailProfessore = 'mario.Bianchi@example.com' AND Nome = 'Tabella2');
CALL DropTabella(@id, 'mario.Bianchi@example.com');
SELECT * FROM tabella WHERE ID = @id;

-- Test per la procedura 'ViewAllTabelle'
CALL ViewAllTabelle('mario.Bianchi@example.com');

-- Test per la procedura 'ViewTabella'
-- Creiamo un'altra tabella per il test
CALL CreateTabella('mario.Bianchi@example.com', 'Tabella4', CURDATE(), 20);
SET @id = (SELECT ID FROM tabella WHERE MailProfessore = 'mario.Bianchi@example.com' AND Nome = 'Tabella4');
CALL DropTabella(@id, 'mario.Bianchi@example.com');
*/
-- Inserisci un attributo nella tabella
CALL CreateAttributo(4, 'Colonna1', 'INT', 1);

-- Visualizza tutti gli attributi della tabella
CALL ViewAllAttributi(4);

-- Inserisci un altro attributo nella tabella
CALL CreateAttributo(4, 'Colonna2', 'VARCHAR(50)', 0);

-- Visualizza tutti gli attributi della tabella
CALL ViewAllAttributi(4);

-- Visualizza un attributo specifico
CALL ViewAttributo(4, 'Colonna1');

-- Elimina un attributo
CALL DropAttributo(4, 'Colonna1');

-- Visualizza tutti gli attributi dopo l'eliminazione
CALL ViewAllAttributi(4);
