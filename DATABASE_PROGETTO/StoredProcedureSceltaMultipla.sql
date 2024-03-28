DELIMITER //
CREATE PROCEDURE `CreateSceltaMultipla`(
in DescrizioneAtt varchar(45),
in IDTestAtt int,
in NumRisposteAtt int,
in DifficoltaAtt int
)
BEGIN
INSERT INTO Scelta_Multipla (Descrizione,IDTest,NumRisposte,Difficolta)
VALUES (DescrizioneAtt,IDTestAtt,NumRisposteAtt,DifficoltaAtt);
END //

CREATE PROCEDURE `DropSceltaMultipla`(
in IDTestAtt int,
in IDAtt int
)
BEGIN
DELETE FROM Scelta_Multipla
WHERE IDTest = IDTestAtt and ID = IDAtt;
END //

CREATE PROCEDURE `ViewSceltaMultipla`(
in IDTestAtt int,
in IDAtt int )
BEGIN
SELECT*
FROM Scelta_Multipla
WHERE IDTest = IDTestAtt and ID = IDAtt;
END //

CREATE PROCEDURE `ViewAllSceltaMultipla`(
    in IDTestAtt int )
        BEGIN
        SELECT*
        FROM Scelta_Multipla
        WHERE IDTest = IDTestAtt;
END //

DELIMITER ;