CREATE DEFINER=`root`@`localhost` PROCEDURE `CreateStudente`(NomeAtt text, CognomeAtt text,MailAtt text,MatricolaAtt varchar(16), AnnoImmAtt year,TelefonoAtt int)
BEGIN
INSERT INTO studente (Nome, Cognome ,Mail ,Matricola, AnnoImm, Telefono)
VALUES (NomeAtt, CognomeAtt ,MailAtt ,MatricolaAtt, AnnoImmAtt, TelefonoAtt);
END