DROP DATABASE IF EXISTS ESQL;
CREATE DATABASE IF NOT EXISTS ESQL;
USE ESQL;

CREATE TABLE IF NOT EXISTS `STUDENTE` (
  `Nome` VARCHAR(45) NOT NULL,
  `Cognome` VARCHAR(45) NOT NULL,
  `Mail` VARCHAR(45) NOT NULL,
  `Matricola` VARCHAR(16) NOT NULL,
  `AnnoImm` YEAR NOT NULL,
  `password` varchar(45) NOT NULL,
  `Telefono` INT DEFAULT NULL,
  PRIMARY KEY (`Mail`))
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `DOCENTE` (
  `Nome` VARCHAR(45) NOT NULL,
  `Cognome` VARCHAR(45) NOT NULL,
  `Mail` VARCHAR(45) NOT NULL,
  `Corso` VARCHAR(45) NOT NULL,
  `Dipartimento` VARCHAR(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  `Telefono` INT DEFAULT NULL,
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
  `VisualizzaRisposte` int DEFAULT 0,
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
  `IsPK` BOOLEAN DEFAULT 1,
  PRIMARY KEY (`IDTabella`, `Nome`),
  CONSTRAINT `FK_Attributo_IDTabella`
    FOREIGN KEY (`IDTabella`)
    REFERENCES `TABELLA` (`ID`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `CODICE` (
  `ID` INT NOT NULL,
  `Output` VARCHAR(45) DEFAULT NULL,
  `IDTest` INT NOT NULL,
  `Difficolta` INT NOT NULL,
  PRIMARY KEY (`ID`, `IDTest`),
  CONSTRAINT `FK_Codice_IDTest`
    FOREIGN KEY (`IDTest`)
    REFERENCES `TEST` (`ID`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `GALLERIA` (
  `IDTest` INT NOT NULL,
  `Foto` varchar(45) NOT NULL, -- da modificare
  PRIMARY KEY (`IDTest`, `Foto`),
  CONSTRAINT `FK_Galleria_IDTest`
    FOREIGN KEY (`IDTest`)
    REFERENCES `TEST` (`ID`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `MESSAGGIO_DOCENTE` (
  `Titolo` VARCHAR(45) NOT NULL,
  `Testo` VARCHAR(45) NOT NULL,
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
  `Testo` VARCHAR(45) NOT NULL,
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
  `ID` INT NOT NULL AUTO_INCREMENT,
  `Descrizione` SMALLINT NOT NULL,
  `Difficoltà` SMALLINT NOT NULL,
  `NumRisposte` INT NOT NULL DEFAULT 0,
  `IDTest` INT NOT NULL,
  PRIMARY KEY (`ID`, `IDTest`),
  CONSTRAINT `FK_SceltaMultipla_IDTest`
    FOREIGN KEY (`IDTest`)
    REFERENCES `TEST` (`ID`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `SCELTA` ( -- le possibili scelte dei quesiti scelta multipla
  `ID` INT NOT NULL AUTO_INCREMENT,
  `Testo` VARCHAR(45) NULL,
  `IDTest` INT NOT NULL,
  `IDScMult` INT NOT NULL,
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

CREATE TABLE IF NOT EXISTS `RISPOSTA_SCELTA` ( -- tabella risposta dello studente quesito multiplo
  `Studente` varchar(45) not null,
  `IDDomanda` INT NOT NULL,
  `IDRisposta` int,
  `IDTest` INT NOT NULL,
  PRIMARY KEY (`Studente`, `IDDomanda`,`IDRisposta`),
  CONSTRAINT `FK_RispostaScelta_IDTest`
    FOREIGN KEY (`IDTest`)
    REFERENCES `TEST` (`ID`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `FK_RispostaScelta_IDRisposta`
    FOREIGN KEY (`IDRisposta`)
    REFERENCES `SCELTA` (`ID`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,  
  CONSTRAINT `FK_RispostaScelta_IDDomanda`
    FOREIGN KEY (`IDDomanda`)
    REFERENCES `SCELTA_MULTIPLA` (`ID`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `FK_RispostaScelta_Studente`
    FOREIGN KEY (`Studente`)
    REFERENCES `Studente` (`Mail`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
    )
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `RISPOSTA_CODICE` ( -- tabella risposta dello studente a quesito codice
  `Studente` varchar(45) not null,
  `IDDomanda` INT NOT NULL,
  `CodiceRisposta` varchar(500),
  `IDTest` INT NOT NULL,
  PRIMARY KEY (`Studente`, `IDDomanda`,`IDTest`),
  CONSTRAINT `FK_RispostaCodice_IDTest`
    FOREIGN KEY (`IDTest`)
    REFERENCES `TEST` (`ID`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `FK_RispostaCodice_IDDomanda`
    FOREIGN KEY (`IDDomanda`)
    REFERENCES `CODICE` (`ID`)
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
  `Stato` VARCHAR(45) NOT NULL DEFAULT 'Da iniziare',
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