DELIMITER //

CREATE PROCEDURE `CreateAttributo`(
in IDTabellaAtt int,
in NomeAtt varchar(45),
in TipoAtt varchar(45),
in IsPKAtt boolean
)
BEGIN
INSERT INTO attributo (IDTabella,Nome,Tipo,IsPK)
VALUES (IDTabellaAtt,NomeAtt,TipoAtt,IsPKAtt);
END //

CREATE PROCEDURE `DropAttributo`(
in IDTabellaAtt int,
in NomeAtt varchar(45)
)
BEGIN
DELETE FROM attributo
WHERE IDTabella = IDTabellaAtt and Nome = NomeAtt;
END //

CREATE PROCEDURE `ViewAllAttributi`(in IDTabellaAtt int)
BEGIN
SELECT*
FROM attributo
WHERE IDTabella = IDTabellaAtt;
END //

CREATE PROCEDURE `ViewAttributo`(in IDTabellaAtt int,
in NomeAtt varchar(45))
BEGIN
SELECT * 
FROM attributo
WHERE IDTabella = IDTabellaAtt and Nome = NomeAtt;
END //

DELIMITER ;