CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateStudente`(NomeAtt text, CognomeAtt text,MailAtt text,MatricolaAtt varchar(16), AnnoImmAtt year,TelefonoAtt int)
BEGIN
UPDATE studente
SET 
Nome = NomeAtt,
Cognome = CognomeAtt,
Matricola = MatricolaAtt, 
AnnoImm = AnnoImmAtt, 
Telefono = TelefonoAtt
WHERE Mail = MailAtt;
END