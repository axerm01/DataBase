DELIMITER //

CREATE PROCEDURE `CreateDocente`(
in NomeAtt text,
in CognomeAtt varchar(45),
in MailAtt text,
in CorsoAtt varchar(16),
in DipartimentoAtt text,
in TelefonoAtt int,
in PasswordAtt varchar(16)  
)
BEGIN
INSERT INTO docente (Nome, Cognome ,Mail ,Corso, Dipartimento, Telefono,Password)
VALUES (NomeAtt, CognomeAtt ,MailAtt ,CorsoAtt, DipartimentoAtt, TelefonoAtt,PasswordAtt);
END //

CREATE PROCEDURE `DropDocente`(
in MailParam text
)
BEGIN
DELETE FROM docente
WHERE Mail = MailParam;
END //

CREATE PROCEDURE `CheckDocente` (
    IN MailAtt VARCHAR(45),
    IN PasswordAtt VARCHAR(16),
    OUT Risultato BOOLEAN
)
BEGIN
    SET Risultato = FALSE;
    IF EXISTS (
        SELECT *
        FROM Docente
        WHERE Mail = MailAtt AND password = PasswordAtt
    ) THEN
        SET Risultato = TRUE;
    END IF;
END//
  
CREATE PROCEDURE `UpdateDocente`(
in NomeAtt text,
in CognomeAtt text,
in MailAtt text,
in CorsoAtt varchar(16),
in DipartimentoAtt text,
in TelefonoAtt int)
BEGIN
UPDATE docente
SET 
Nome = NomeAtt,
Cognome = CognomeAtt,
Dipartimento = DipartimentoAtt, 
Corso = CorsoAtt, 
Telefono = TelefonoAtt
WHERE Mail = MailAtt;
END //

CREATE PROCEDURE `UpdateDocenteTel`(
in MailAtt text,
in Tel int)
BEGIN
UPDATE docente
SET Telefono = Tel
WHERE Mail = MailAtt;
END //

CREATE PROCEDURE `ViewAllDocenti`()
BEGIN
SELECT*
FROM docente;
END //

CREATE PROCEDURE `ViewDocente`(in Mail text)
BEGIN
SELECT * 
FROM docente
WHERE docente.Mail = Mail;
END //

DELIMITER ;
