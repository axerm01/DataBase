DROP DATABASE IF EXISTS ESQLDB;
CREATE DATABASE IF NOT EXISTS ESQLDB;
USE ESQLDB;

CREATE TABLE IF NOT EXISTS `STUDENTE` (
    `Nome` VARCHAR(45) NOT NULL,
    `Cognome` VARCHAR(45) NOT NULL,
    `Mail` VARCHAR(45) NOT NULL,
    `Matricola` VARCHAR(16) NOT NULL,
    `AnnoImm` YEAR NOT NULL,
    `password` varchar(255) NOT NULL,
    `Telefono` varchar(45) DEFAULT NULL,
    PRIMARY KEY (`Mail`))
    ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `DOCENTE` (
     `Nome` VARCHAR(45) NOT NULL,
    `Cognome` VARCHAR(45) NOT NULL,
    `Mail` VARCHAR(45) NOT NULL,
    `Corso` VARCHAR(45) NOT NULL,
    `Dipartimento` VARCHAR(45) NOT NULL,
    `password` varchar(255) NOT NULL,
    `Telefono` varchar(45) DEFAULT NULL,
    PRIMARY KEY (`Mail`))
    ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `TABELLA` (
    `ID` INT AUTO_INCREMENT NOT NULL,
    `MailProfessore` VARCHAR(45) NOT NULL,
    `Nome` VARCHAR(45) NOT NULL,
    `DataCreazione` DATETIME NOT NULL,
    `NumRighe` SMALLINT DEFAULT 0,
    PRIMARY KEY (`ID`),
    CONSTRAINT `FK_Tabella_MailDocente`
    FOREIGN KEY (`MailProfessore`)
    REFERENCES `DOCENTE` (`Mail`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
    ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `TEST` (
    `ID` INT NOT NULL AUTO_INCREMENT,
    `Titolo` VARCHAR(45) NOT NULL,
    `DataCreazione` DATETIME,
    `VisualizzaRisposte` VARCHAR(45) DEFAULT 'FALSE',
    `MailDocente` VARCHAR(45) NOT NULL,
    PRIMARY KEY (`ID`),
    CONSTRAINT `FK_Test_MailDocente`
    FOREIGN KEY (`MailDocente`)
    REFERENCES `DOCENTE` (`Mail`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
    ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `TEST_TABELLA` (
    `IDTabella` INT NOT NULL,
    `IDTest` INT NOT NULL,
    PRIMARY KEY (`IDTabella`, `IDTest`),
    CONSTRAINT `FK_TT_IDTabella`
    FOREIGN KEY (`IDTabella`)
    REFERENCES `TABELLA` (`ID`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
    CONSTRAINT `FK_TT_IDTest`
    FOREIGN KEY (`IDTest`)
    REFERENCES `TEST` (`ID`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
    ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `ATTRIBUTO` (
    `IDTabella` INT NOT NULL,
    `Nome` VARCHAR(45) NOT NULL,
    `Tipo` VARCHAR(45) NOT NULL,
    `IsPK` BOOLEAN DEFAULT 0,
    PRIMARY KEY (`IDTabella`, `Nome`),
    CONSTRAINT `FK_Attributo_IDTabella`
    FOREIGN KEY (`IDTabella`)
    REFERENCES `TABELLA` (`ID`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
    ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `CODICE` (
     `ID` INT NOT NULL,
     `Output` VARCHAR(255) DEFAULT NULL,
    `Descrizione` VARCHAR(255) DEFAULT NULL,
    `IDTest` INT NOT NULL,
    `Difficolta` ENUM('Basso','Medio','Alto') NOT NULL,
    PRIMARY KEY (`ID`, `IDTest`),
    CONSTRAINT `FK_Codice_IDTest`
    FOREIGN KEY (`IDTest`)
    REFERENCES `TEST` (`ID`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
    ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `GALLERIA` (
    `IDTest` INT NOT NULL,
    `Foto` LONGBLOB DEFAULT NULL,
    PRIMARY KEY (`IDTest`),
    CONSTRAINT `FK_Galleria_IDTest`
    FOREIGN KEY (`IDTest`)
    REFERENCES `TEST` (`ID`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
    ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `MESSAGGIO_DOCENTE` (
    `Titolo` VARCHAR(45) NOT NULL,
    `Testo` VARCHAR(255) NOT NULL,
    `Data` DATETIME NOT NULL,
    `IDTest` INT NOT NULL,
    `MailDocente` VARCHAR(45) NOT NULL,
    PRIMARY KEY (`IDTest`, `Data`),
    CONSTRAINT `FK_MessaggioDocente_IDTest`
    FOREIGN KEY (`IDTest`)
    REFERENCES `TEST` (`ID`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
    CONSTRAINT `FK_MessaggioDocente_MailDocente`
    FOREIGN KEY (`MailDocente`)
    REFERENCES `DOCENTE` (`Mail`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
    ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `MESSAGGIO_STUDENTE` (
     `Titolo` VARCHAR(45) NOT NULL,
    `Testo` VARCHAR(255) NOT NULL,
    `Data` DATETIME NOT NULL,
    `IDTest` INT NOT NULL,
    `MailStudente` VARCHAR(45) NOT NULL,
    PRIMARY KEY (`MailStudente`, `Data`),
    CONSTRAINT `FK_MessaggioStudente_IDTest`
    FOREIGN KEY (`IDTest`)
    REFERENCES `TEST` (`ID`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
    CONSTRAINT `FK_MessaggioStudente_MailStudente`
    FOREIGN KEY (`MailStudente`)
    REFERENCES `STUDENTE` (`Mail`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
    ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `REFERENZE` (
     `IDT1` INT NOT NULL,
     `NomeAttributo1` VARCHAR(45) NOT NULL,
    `IDT2` INT NOT NULL,
    `NomeAttributo2` VARCHAR(45) NOT NULL,
    PRIMARY KEY (`IDT1`, `NomeAttributo1`, `IDT2`, `NomeAttributo2`),
    CONSTRAINT `FK_Referenze_IDTab1`
    FOREIGN KEY (`IDT1`, `NomeAttributo1`)
    REFERENCES `ATTRIBUTO` (`IDTabella`, `Nome`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
    CONSTRAINT `FK_Referenze_IDTab2`
    FOREIGN KEY (`IDT2`, `NomeAttributo2`)
    REFERENCES `ATTRIBUTO` (`IDTabella`, `Nome`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
    ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `SCELTA_MULTIPLA` (
    `ID` INT NOT NULL,
    `Descrizione` VARCHAR(255) NOT NULL,
    `Difficolta` ENUM('Basso','Medio','Alto') NOT NULL,
    `NumRisposte` INT NOT NULL DEFAULT 0,
    `IDTest` INT NOT NULL,
    PRIMARY KEY (`ID`, `IDTest`),
    CONSTRAINT `FK_SceltaMultipla_IDTest`
    FOREIGN KEY (`IDTest`)
    REFERENCES `TEST` (`ID`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
    ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `SCELTA` (
     `ID` INT NOT NULL,
     `Testo` VARCHAR(255) NULL,
    `IDTest` INT NOT NULL,
    `IDScMult` INT NOT NULL,
    `IsCorretta` INT default 0,
    PRIMARY KEY (`ID`, `IDTest`, `IDScMult`),
    CONSTRAINT `FK_Scelta_IDTest`
    FOREIGN KEY (`IDTest`)
    REFERENCES `TEST` (`ID`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
    CONSTRAINT `FK_Scelta_IDScMult`
    FOREIGN KEY (`IDScMult`)
    REFERENCES `SCELTA_MULTIPLA` (`ID`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
    ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `RISPOSTA_SCELTA` (
     `Studente` varchar(45) not null,
    `IDDomanda` INT NOT NULL,
    `IDRisposta` int not null,
    `IDTest` INT NOT NULL,
    `Esito` Boolean default 0,
    PRIMARY KEY (`Studente`, `IDDomanda`,`IDTest`),
    CONSTRAINT `FK_RispostaScelta_IDTest`
    FOREIGN KEY (`IDTest`)
    REFERENCES `TEST` (`ID`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
    CONSTRAINT `FK_RispostaScelta_Studente`
    FOREIGN KEY (`Studente`)
    REFERENCES `Studente` (`Mail`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
    )
    ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `RISPOSTA_CODICE` (
     `Studente` varchar(45) not null,
    `IDDomanda` INT NOT NULL,
    `CodiceRisposta` varchar(500),
    `IDTest` INT NOT NULL,
    `Esito` Boolean default 0,
    PRIMARY KEY (`Studente`, `IDDomanda`,`IDTest`),
    CONSTRAINT `FK_RispostaCodice_IDTest`
    FOREIGN KEY (`IDTest`)
    REFERENCES `TEST` (`ID`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
    CONSTRAINT `FK_RispostaCodice_Studente`
    FOREIGN KEY (`Studente`)
    REFERENCES `Studente` (`Mail`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
    )
    ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `SVOLGIMENTO` (
    `MailStudente` VARCHAR(45) NOT NULL,
    `Stato` ENUM('Aperto','InCompletamento','Concluso') DEFAULT NULL,
    `DataPrimaRisposta` DATETIME DEFAULT NULL,
    `DataUltimaRisposta` DATETIME DEFAULT Null,
    `IDTest` INT NOT NULL,
    PRIMARY KEY (`MailStudente`, `IDTest`),
    CONSTRAINT `FK_Svolgimento_Mail`
    FOREIGN KEY (`MailStudente`)
    REFERENCES `STUDENTE` (`Mail`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
    CONSTRAINT `FK_Svolgimento_IDTest`
    FOREIGN KEY (`IDTest`)
    REFERENCES `TEST` (`ID`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
    ENGINE = InnoDB;



CREATE VIEW ClassificaTestCompletati AS
SELECT
    Studente.Matricola,
    COUNT(*) AS Numero
FROM
    Svolgimento
        JOIN
    Studente ON Svolgimento.MailStudente = Studente.Mail
WHERE
    Svolgimento.Stato = 'Concluso'
GROUP BY
    Studente.Matricola
ORDER BY
    Numero DESC;


CREATE VIEW ClassificaRisposteCorrette AS
SELECT
    Studente.Matricola,
    ROUND((SUM(CASE WHEN (rs.Esito = 1 OR rc.Esito = 1) THEN 1 ELSE 0 END) /
           COUNT(*)) * 100, 2) AS PercentualeSuccesso
FROM
    Studente
        LEFT JOIN
    risposta_scelta rs ON rs.Studente = Studente.Mail
        LEFT JOIN
    risposta_codice rc ON rc.Studente = Studente.Mail
GROUP BY
    Studente.Matricola
ORDER BY
    PercentualeSuccesso DESC;



CREATE VIEW ClassificaQuesiti AS
SELECT
    IDDomanda,
    IDTest,
    COUNT(*) AS NumeroRisposte
FROM (
         SELECT IDDomanda, IDTest FROM risposta_codice
         UNION ALL
         SELECT IDDomanda, IDTest FROM risposta_scelta
     ) AS Risposte
GROUP BY
    IDDomanda, IDTest
ORDER BY
    NumeroRisposte DESC;



DELIMITER //
CREATE TRIGGER ViewAnswersConclusion AFTER UPDATE ON test
    FOR EACH ROW BEGIN
    IF NEW.VisualizzaRisposte = TRUE THEN
    UPDATE Svolgimento
    SET svolgimento.Stato = 'Concluso'
    WHERE svolgimento.IDTest = NEW.ID;
END IF;
END//


CREATE TRIGGER UpdateStatoOnPrimaRisposta
    BEFORE UPDATE ON Svolgimento
    FOR EACH ROW
BEGIN
    IF OLD.DataPrimaRisposta IS NULL AND NEW.DataPrimaRisposta IS NOT NULL THEN
        SET NEW.Stato = 'InCompletamento';
END IF;
END//


CREATE TRIGGER CloseTestAfterAllCorrectAnswers
    AFTER INSERT ON risposta_codice
    FOR EACH ROW
BEGIN
    DECLARE totalQuestions INT;
    DECLARE correctAnswers INT;

    SELECT COUNT(*) INTO totalQuestions
    FROM (
             SELECT ID FROM Codice WHERE IDTest = NEW.IDTest
             UNION ALL
             SELECT ID FROM Scelta_multipla WHERE IDTest = NEW.IDTest
         ) AS Questions;

    SELECT COUNT(*) INTO correctAnswers
    FROM (
             SELECT IDTest FROM risposta_codice WHERE Studente = NEW.Studente AND IDTest = NEW.IDTest AND Esito = 1
             UNION ALL
             SELECT IDTest FROM risposta_scelta WHERE Studente = NEW.Studente AND IDTest = NEW.IDTest AND Esito = 1
         ) AS CorrectAnswers
    WHERE IDTest = NEW.IDTest;

    IF totalQuestions = correctAnswers THEN
    UPDATE Svolgimento
    SET Stato = 'Concluso'
    WHERE MailStudente = NEW.Studente AND IDTest = NEW.IDTest;
END IF;
END//


CREATE TRIGGER CloseTestAfterAllCorrectAnswersUpdate
    AFTER UPDATE ON risposta_codice
    FOR EACH ROW
BEGIN
    DECLARE totalQuestions INT;
    DECLARE correctAnswers INT;

    SELECT COUNT(*) INTO totalQuestions
    FROM (
             SELECT ID FROM Codice WHERE IDTest = NEW.IDTest
             UNION ALL
             SELECT ID FROM Scelta_multipla WHERE IDTest = NEW.IDTest
         ) AS Questions;

    SELECT COUNT(*) INTO correctAnswers
    FROM (
             SELECT IDTest FROM risposta_codice WHERE Studente = NEW.Studente AND IDTest = NEW.IDTest AND Esito = 1
             UNION ALL
             SELECT IDTest FROM risposta_scelta WHERE Studente = NEW.Studente AND IDTest = NEW.IDTest AND Esito = 1
         ) AS CorrectAnswers
    WHERE IDTest = NEW.IDTest;

    IF totalQuestions = correctAnswers THEN
    UPDATE Svolgimento
    SET Stato = 'Concluso'
    WHERE MailStudente = NEW.Studente AND IDTest = NEW.IDTest;
END IF;
END//


CREATE TRIGGER CloseTestAfterAllCorrectAnswersMC
    AFTER INSERT ON risposta_scelta
    FOR EACH ROW
BEGIN
    DECLARE totalQuestions INT;
    DECLARE correctAnswers INT;

    SELECT COUNT(*) INTO totalQuestions
    FROM (
             SELECT ID FROM Codice WHERE IDTest = NEW.IDTest
             UNION ALL
             SELECT ID FROM Scelta_multipla WHERE IDTest = NEW.IDTest
         ) AS Questions;

    SELECT COUNT(*) INTO correctAnswers
    FROM (
             SELECT IDTest FROM risposta_codice WHERE Studente = NEW.Studente AND IDTest = NEW.IDTest AND Esito = 1
             UNION ALL
             SELECT IDTest FROM risposta_scelta WHERE Studente = NEW.Studente AND IDTest = NEW.IDTest AND Esito = 1
         ) AS CorrectAnswers
    WHERE IDTest = NEW.IDTest;

    IF totalQuestions = correctAnswers THEN
    UPDATE Svolgimento
    SET Stato = 'Concluso'
    WHERE MailStudente = NEW.Studente AND IDTest = NEW.IDTest;
END IF;
END//

CREATE TRIGGER CloseTestAfterAllCorrectAnswersMCUpdate
    AFTER UPDATE ON risposta_scelta
    FOR EACH ROW
BEGIN
    DECLARE totalQuestions INT;
    DECLARE correctAnswers INT;

    SELECT COUNT(*) INTO totalQuestions
    FROM (
             SELECT ID FROM Codice WHERE IDTest = NEW.IDTest
             UNION ALL
             SELECT ID FROM Scelta_multipla WHERE IDTest = NEW.IDTest
         ) AS Questions;

    SELECT COUNT(*) INTO correctAnswers
    FROM (
             SELECT IDTest FROM risposta_codice WHERE Studente = NEW.Studente AND IDTest = NEW.IDTest AND Esito = 1
             UNION ALL
             SELECT IDTest FROM risposta_scelta WHERE Studente = NEW.Studente AND IDTest = NEW.IDTest AND Esito = 1
         ) AS CorrectAnswers
    WHERE IDTest = NEW.IDTest;

    IF totalQuestions = correctAnswers THEN
    UPDATE Svolgimento
    SET Stato = 'Concluso'
    WHERE MailStudente = NEW.Studente AND IDTest = NEW.IDTest;
END IF;
END//


DELIMITER ;


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

CREATE PROCEDURE ViewAllTT(
    in IDTestAtt int
)
BEGIN
SELECT *
FROM  test_tabella
where IDTest = IDTestAtt;
END //

CREATE PROCEDURE `CreateAttribute`(
    in IDTabellaAtt int,
    in NomeAtt varchar(45),
    in TipoAtt varchar(45),
    in IsPKAtt boolean
)
BEGIN
INSERT INTO attributo (IDTabella,Nome,Tipo,IsPK)
VALUES (IDTabellaAtt,NomeAtt,TipoAtt,IsPKAtt);
END //

CREATE PROCEDURE `ViewAllAttributes`(in IDTabellaAtt int)
BEGIN
SELECT*
FROM attributo
WHERE IDTabella = IDTabellaAtt;
END //

CREATE PROCEDURE `CreateCodeQuestion`(
    in IDTestAtt int,
    in IDAtt int,
    in TextAtt varchar(45),
    in OutputAtt varchar(255),
    in DiffAtt varchar(45)
)
BEGIN
INSERT INTO Codice (IDTest,ID, Descrizione, Output, Difficolta)
VALUES (IDTestAtt,IDAtt,TextAtt,OutputAtt,DiffAtt);
END //

CREATE PROCEDURE `DropCodeQuestion`(
    in IDAtt int,
    in IDTestAtt int
)
BEGIN
DELETE FROM Codice
WHERE ID = IDAtt and IDTest = IDTestAtt;
END //

CREATE PROCEDURE `ViewAllCodeQuestions`(in IDTestAtt int)
BEGIN
SELECT*
FROM codice
WHERE IDTest = IDTestAtt;
END //

CREATE PROCEDURE `ViewSqlCode`(in IDTestAtt int)
BEGIN
SELECT codice.ID, codice.Output
FROM codice
WHERE IDTest = IDTestAtt;
END //


CREATE PROCEDURE `CreateProfessor`(
    in NomeAtt varchar(45),
    in CognomeAtt varchar(45),
    in MailAtt varchar(45),
    in CorsoAtt varchar(45),
    in DipartimentoAtt varchar(45),
    in TelefonoAtt varchar(45),
    in PasswordAtt varchar(255)
)
BEGIN
INSERT INTO docente (Nome, Cognome ,Mail ,Corso, Dipartimento, Telefono,Password)
VALUES (NomeAtt, CognomeAtt ,MailAtt ,CorsoAtt, DipartimentoAtt, TelefonoAtt,PasswordAtt);
END //

CREATE PROCEDURE GetStudentPassword(IN input_email VARCHAR(45))
BEGIN
SELECT password FROM Studente WHERE mail = input_email;
END //

CREATE PROCEDURE GetProfessorPassword(IN input_email VARCHAR(45))
BEGIN
SELECT password FROM Docente WHERE mail = input_email;
END //


CREATE PROCEDURE `AddToGallery`(
    in IDTestAtt int,
    in FotoAtt LONGBLOB
)
BEGIN
INSERT INTO Galleria(IDTest,Foto)
VALUES (IDTestAtt, FotoAtt);
END //

CREATE PROCEDURE `ViewPhoto`(
    in IDTestAtt int
)
BEGIN
SELECT *
FROM Galleria
WHERE IDTest = IDTestAtt;
END //

CREATE PROCEDURE `CreateProfessorMessage`(
    in TitoloAtt varchar(45),
    in TestoAtt varchar(500),
    in DataAtt DATETIME,
    in IDTestAtt int,
    in MailDocenteAtt varchar(45)
)
BEGIN
INSERT INTO messaggio_docente (Titolo, Testo, Data, IDTest, MailDocente)
VALUES (TitoloAtt,TestoAtt,DataAtt,IDTestAtt,MailDocenteAtt);
END //

CREATE PROCEDURE `ViewProfessorMessages`(
    in IDTestAtt int
)
BEGIN
SELECT *
FROM messaggio_docente
WHERE
    IDTest = IDTestAtt;
END //


CREATE PROCEDURE `CreateStudentMessage`(
    in TitoloAtt varchar(45),
    in TestoAtt varchar(500),
    in DataAtt DATETIME,
    in IDTestAtt int,
    in MailStudenteAtt varchar(45)
)
BEGIN
INSERT INTO messaggio_Studente (Titolo, Testo, Data, IDTest, MailStudente)
VALUES (TitoloAtt,TestoAtt,DataAtt,IDTestAtt,MailStudenteAtt);
END //

CREATE PROCEDURE `ViewStudentMessages`(
    in IDTestAtt int
)
BEGIN
SELECT *
FROM messaggio_Studente
WHERE
    IDTest = IDTestAtt;
END//

CREATE PROCEDURE `CreateReference`(
    in IDT1Att int,
    in IDT2Att int,
    in NomeAttributo1Att varchar(45),
    in NomeAttributo2Att varchar(45)
)
BEGIN
INSERT IGNORE INTO referenze (IDT1, NomeAttributo1, IDT2, NomeAttributo2)
VALUES (IDT1Att, NomeAttributo1Att, IDT2Att, NomeAttributo2Att);
END //

CREATE PROCEDURE `DropReference`(
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


CREATE PROCEDURE `CreateAnswer`(
    in IDAtt int,
    in IDTestAtt int,
    in IDScMulAtt int,
    in TestoAtt varchar(255),
    in ISCorrectAtt int
)
BEGIN
INSERT INTO scelta (ID,Testo,IDTest,IDScMult, IsCorretta)
VALUES (IDAtt,TestoAtt,IDTestAtt,IDScMulAtt, ISCorrectAtt);
END //

CREATE PROCEDURE `DropAnswers`(
    in IDTestAtt int,
    in IDScMultAtt varchar(45)
)
BEGIN
DELETE FROM scelta
WHERE IDTest = IDTestAtt and IDScMult = IDScMultAtt;
END //

CREATE PROCEDURE `ViewAnswers`(in IDTestAtt int,
                               in IDScMultAtt int)
BEGIN
SELECT *
FROM scelta
WHERE IDTest = IDTestAtt and IDScMult = IDScMultAtt;
END //


CREATE PROCEDURE `CreateMC`(
    in IDTestAtt int,
    in IDAtt int,
    in DescrizioneAtt varchar(255),
    in NumRisposteAtt int,
    in DifficoltaAtt varchar(45)
)
BEGIN
INSERT INTO Scelta_Multipla (IDTest,ID,Descrizione,NumRisposte,Difficolta)
VALUES (IDTestAtt,IDAtt,DescrizioneAtt,NumRisposteAtt,DifficoltaAtt);
END //

CREATE PROCEDURE `DropMC`(
    in IDTestAtt int,
    in IDAtt varchar(45)
)
BEGIN
DELETE FROM Scelta_Multipla
WHERE IDTest = IDTestAtt and ID = IDAtt;
END //

CREATE PROCEDURE `ViewAllMC`(in IDTestAtt int)
BEGIN
SELECT *
FROM Scelta_Multipla
WHERE IDTest = IDTestAtt;
END //


CREATE PROCEDURE `CreateStudent`(
    in NomeAtt varchar(45),
    in CognomeAtt varchar(45),
    in MailAtt varchar(45),
    in MatricolaAtt varchar(16),
    in AnnoImmAtt year,
    in TelefonoAtt varchar(45),
    in PasswordAtt varchar(255)
)
BEGIN
INSERT INTO Studente (Nome, Cognome ,Mail ,Matricola, AnnoImm, Telefono,Password)
VALUES (NomeAtt, CognomeAtt ,MailAtt ,MatricolaAtt, AnnoImmAtt, TelefonoAtt,PasswordAtt);
END //

CREATE PROCEDURE `CreateStudentTest`(
    in MailStudenteAtt varchar(45),
    in StatoAtt varchar(45),
    in IDTestAtt int
)
BEGIN
INSERT INTO Svolgimento (MailStudente,Stato,DataPrimaRisposta,DataUltimaRisposta,IDTest)
VALUES (MailStudenteAtt,StatoAtt,null,null,IDTestAtt);
END //

CREATE PROCEDURE `ViewStudentTest`(in IDTestAtt int,
                                   in MailStudenteAtt varchar(45))
BEGIN
SELECT*
FROM Svolgimento
WHERE IDTest = IDTestAtt and MailStudente = MailStudenteAtt;
END //

CREATE PROCEDURE `ViewAllStudentTest`(in MailStudenteAtt varchar(45))
BEGIN
SELECT*
FROM Svolgimento
WHERE MailStudente = MailStudenteAtt;
END //

CREATE PROCEDURE `ViewStudentTestByStatus`(in StatoAtt varchar(45),
                                           in MailStudenteAtt varchar(45))
BEGIN
SELECT*
FROM Svolgimento
WHERE Stato = StatoAtt and MailStudente = MailStudenteAtt;
END //

CREATE PROCEDURE `UpdateStudentTestStart`(
    in IDTestAtt int,
    in MailStudenteAtt varchar(45),
    in DataPrimaRispostaAtt datetime)
BEGIN
UPDATE svolgimento
SET DataPrimaRisposta = DataPrimaRispostaAtt
WHERE IDTest = IDTestAtt and MailStudente = MailStudenteAtt;
END //

CREATE PROCEDURE `UpdateStudentTestEnd`(
    in IDTestAtt int,
    in MailStudenteAtt varchar(45),
    in DataUltimaRispostaAtt datetime)
BEGIN
UPDATE svolgimento
SET DataUltimaRisposta = DataUltimaRispostaAtt
WHERE IDTest = IDTestAtt and MailStudente = MailStudenteAtt;
END //

CREATE PROCEDURE `CreateTable`(
    in MailAtt varchar(45),
    in NomeAtt varchar(45),
    in DataAtt date,
    out IDAtt int )
BEGIN
INSERT INTO tabella (MailProfessore, Nome, DataCreazione)
VALUES (MailAtt, NomeAtt, DataAtt);
SET IDAtt = LAST_INSERT_ID();
END//

CREATE PROCEDURE `DropTable`(IN ID smallint, IN nome varchar(45))
BEGIN

DELETE FROM tabella
WHERE tabella.ID = ID;

DELETE FROM ATTRIBUTO
WHERE IDTabella = ID;
END //

CREATE PROCEDURE `ViewAllTables`(in mail varchar(45))
BEGIN
SELECT *
FROM tabella
WHERE
    MailProfessore = mail;
END//

CREATE PROCEDURE `ViewTable`(in ID int)
BEGIN
SELECT *
FROM tabella
WHERE
    tabella.ID = ID ;
END//

CREATE PROCEDURE `ViewTableName`(in ID int)
BEGIN
SELECT Nome
FROM tabella
WHERE
    tabella.ID = ID ;
END//


CREATE PROCEDURE `CreateTest`(
    in TitoloAtt varchar(45),
    in VisualizzaRisposteAtt int,
    in MailDocenteAtt varchar(45),
    out IDAtt INT
)
BEGIN
Declare orario DATETIME;
set orario = NOW();
INSERT INTO test (Titolo,DataCreazione,VisualizzaRisposte,MailDocente)
VALUES (TitoloAtt,orario,VisualizzaRisposteAtt,MailDocenteAtt);
SET IDAtt = LAST_INSERT_ID();
END //

CREATE PROCEDURE `ShowTestAnswers`(
    IN IDAtt INT
)
BEGIN
UPDATE test
SET VisualizzaRisposte = true
WHERE ID = IDAtt;
END //


CREATE PROCEDURE `ViewStudentCodeAnswers`(
    in  IDTestAtt int,in StudenteAtt varchar(45)
)
BEGIN
SELECT *
FROM RISPOSTA_CODICE
WHERE Studente = StudenteAtt and IDTest = IDTestAtt;
END //

CREATE PROCEDURE `ViewStudentMCAnswers`(
    in  IDTestAtt int,in StudenteAtt varchar(45)
)
BEGIN
SELECT *
FROM RISPOSTA_SCELTA
WHERE Studente = StudenteAtt and IDTest = IDTestAtt;
END //

CREATE PROCEDURE `UpdateStudentMCAnswer`(
    in StudenteAtt varchar(45), in IDDomandaAtt int, in IDTestAtt int, in IDRispostaAtt int, in EsitoAtt boolean
)
BEGIN
UPDATE RISPOSTA_SCELTA
SET IDRisposta = IDRispostaAtt, Esito = EsitoAtt
WHERE Studente = StudenteAtt and IDDomanda = IDDomandaAtt and IDTest = IDTestAtt;
END //

CREATE PROCEDURE `UpdateStudentCodeAnswer`(
    in StudenteAtt varchar(45),in IDDomandaAtt int, in IDTestAtt int, in RispostaAtt varchar(500), in EsitoAtt boolean
)
BEGIN
UPDATE RISPOSTA_CODICE
SET CodiceRisposta = RispostaAtt, Esito = EsitoAtt
WHERE Studente = StudenteAtt and IDDomanda = IDDomandaAtt and IDTest = IDTestAtt;
END //


CREATE PROCEDURE `ViewAllTests`(
    in MailDocenteAtt varchar(45)
)
BEGIN
SELECT *
FROM Test
WHERE MailDocente = MailDocenteAtt;
END //

CREATE PROCEDURE `ViewAllDBTests`()
BEGIN
SELECT *
FROM Test;
END //

CREATE PROCEDURE `UpdateTestTitle`(
    in IDAtt int,
    in TitleAtt varchar(45)
)
BEGIN
UPDATE TEST
SET Titolo = TitleAtt
WHERE ID = IDAtt;
END //

CREATE PROCEDURE `CreateStudentMCAnswer`(
    in StudenteAtt varchar(45), in IDTestAtt int , in IDDomandaAtt int ,in IDRispostaAtt int, in EsitoAtt boolean
)
BEGIN
INSERT INTO RISPOSTA_SCELTA(Studente,IDDomanda,IDRisposta,IDTest,Esito)
VALUES (StudenteAtt,IDDomandaAtt,IDRispostaAtt,IDTestAtt,EsitoAtt);
END//

CREATE PROCEDURE `CreateStudentCodeAnswer`(
    in StudenteAtt varchar(45),in IDTestAtt int ,in IDDomandaAtt int , in CodiceRispostaAtt varchar(500), in EsitoAtt boolean
)
BEGIN
INSERT INTO RISPOSTA_CODICE(Studente,IDDomanda,IDTest,CodiceRisposta,Esito)
VALUES (StudenteAtt,IDDomandaAtt,IDTestAtt,CodiceRispostaAtt,EsitoAtt);
END//


CREATE PROCEDURE CheckIfNameExists(IN nomeInput VARCHAR(45), OUT result BOOLEAN)
BEGIN
    DECLARE countName INT;

SELECT COUNT(*) INTO countName
FROM Tabella
WHERE Nome = nomeInput;

IF countName > 0 THEN
        SET result = TRUE;
ELSE
        SET result = FALSE;
END IF;
END //

CREATE PROCEDURE CheckIfTestNameExists(IN nomeInput VARCHAR(45), IN mail VARCHAR(45), OUT result BOOLEAN)
BEGIN
    DECLARE countName INT;

SELECT COUNT(*) INTO countName
FROM Test
WHERE Titolo = nomeInput AND MailDocente = mail;

IF countName > 0 THEN
        SET result = TRUE;
ELSE
        SET result = FALSE;
END IF;
END//

CREATE PROCEDURE UpdateOutputCodice(IN IdCodice int, in testId int, IN OutputAtt varchar(255))
BEGIN
UPDATE CODICE
SET CODICE.Output = OutputAtt
WHERE ID = IdCodice and  IDTest = testId;
END //

CREATE PROCEDURE UpdateAnswer(IN IdAtt int, in testId int, IN IDScMultAtt int, in testoAtt varchar(255),in IsCorrettaAtt int)
BEGIN
UPDATE SCELTA
SET Testo = testoAtt AND IsCorretta = IsCorrettaAtt
WHERE ID = IdAtt and  IDTest = testId and IDScMult = IDScMultAtt;
END //

CREATE PROCEDURE UpdateNumMCAnswers(IN IdAtt int, in testId int,in Numero int)
BEGIN
UPDATE SCELTA_MULTIPLA
SET NumRisposte = NumRisposte + numero
WHERE ID = IdAtt and  IDTest = testId;
END //

CREATE PROCEDURE UpdateMCDifficulty(IN IdAtt int, in testId int,in DifficoltaAtt varchar(45))
BEGIN
UPDATE SCELTA_MULTIPLA
SET Difficolta = DifficoltaAtt
WHERE ID = IdAtt and  IDTest = testId;
END //

CREATE PROCEDURE UpdateMCDescription(IN IdAtt int, in testId int,in DescrizioneAtt varchar(255))
BEGIN
UPDATE SCELTA_MULTIPLA
SET Descrizione = DesrizioneAtt
WHERE ID = IdAtt and  IDTest = testId;
END //

CREATE PROCEDURE DropSingleAnswer(IN IdAtt int, in testId int, IN IDScMultAtt int)
BEGIN
DELETE FROM SCELTA
WHERE ID = IdAtt and  IDTest = testId and IDScMult = IDScMultAtt;
END //

CREATE PROCEDURE GetCorrectAnswers(in testId int)
BEGIN
SELECT IDScMult, ID FROM SCELTA
WHERE IDTest = testId AND IsCorretta = TRUE;
END //

DELIMITER ;
