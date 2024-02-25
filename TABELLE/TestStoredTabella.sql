-- Test per la procedura 'CreateTabella'
CALL CreateTabella('professore@example.com', 'Tabella1', CURDATE(), 10);
SELECT * FROM tabella WHERE MailProfessore = 'professore@example.com' AND Nome = 'Tabella1';

-- Test per la procedura 'DropTabella'
-- Prima, otteniamo l'ID della tabella che abbiamo appena creato
SET @id = (SELECT ID FROM tabella WHERE MailProfessore = 'professore@example.com' AND Nome = 'Tabella1');
CALL DropTabella(@id, 'professore@example.com');
SELECT * FROM tabella WHERE ID = @id;

-- Test per la procedura 'ViewAllTabelle'
CALL ViewAllTabelle('professore@example.com');

-- Test per la procedura 'ViewTabella'
-- Creiamo un'altra tabella per il test
CALL CreateTabella('professore@example.com', 'Tabella4', CURDATE(), 20);
SET @id = (SELECT ID FROM tabella WHERE MailProfessore = 'professore@example.com' AND Nome = 'Tabella4');
CALL DropTabella(@id, 'professore@example.com');
