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


DELIMITER ;