DELIMITER //
CREATE PROCEDURE `CreateCodice`(
in IDTestAtt int,
in IDAtt int,
in OutputAtt varchar(45)
)
BEGIN
INSERT INTO Codice (ID,IDTest,Output)
VALUES (IDAtt,IDTestAtt,OutputAtt);
END //

CREATE PROCEDURE `DropCodice`(
in IDAtt int
)
BEGIN
DELETE FROM Codice
WHERE ID = IDAtt;
END //

CREATE PROCEDURE `ViewAllCodice`(in IDTestAtt int)
BEGIN
SELECT*
FROM codice
WHERE IDTest = IDTestAtt;
END //

CREATE PROCEDURE `ViewCodice`(in IDAtt int)
BEGIN
SELECT*
FROM codice
WHERE ID = IDAtt;
END //

DELIMITER ;