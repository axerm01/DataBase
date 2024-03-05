DELIMITER //

CREATE PROCEDURE `CreateTest`(
in TitoloAtt varchar(45),
in MailDocenteAtt varchar(45),
in VisualizzaRisposteAtt boolean
)
BEGIN
INSERT INTO Test (Titolo,VisualizzaRisposte,MailDocente,DataCreazione)
VALUES (TitoloAtt,VisualizzaRisposteAtt,MailDocenteAtt,NOW());
END //

CREATE PROCEDURE `DropTest`(
in IDAtt int
)
BEGIN
DELETE FROM Test
WHERE ID = IDAtt;
END //

CREATE PROCEDURE `DropTest2`(
in TitoloAtt varchar(45),
in MailDocenteAtt varchar(45)
)
BEGIN
DELETE FROM Test
WHERE MailDocente = MailDocenteAtt and Titolo = TitoloAtt;
END //

CREATE PROCEDURE `ViewAllTest`(in MailDocenteAtt varchar(45))
BEGIN
SELECT*
FROM Test
WHERE MailDocente = MailDocenteAtt;
END //

CREATE PROCEDURE `ViewTest`(in IDAtt int)
BEGIN
SELECT * 
FROM Test
WHERE ID = IDAtt;
END //

CREATE PROCEDURE `ViewTest2`(in TitoloAtt varchar(45),
in MailDocenteAtt varchar(45))
BEGIN
SELECT * 
FROM Test
WHERE MailDocente = MailDocenteAtt and Titolo = TitoloAtt;
END //
DELIMITER ;