DELIMITER //

CREATE PROCEDURE `CreateMessaggioStudente`(
in TitoloAtt varchar(45),
in TestoAtt varchar(500),
in DataAtt DATETIME,
in IDTestAtt int,
in MailStudenteAtt varchar(45)
)
BEGIN
INSERT INTO messaggio_Studente (Titolo, Testo, Data, IDTest, MailStudente)
VALUES (TitoloAtt,TestoAtt,DataAtt,IDTestAtt,MailStudenteAtt);
END //

CREATE PROCEDURE `DropMessaggioStudente`(
in DataAtt DATETIME,
in IDTestAtt int
)
BEGIN
DELETE FROM messaggio_Studente
WHERE 
(Data = DataAtt and
IDTest = IDTestAtt)
;
END //

CREATE PROCEDURE `ViewMessaggioStudente`(
in DataAtt DATETIME,
in IDTestAtt int
)
BEGIN
SELECT *
FROM messaggio_Studente
WHERE ( 
Data = DataAtt and
IDTest = IDTestAtt
);
END //

CREATE PROCEDURE `ViewMessaggiStudente`(
in MailStudenteAtt int
)
BEGIN
SELECT *
FROM messaggio_Studente
WHERE
MailStudente = MailStudenteAtt;
END //