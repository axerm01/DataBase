DELIMITER //

CREATE PROCEDURE `CreateMessaggioDocente`(
in TitoloAtt varchar(45),
in TestoAtt varchar(500),
in DataAtt DATETIME,
in IDTestAtt int,
in MailDocenteAtt varchar(45)
)
BEGIN
INSERT INTO messaggio_docente (Titolo, Testo, Data, IDTest, MailDocente)
VALUES (TitoloAtt,TestoAtt,DataAtt,IDTestAtt,MailDocenteAtt);
END //

CREATE PROCEDURE `DropMessaggioDocente`(
in DataAtt DATETIME,
in IDTestAtt int
)
BEGIN
DELETE FROM messaggio_docente
WHERE 
(Data = DataAtt and
IDTest = IDTestAtt)
;
END //

CREATE PROCEDURE `ViewMessaggioDocente`(
in DataAtt DATETIME,
in IDTestAtt int
)
BEGIN
SELECT *
FROM messaggio_docente
WHERE ( 
Data = DataAtt and
IDTest = IDTestAtt
);
END //

CREATE PROCEDURE `ViewMessaggiDocente`(
in MailDocenteAtt int
)
BEGIN
SELECT *
FROM messaggio_docente
WHERE
MailDocente = MailDocenteAtt;
END //