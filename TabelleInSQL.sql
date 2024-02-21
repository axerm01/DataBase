CREATE TABLE IF NOT EXISTS `tabelleprogetto`.`DOCENTE` (
  `Nome` VARCHAR(45) NOT NULL,
  `Cognome` VARCHAR(45) NOT NULL,
  `Mail` VARCHAR(45) NOT NULL,
  `Corso` VARCHAR(45) NOT NULL,
  `Dipartimento` VARCHAR(45) NOT NULL,
  `Telefono` INT(10) NULL,
  PRIMARY KEY (`Mail`))
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `tabelleprogetto`.`TABELLA` (
  `ID` INT NOT NULL,
  `MailProfessore` VARCHAR(45) NULL,
  `Nome` VARCHAR(45) NULL,
  `DataCreazione` DATE NULL,
  `NumRighe` SMALLINT NULL,
  PRIMARY KEY (`ID`),
  CONSTRAINT `FK_Tabella_MailDocente`
    FOREIGN KEY (`MailProfessore`)
    REFERENCES `tabelleprogetto`.`DOCENTE` (`Mail`))
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `tabelleprogetto`.`ATTRIBUTO` (
  `IDTabella` INT NOT NULL,
  `Nome` VARCHAR(45) NOT NULL,
  `Tipo` VARCHAR(45) NULL,
  `IsPK` VARCHAR(45) NULL,
  PRIMARY KEY (`IDTabella`, `Nome`),
  CONSTRAINT `FK_Attributo_IDTabella`
    FOREIGN KEY (`IDTabella`)
    REFERENCES `tabelleprogetto`.`TABELLA` (`ID`))
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `tabelleprogetto`.`TEST` (
  `ID` INT NOT NULL AUTO_INCREMENT,
  `Titolo` VARCHAR(45) NOT NULL,
  `DataCreazione` DATE,
  `VisualizzaRisposte` TINYINT NULL,
  `MailDocente` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`ID`),
  CONSTRAINT `FK_Test_MailDocente`
    FOREIGN KEY (`MailDocente`)
    REFERENCES `tabelleprogetto`.`DOCENTE` (`Mail`))
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `tabelleprogetto`.`CODICE` (
  `ID` INT NOT NULL,
  `Output` VARCHAR(45) NULL,
  `IDTest` INT NOT NULL,
  PRIMARY KEY (`ID`, `IDTest`))
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `tabelleprogetto`.`GALLERIA` (
  `IDTest` INT NOT NULL,
  `Foto` int NOT NULL,
  PRIMARY KEY (`IDTest`, `Foto`),
  CONSTRAINT `FK_Galleria_IDTest`
    FOREIGN KEY (`IDTest`)
    REFERENCES `tabelleprogetto`.`TEST` (`ID`))
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `tabelleprogetto`.`MESSAGGIO_DOCENTE` (
  `Titolo` INT NOT NULL,
  `Testo` VARCHAR(45) NULL,
  `Data` DATE NOT NULL,
  `IDTest` INT NOT NULL,
  `MailDocente` VARCHAR(45) NULL,
  PRIMARY KEY (`IDTest`, `Data`),
  CONSTRAINT `FK_MessaggioDocente_IDTest`
    FOREIGN KEY (`IDTest`)
    REFERENCES `tabelleprogetto`.`TEST` (`ID`),
  CONSTRAINT `FK_MessaggioDocente_MailDocente`
    FOREIGN KEY (`MailDocente`)
    REFERENCES `tabelleprogetto`.`DOCENTE` (`Mail`))
    ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `tabelleprogetto`.`STUDENTE` (
  `Nome` VARCHAR(45) NOT NULL,
  `Cognome` VARCHAR(45) NOT NULL,
  `Mail` VARCHAR(45) NOT NULL,
  `Matricola` VARCHAR(16) NOT NULL,
  `AnnoImm` YEAR(4) NOT NULL,
  `Telefono` INT(10) NULL DEFAULT NULL,
  PRIMARY KEY (`Mail`))
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `tabelleprogetto`.`MESSAGGIO_STUDENTE` (
  `Titolo` VARCHAR(45) NOT NULL,
  `Testo` VARCHAR(45) NULL,
  `Data` DATE NOT NULL,
  `IDTest` INT NULL,
  `MailStudente` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`MailStudente`, `Data`),
  CONSTRAINT `FK_MessaggioStudente_IDTest`
    FOREIGN KEY (`IDTest`)
    REFERENCES `tabelleprogetto`.`TEST` (`ID`),
    CONSTRAINT `FK_MessaggioStudente_MailStudente`
    FOREIGN KEY (`MailStudente`)
    REFERENCES `tabelleprogetto`.`STUDENTE` (`Mail`))
    ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `tabelleprogetto`.`REFERENZE` (
  `IDT1` INT NOT NULL,
  `NomeAttributo1` VARCHAR(45) NOT NULL,
  `IDT2` INT NOT NULL,
  `NomeAttributo2` VARCHAR(45) NOT NULL,
 PRIMARY KEY (`IDT1`, `NomeAttributo1`, `IDT2`, `NomeAttributo2`),
  CONSTRAINT `FK_Referenze_IDTab1`
    FOREIGN KEY (`IDT1`, `NomeAttributo1`)
    REFERENCES `tabelleprogetto`.`ATTRIBUTO` (`IDTabella`, `Nome`),
  CONSTRAINT `FK_Referenze_IDTab2`
    FOREIGN KEY (`IDT2`, `NomeAttributo2`)
    REFERENCES `tabelleprogetto`.`ATTRIBUTO` (`IDTabella`, `Nome`))
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `tabelleprogetto`.`SCELTAMULTIPLA` (
  `ID` INT NOT NULL,
  `Descrizione` SMALLINT NULL,
  `Difficoltà` VARCHAR(45) NULL,
  `NumRisposte` INT NULL,
  `IDTest` INT NOT NULL,
  PRIMARY KEY (`ID`, `IDTest`),
  CONSTRAINT `FK_SceltaMultipla_IDTest`
    FOREIGN KEY (`IDTest`)
    REFERENCES `tabelleprogetto`.`TEST` (`ID`))
    ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `tabelleprogetto`.`SCELTA` (
  `ID` INT NOT NULL,
  `Testo` VARCHAR(45) NULL,
  `IDTest` INT NOT NULL,
  `IDScMult` INT NOT NULL,
  PRIMARY KEY (`ID`, `IDTest`, `IDScMult`),
  CONSTRAINT `FK_Scelta_IDTest`
    FOREIGN KEY (`IDTest`)
    REFERENCES `tabelleprogetto`.`TEST` (`ID`),  
    CONSTRAINT `FK_Scelta_IDScMult`
    FOREIGN KEY (`IDScMult`)
    REFERENCES `tabelleprogetto`.`SCELTAMULTIPLA` (`ID`))
    ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `tabelleprogetto`.`SVOLGIMENTO` (
  `MailStudente` VARCHAR(45) NOT NULL,
  `Stato` VARCHAR(45) NULL,
  `DataPrimaRisposta` DATE NULL DEFAULT NULL,
  `DataUltimaRisposta` DATE NULL DEFAULT Null,
  `IDTest` INT NOT NULL,
  PRIMARY KEY (`MailStudente`, `IDTest`),
  CONSTRAINT `FK_Svolgimento_Mail`
    FOREIGN KEY (`MailStudente`)
    REFERENCES `tabelleprogetto`.`STUDENTE` (`Mail`),
    CONSTRAINT `FK_Svolgimento_IDTest`
    FOREIGN KEY (`IDTest`)
    REFERENCES `tabelleprogetto`.`TEST` (`ID`))