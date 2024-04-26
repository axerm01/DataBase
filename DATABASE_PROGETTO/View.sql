CREATE VIEW ClassificaTestCompletati AS
SELECT
    Studente.Matricola,
    COUNT(*) AS Numero
FROM
    Svolgimento
        JOIN
    Studente ON Svolgimento.MailStudente = Studente.Mail
WHERE
    Svolgimento.Stato = 'Closed'
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
