DELIMITER //
CREATE TRIGGER ViewAnswersConclusion AFTER UPDATE ON test
    FOR EACH ROW BEGIN
    IF NEW.VisualizzaRisposte = TRUE THEN
    UPDATE Svolgimento
    SET svolgimento.Stato = 'Concluso'
    WHERE svolgimento.IDTest = NEW.ID;
END IF;
END

DELIMITER ;