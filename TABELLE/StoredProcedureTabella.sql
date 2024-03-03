DELIMITER //

CREATE PROCEDURE `CreateTable`(
in MailAtt varchar(45),
in NomeAtt varchar(45),
in DataAtt datetime,
in NumRigheAtt smallint)
BEGIN
    INSERT INTO tabella (MailProfessore, Nome, DataCreazione, NumRighe)
    VALUES (MailAtt, NomeAtt, DataAtt, NumRigheAtt);
END//

CREATE PROCEDURE `DropTable`(
in ID smallint,
in mail varchar(45))
BEGIN
DELETE FROM tabella
WHERE (
tabella.ID = ID and
tabella.MailProfessore = mail);
END//

CREATE PROCEDURE `ViewAllTables`(in mail varchar(45))
BEGIN
SELECT *
FROM tabella
WHERE 
MailProfessore = mail;
END//

CREATE PROCEDURE `ViewTable`(in ID smallint,in mail varchar(45))
BEGIN
SELECT *
FROM tabella
WHERE (
tabella.ID = ID and
MailProfessore = mail);
END//

DELIMITER ;