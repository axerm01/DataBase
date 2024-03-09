DELIMITER //

CREATE PROCEDURE CreateTT(
in IDTabellaAtt int,
in IDTestAtt int
)
BEGIN 
INSERT INTO test_tabella (IDTabella,IDTest)
VALUES(IDTabellaAtt,IDTestAtt);
END //

CREATE PROCEDURE RemoveAllTT(
in IDTabellaAtt int,
in IDTestAtt int
)
BEGIN 
DELETE FROM test_tabella
WHERE IDTabella = IDTabellaAtt and IDTest = IDTestAtt;
END //

CREATE PROCEDURE RemoveTT(
in IDTestAtt int
)
BEGIN 
DELETE FROM test_tabella
WHERE IDTest = IDTestAtt;
END //

CREATE PROCEDURE ViewTT(
in IDTabellaAtt int,
in IDTestAtt int
)
BEGIN 
SELECT *
FROM  test_tabella
where IDTabella = IDTabellaAtt and IDTest = IDTestAtt;
END //

CREATE PROCEDURE ViewAllTT(
in IDTestAtt int
)
BEGIN 
SELECT *
FROM  test_tabella
where IDTest = IDTestAtt;
END //

DELIMITER ;