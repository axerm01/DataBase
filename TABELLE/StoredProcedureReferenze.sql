DELIMITER //

CREATE PROCEDURE `CreateReferenze`(
in IDT1Att int,
in IDT2Att int,
in NomeAttributo1Att varchar(45),
in NomeAttributo2Att varchar(45)
)
BEGIN
INSERT INTO referenze (IDT1, NomeAttributo1, IDT2, NomeAttributo2)
VALUES (IDT1Att, NomeAttributo1Att, IDT2Att, NomeAttributo2Att);
END //

CREATE PROCEDURE `DropReferenze`(
in IDT1Att int,
in IDT2Att int,
in NomeAttributo1Att varchar(45),
in NomeAttributo2Att varchar(45)
)
BEGIN
DELETE FROM Referenze
WHERE 
(
IDT1 = IDT1Att and 
IDT2 = IDT2Att and
NomeAttributo1 = NomeAttributo1Att and
NomeAttributo2 = NomeAttributo2Att 
);
END //

CREATE PROCEDURE `ViewReferenze`(
in IDT1Att int,
in IDT2Att int,
in NomeAttributo1Att varchar(45),
in NomeAttributo2Att varchar(45)
)
BEGIN
SELECT * 
FROM Referenze
WHERE 
(
IDT1 = IDT1Att and 
IDT2 = IDT2Att and
NomeAttributo1 = NomeAttributo1Att and
NomeAttributo2 = NomeAttributo2Att 
);
END //
DELIMITER ; 