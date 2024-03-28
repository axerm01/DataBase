DELIMITER //
CREATE PROCEDURE `CreateScelta`(
in TestoAtt varchar(45),
in IDTestAtt int,
in IDScMulAtt int
)
BEGIN
INSERT INTO scelta (Testo,IDTest,IDScMul)
VALUES (TestoAtt,IDTestAtt,IDScMulAtt);
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
in IDAtt int, in IDScMultAtt)
BEGIN
SELECT*
FROM scelta
WHERE IDTest = IDTestAtt and ID = IDAtt and IDScMult = IDScMultAtt;
END //

CREATE PROCEDURE `ViewAllScelta`(
in IDTestAtt int)
BEGIN
SELECT*
FROM scelta
WHERE IDTest = IDTestAtt;
END //

DELIMITER ;