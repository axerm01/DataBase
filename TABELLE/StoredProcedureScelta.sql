DELIMITER //
CREATE PROCEDURE `CreateScelta`(
in TestoAtt varchar(45),
in IDAtt int,
in IDTestAtt int,
in IDScMulAtt int,
in IsCorrect boolean
)
BEGIN
INSERT INTO scelta (Testo,ID,IDTest,IDScMul,IsCorrect)
VALUES (TestoAtt,ID,IDTestAtt,IDScMulAtt,IsCorrect);
END //

CREATE PROCEDURE `DropScelta`(
in IDTestAtt int,
in TestoAtt varchar(45)
)
BEGIN
DELETE FROM scelta
WHERE IDTest = IDTestAtt and Testo = TestoAtt;
END //

CREATE PROCEDURE `ViewScelta`(in IDTestAtt int,
in TestoAtt varchar(45))
BEGIN
SELECT*
FROM scelta
WHERE IDTest = IDTestAtt and Testo = TestoAtt;
END //

DELIMITER ;