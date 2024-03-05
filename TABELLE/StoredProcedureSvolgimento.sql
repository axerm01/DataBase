DELIMITER //
CREATE PROCEDURE `CreateSvolgimento`(
in MailStudenteAtt varchar(45),
in StatoAtt varchar(45),
in DataPrimaRispostaAtt datetime,
in DataUltimaRispostaAtt datetime,
in IDTestAtt int
)
BEGIN
INSERT INTO Scelta_Multipla (MailStudente,Stato,DataPrimaRisposta,DataUltimaRisposta,IDTest)
VALUES (MailStudenteAtt,StatoAtt,DataPrimaRispostaAtt,DataUltimaRispostaAtt,IDTestAtt);
END //

CREATE PROCEDURE `DropSvolgimento`(
in IDTestAtt int,
in MailStudenteAtt varchar(45)
)
BEGIN
DELETE FROM Svolgimento
WHERE IDTest = IDTestAtt and MailStudente = MailStudenteAtt;
END //

CREATE PROCEDURE `ViewSvolgimento`(in IDTestAtt int,
in MailStudenteAtt varchar(45))
BEGIN
SELECT*
FROM Scelta_Multipla
WHERE IDTest = IDTestAtt and MailStudente = MailStudenteAtt;
END //

CREATE PROCEDURE `UpdateInizioSvolgimento`(in IDTestAtt int,
in MailStudenteAtt varchar(45))
BEGIN
UPDATE svolgimento
SET DataPrimaRisposta = localtime()
WHERE IDTest = IDTestAtt and MailStudente = MailStudenteAtt;
END //

CREATE PROCEDURE `UpdateFineSvolgimento`(in IDTestAtt int,
in MailStudenteAtt varchar(45))
BEGIN
UPDATE svolgimento
SET DataUltimaRisposta = localtime()
WHERE IDTest = IDTestAtt and MailStudente = MailStudenteAtt;
END //

CREATE PROCEDURE `UpdateStatoSvolgimento`(in IDTestAtt int,
in MailStudenteAtt varchar(45), in StatoAtt varchar(45))
BEGIN
UPDATE svolgimento
SET Stato = StatoAtt
WHERE IDTest = IDTestAtt and MailStudente = MailStudenteAtt;
END //
DELIMITER ;