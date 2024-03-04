DELIMITER //
CREATE PROCEDURE `CreateSceltaMultipla`(
in DescrizioneAtt varchar(45),
in IDAtt int,
in IDTestAtt int,
in NumRisposteAtt int,
in DifficoltaAtt int
)
BEGIN
INSERT INTO Scelta_Multipla (Descrizione,ID,IDTest,NumRisposte,Difficolta)
VALUES (DescrizioneAtt,IDAtt,IDTestAtt,NumRisposteAtt,DifficoltaAtt);
END //

CREATE PROCEDURE `DropSceltaMultipla`(
in IDTestAtt int,
in DescrizioneAtt varchar(45)
)
BEGIN
DELETE FROM Scelta_Multipla
WHERE IDTest = IDTestAtt and Descrizione = DescrizioneAtt;
END //

CREATE PROCEDURE `ViewSceltaMultipla`(in IDTestAtt int,
in DescrizioneAtt varchar(45))
BEGIN
SELECT*
FROM Scelta_Multipla
WHERE IDTest = IDTestAtt and Descrizione = DescrizioneAtt;
END //

DELIMITER ;