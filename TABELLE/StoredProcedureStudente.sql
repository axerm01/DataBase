DELIMITER //

CREATE PROCEDURE `CreateStudente`(
in NomeAtt text,
in CognomeAtt varchar(45),
in MailAtt text,
in MatricolaAtt varchar(16),
in AnnoImmAtt text,
in TelefonoAtt int,
in PasswordAtt varchar(16)
)
BEGIN
INSERT INTO Studente (Nome, Cognome ,Mail ,Matricola, AnnoImm, Telefono,Password)
VALUES (NomeAtt, CognomeAtt ,MailAtt ,MatricolaAtt, AnnoImmAtt, TelefonoAtt,PasswordAtt);
END //

CREATE PROCEDURE `DropStudente`(
in MailParam text
)
BEGIN
DELETE FROM Studente
WHERE Mail = MailParam;
END //

CREATE PROCEDURE `UpdateStudente`(
in NomeAtt text,
in CognomeAtt text,
in MailAtt text,
in MatricolaAtt varchar(16),
in AnnoImmAtt text,
in TelefonoAtt int)
BEGIN
UPDATE Studente
SET 
Nome = NomeAtt,
Cognome = CognomeAtt,
AnnoImm = AnnoImmAtt, 
Matricola = MatricolaAtt, 
Telefono = TelefonoAtt
WHERE Mail = MailAtt;
END //

CREATE PROCEDURE `UpdateStudenteTel`(
in MailAtt text,
in Tel int)
BEGIN
UPDATE Studente
SET Telefono = Tel
WHERE Mail = MailAtt;
END //

CREATE PROCEDURE `ViewAllStudenti`()
BEGIN
SELECT*
FROM Studente;
END //

CREATE PROCEDURE `ViewStudente`(in Mail text)
BEGIN
SELECT * 
FROM Studente
WHERE Studente.Mail = Mail;
END //

DELIMITER ;