DELIMITER //

CREATE PROCEDURE `CreateGalleria`(
in IDTestAtt int,
in FotoAtt varchar(45)
)
BEGIN
INSERT INTO Galleria(IDTest,Foto)
VALUES (IDTestAtt, FotoAtt);
END //
  
CREATE PROCEDURE `ViewGalleria`(
in IDTestAtt int
)
BEGIN
SELECT *
FROM Galleria
WHERE IDTest = IDTestAtt;
END //

CREATE PROCEDURE `ViewFoto`(
in IDTestAtt int,
in FotoAtt varchar(45)
)
BEGIN
SELECT *
FROM Galleria
WHERE IDTest = IDTestAtt
and Foto = FotoAtt;
END //

CREATE PROCEDURE `DropFoto`(
in IDAtt int,
in FotoAtt varchar(45)
)
BEGIN
DELETE FROM Test
WHERE ID = IDAtt
and Foto = FotoAtt;
END //

DELIMITER ;
