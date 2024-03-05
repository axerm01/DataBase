DELIMITER //
CREATE PROCEDURE `CreateCodice`(
in IDTestAtt int,
in OutputAtt text
)
BEGIN
INSERT INTO Codice (IDTest,Output)
VALUES (IDTestAtt,OutputAtt);
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