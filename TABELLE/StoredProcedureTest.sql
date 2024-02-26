DELIMITER //

CREATE PROCEDURE `CreateTest`(
in TitoloAtt varchar(45),
in VisualizzRisposteAtt boolean,
in MailDocenteAtt varchar(45)
)
BEGIN
INSERT INTO test (Titolo,DataCreazione,VisualizzaRisposte,MailDocente)
VALUES (TitoloAtt,DATETIME,VisualizzaRisposteAtt,MailDocenteAtt);
END //

CREATE PROCEDURE `UpdateVisualizzaRisposteTest`(
in TitoloAtt varchar(45),
in VisualizzaRisposteAtt boolean,
in MailDocenteAtt varchar(45)
)
BEGIN
UPDATE test
SET VisualizzaRisposte = VisualizzaRisposteAtt
WHERE MailDocente = MailDocenteAtt and Titolo = TitoloAtt;
END //

CREATE PROCEDURE `ViewTest`(
in IDAtt int
)
BEGIN
SELECT *
FROM Test
WHERE ID = IDAtt;
END //

CREATE PROCEDURE `DropTest`(
in IDAtt int
)
BEGIN
DELETE FROM Test
WHERE ID = IDAtt;
END //

DELIMITER ;