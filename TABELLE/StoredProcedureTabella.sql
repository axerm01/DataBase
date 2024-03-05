DELIMITER //

CREATE PROCEDURE `CreateTabella`(
in MailAtt varchar(45),
in NomeAtt varchar(45),
in DataAtt date,
in NumRigheAtt smallint)
BEGIN
    INSERT INTO tabella (MailProfessore, Nome, DataCreazione, NumRighe)
    VALUES (MailAtt, NomeAtt, DataAtt, NumRigheAtt);
END//

CREATE PROCEDURE `DropTabella`(
in ID smallint,
in mail varchar(45))
BEGIN
DELETE FROM tabella
WHERE (
tabella.ID = ID and
tabella.MailProfessore = mail);
END//

CREATE PROCEDURE `ViewAllTabelle`(in mail varchar(45))
BEGIN
SELECT *
FROM tabella
WHERE 
MailProfessore = mail;
END//

CREATE PROCEDURE `ViewTabella`(in ID smallint,in mail varchar(45))
BEGIN
SELECT *
FROM tabella
WHERE (
tabella.ID = ID and
MailProfessore = mail);
END//

DELIMITER ;