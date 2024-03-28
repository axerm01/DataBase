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
DELIMITER //
CREATE PROCEDURE `CreateCodice`(
in IDTestAtt int,
in OutputAtt varchar(45)
)
BEGIN
INSERT INTO Codice (IDTest,Output)
VALUES (IDTestAtt,OutputAtt);
END //

CREATE PROCEDURE `DropCodice`(
in IDAtt int
)
BEGIN
DELETE FROM Codice
WHERE ID = IDAtt;
END //

CREATE PROCEDURE `ViewAllCodice`(in IDTestAtt int)
BEGIN
SELECT*
FROM codice
WHERE IDTest = IDTestAtt;
END //

CREATE PROCEDURE `ViewCodice`(in IDAtt int)
BEGIN
SELECT*
FROM codice
WHERE ID = IDAtt;
END //

DELIMITER ;
DELIMITER //

CREATE PROCEDURE `CreateDocente`(
in NomeAtt text,
in CognomeAtt varchar(45),
in MailAtt text,
in CorsoAtt varchar(16),
in DipartimentoAtt text,
in TelefonoAtt int,
in PasswordAtt varchar(16)  
)
BEGIN
INSERT INTO docente (Nome, Cognome ,Mail ,Corso, Dipartimento, Telefono,Password)
VALUES (NomeAtt, CognomeAtt ,MailAtt ,CorsoAtt, DipartimentoAtt, TelefonoAtt,PasswordAtt);
END //

CREATE PROCEDURE `DropDocente`(
in MailParam text
)
BEGIN
DELETE FROM docente
WHERE Mail = MailParam;
END //

CREATE PROCEDURE `CheckDocente` (
    IN MailAtt VARCHAR(45),
    IN PasswordAtt VARCHAR(16),
    OUT Risultato BOOLEAN
)
BEGIN
    SET Risultato = FALSE;
    IF EXISTS (
        SELECT *
        FROM Docente
        WHERE Mail = MailAtt AND password = PasswordAtt
    ) THEN
        SET Risultato = TRUE;
    END IF;
END//
  
CREATE PROCEDURE `UpdateDocente`(
in NomeAtt text,
in CognomeAtt text,
in MailAtt text,
in CorsoAtt varchar(16),
in DipartimentoAtt text,
in TelefonoAtt int)
BEGIN
UPDATE docente
SET 
Nome = NomeAtt,
Cognome = CognomeAtt,
Dipartimento = DipartimentoAtt, 
Corso = CorsoAtt, 
Telefono = TelefonoAtt
WHERE Mail = MailAtt;
END //

CREATE PROCEDURE `UpdateDocenteTel`(
in MailAtt text,
in Tel int)
BEGIN
UPDATE docente
SET Telefono = Tel
WHERE Mail = MailAtt;
END //

CREATE PROCEDURE `ViewAllDocenti`()
BEGIN
SELECT*
FROM docente;
END //

CREATE PROCEDURE `ViewDocente`(in Mail text)
BEGIN
SELECT * 
FROM docente
WHERE docente.Mail = Mail;
END //

DELIMITER ;
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
DELIMITER //

CREATE PROCEDURE `CreateMessaggioDocente`(
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

CREATE PROCEDURE `DropMessaggioDocente`(
in DataAtt DATETIME,
in IDTestAtt int
)
BEGIN
DELETE FROM messaggio_docente
WHERE 
(Data = DataAtt and
IDTest = IDTestAtt)
;
END //

CREATE PROCEDURE `ViewMessaggioDocente`(
in DataAtt DATETIME,
in IDTestAtt int
)
BEGIN
SELECT *
FROM messaggio_docente
WHERE ( 
Data = DataAtt and
IDTest = IDTestAtt
);
END //

CREATE PROCEDURE `ViewMessaggiDocente`(
in MailDocenteAtt int
)
BEGIN
SELECT *
FROM messaggio_docente
WHERE
MailDocente = MailDocenteAtt;
END //
DELIMITER //

CREATE PROCEDURE `CreateMessaggioStudente`(
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

CREATE PROCEDURE `DropMessaggioStudente`(
in DataAtt DATETIME,
in IDTestAtt int
)
BEGIN
DELETE FROM messaggio_Studente
WHERE 
(Data = DataAtt and
IDTest = IDTestAtt)
;
END //

CREATE PROCEDURE `ViewMessaggioStudente`(
in DataAtt DATETIME,
in IDTestAtt int
)
BEGIN
SELECT *
FROM messaggio_Studente
WHERE ( 
Data = DataAtt and
IDTest = IDTestAtt
);
END //

CREATE PROCEDURE `ViewMessaggiStudente`(
in MailStudenteAtt int
)
BEGIN
SELECT *
FROM messaggio_Studente
WHERE
MailStudente = MailStudenteAtt;
END //DELIMITER //

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
DELIMITER //
CREATE PROCEDURE `CreateSceltaMultipla`(
in DescrizioneAtt varchar(45),
in IDTestAtt int,
in NumRisposteAtt int,
in DifficoltaAtt int
)
BEGIN
INSERT INTO Scelta_Multipla (Descrizione,IDTest,NumRisposte,Difficolta)
VALUES (DescrizioneAtt,IDTestAtt,NumRisposteAtt,DifficoltaAtt);
END //

CREATE PROCEDURE `DropSceltaMultipla`(
in IDTestAtt int,
in DescrizioneAtt varchar(45)
)
BEGIN
DELETE FROM Scelta_Multipla
WHERE IDTest = IDTestAtt and Descrizione = DescrizioneAtt;
END //

CREATE PROCEDURE `ViewSceltaMultipla`(in IDTestAtt int,
in DescrizioneAtt varchar(45))
BEGIN
SELECT*
FROM Scelta_Multipla
WHERE IDTest = IDTestAtt and Descrizione = DescrizioneAtt;
END //

DELIMITER ;
DELIMITER //

CREATE PROCEDURE `CreateStudente`(
in NomeAtt text,
in CognomeAtt varchar(45),
in MailAtt text,
in MatricolaAtt varchar(16),
in AnnoImmAtt text,
in TelefonoAtt int,
in PasswordAtt varchar(16)
)
BEGIN
INSERT INTO Studente (Nome, Cognome ,Mail ,Matricola, AnnoImm, Telefono,Password)
VALUES (NomeAtt, CognomeAtt ,MailAtt ,MatricolaAtt, AnnoImmAtt, TelefonoAtt,PasswordAtt);
END //

CREATE PROCEDURE `DropStudente`(
in MailParam text
)
BEGIN
DELETE FROM Studente
WHERE Mail = MailParam;
END //

CREATE PROCEDURE `UpdateStudente`(
in NomeAtt text,
in CognomeAtt text,
in MailAtt text,
in MatricolaAtt varchar(16),
in AnnoImmAtt text,
in TelefonoAtt int)
BEGIN
UPDATE Studente
SET 
Nome = NomeAtt,
Cognome = CognomeAtt,
AnnoImm = AnnoImmAtt, 
Matricola = MatricolaAtt, 
Telefono = TelefonoAtt
WHERE Mail = MailAtt;
END //

CREATE PROCEDURE `CheckStudente` (
    IN MailAtt VARCHAR(45),
    IN PasswordAtt VARCHAR(16),
    OUT Risultato BOOLEAN
)
BEGIN
    SET Risultato = FALSE;
    IF EXISTS (
        SELECT *
        FROM studente
        WHERE Mail = MailAtt AND password = PasswordAtt
    ) THEN
        SET Risultato = TRUE;
    END IF;
END//

  
CREATE PROCEDURE `UpdateStudenteTel`(
in MailAtt text,
in Tel int)
BEGIN
UPDATE Studente
SET Telefono = Tel
WHERE Mail = MailAtt;
END //

CREATE PROCEDURE `ViewAllStudenti`()
BEGIN
SELECT*
FROM Studente;
END //

CREATE PROCEDURE `ViewStudente`(in Mail text)
BEGIN
SELECT * 
FROM Studente
WHERE Studente.Mail = Mail;
END //

DELIMITER ;
DELIMITER //
CREATE PROCEDURE `CreateSvolgimento`(
in MailStudenteAtt varchar(45),
in StatoAtt varchar(45),
in DataPrimaRispostaAtt datetime,
in DataUltimaRispostaAtt datetime,
in IDTestAtt int
)
BEGIN
INSERT INTO Scelta_Multipla (MailStudente,Stato,DataPrimaRisposta,DataUltimaRisposta,IDTest)
VALUES (MailStudenteAtt,StatoAtt,DataPrimaRispostaAtt,DataUltimaRispostaAtt,IDTestAtt);
END //

CREATE PROCEDURE `DropSvolgimento`(
in IDTestAtt int,
in MailStudenteAtt varchar(45)
)
BEGIN
DELETE FROM Svolgimento
WHERE IDTest = IDTestAtt and MailStudente = MailStudenteAtt;
END //

CREATE PROCEDURE `ViewSvolgimento`(in IDTestAtt int,
in MailStudenteAtt varchar(45))
BEGIN
SELECT*
FROM Scelta_Multipla
WHERE IDTest = IDTestAtt and MailStudente = MailStudenteAtt;
END //

CREATE PROCEDURE `UpdateInizioSvolgimento`(in IDTestAtt int,
in MailStudenteAtt varchar(45))
BEGIN
UPDATE svolgimento
SET DataPrimaRisposta = localtime()
WHERE IDTest = IDTestAtt and MailStudente = MailStudenteAtt;
END //

CREATE PROCEDURE `UpdateFineSvolgimento`(in IDTestAtt int,
in MailStudenteAtt varchar(45))
BEGIN
UPDATE svolgimento
SET DataUltimaRisposta = localtime()
WHERE IDTest = IDTestAtt and MailStudente = MailStudenteAtt;
END //

CREATE PROCEDURE `UpdateStatoSvolgimento`(in IDTestAtt int,
in MailStudenteAtt varchar(45), in StatoAtt varchar(45))
BEGIN
UPDATE svolgimento
SET Stato = StatoAtt
WHERE IDTest = IDTestAtt and MailStudente = MailStudenteAtt;
END //
DELIMITER ;
DELIMITER //

CREATE PROCEDURE `CreateTabella`(
in MailAtt varchar(45),
in NomeAtt varchar(45),
in DataAtt date,
in NumRigheAtt smallint)
BEGIN
    INSERT INTO tabella (MailProfessore, Nome, DataCreazione, NumRighe)
    VALUES (MailAtt, NomeAtt, DataAtt, NumRigheAtt);
END//

CREATE PROCEDURE `DropTabella`(
in ID smallint,
in mail varchar(45))
BEGIN
DELETE FROM tabella
WHERE (
tabella.ID = ID and
tabella.MailProfessore = mail);
END//

CREATE PROCEDURE `ViewAllTabelle`(in mail varchar(45))
BEGIN
SELECT *
FROM tabella
WHERE 
MailProfessore = mail;
END//

CREATE PROCEDURE `ViewTabella`(in ID smallint,in mail varchar(45))
BEGIN
SELECT *
FROM tabella
WHERE (
tabella.ID = ID and
MailProfessore = mail);
END//

DELIMITER ;
DELIMITER //

CREATE PROCEDURE `CreateTest`(
in TitoloAtt varchar(45),
in VisualizzaRisposteAtt int,
in MailDocenteAtt varchar(45)
)
BEGIN
Declare orario DATETIME;
set orario = NOW();
INSERT INTO test (Titolo,DataCreazione,VisualizzaRisposte,MailDocente)
VALUES (TitoloAtt,orario,VisualizzaRisposteAtt,MailDocenteAtt);
END //

CREATE PROCEDURE `UpdateVisualizzaRisposteTest`(
in TitoloAtt varchar(45),
in VisualizzaRisposteAtt boolean,
in MailDocenteAtt varchar(45)
)
BEGIN
UPDATE test
SET VisualizzaRisposte = VisualizzaRisposteAtt
WHERE MailDocente = MailDocenteAtt and Titolo = TitoloAtt;
END //

CREATE PROCEDURE `ViewTest`(
in IDAtt int
)
BEGIN
SELECT *
FROM Test
WHERE ID = IDAtt;
END //

CREATE PROCEDURE `DropTest`(
in IDAtt int
)
BEGIN
DELETE FROM Test
WHERE ID = IDAtt;
END //

CREATE PROCEDURE  `DropAllTest`()
BEGIN 
DELETE FROM Test
where id NOT LIKE -1;
END//

CREATE PROCEDURE `ViewRispostaStudente`( --nuovo
in StudenteAtt varchar(45), IDDomandaAtt int ,IDTestAtt int
)
BEGIN
SELECT IDRisposta
FROM RISPOSTA_SCELTA
WHERE Studente = StudenteAtt and IDDomanda = IDDomandaAtt and IDTest = IDTestAtt;
END //

CREATE PROCEDURE `ViewCodiceStudente`( --nuovo
in StudenteAtt varchar(45), IDDomandaAtt int ,IDTestAtt int
)
BEGIN
SELECT CodiceRisposta
FROM RISPOSTA_CODICE
WHERE Studente = StudenteAtt and IDDomanda = IDDomandaAtt and IDTest = IDTestAtt;
END //

CREATE PROCEDURE `UpdateRispostaStudente`( --nuovo
in StudenteAtt varchar(45), IDDomandaAtt int ,IDRispostaAtt int
)
BEGIN
UPDATE RISPOSTA_SCELTA
SET IDRisposta = IDRispostaAtt
WHERE Studente = StudenteAtt and IDDomanda = IDDomandaAtt;
END //

CREATE PROCEDURE `UpdateCodiceStudente`( --nuovo
in StudenteAtt varchar(45), IDDomandaAtt int ,IDTestAtt int, RispostaAtt varchar(500)
)
BEGIN
UPDATE RISPOSTA_CODICE
SET CodiceRisposta = RispostaAtt
WHERE Studente = StudenteAtt and IDDomanda = IDDomandaAtt and IDTest = IDTestAtt;
END //

DELIMITER ;