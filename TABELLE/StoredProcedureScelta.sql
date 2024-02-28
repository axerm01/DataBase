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
in TestoAtt varchar(45))
BEGIN
SELECT*
FROM scelta
WHERE IDTest = IDTestAtt and Testo = TestoAtt;
END //

DELIMITER ;