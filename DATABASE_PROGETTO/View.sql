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



/*giuste risposta scelta*/
create view if not exists SceltePositive(
Studente, RispostePositive
) AS
select Studente, count(*)
from RISPOSTA_SCELTA
where Esito = 1
group by Studente

/*sbagliate risposta scelta*/
create view if not exists ScelteNegative(
Studente, RisposteNegative
) AS
select Studente, count(*)
from RISPOSTA_SCELTA
where Esito = 0
group by Studente

/*giuste risposta codice*/
create view if not exists CodicePositivo(
Studente, CodiciPositivi
) AS
select Studente, count(*)
from RISPOSTA_CODICE
where Esito = 1
group by Studente

/*sbagliate risposta codice*/
create view if not exists CodiceNegativo(
Studente, CodiciNegativi
) AS
select Studente, count(*)
from RISPOSTA_CODICE
where Esito = 0
group by Studente

/*restituisce la somma dei p*/
SELECT (SP.RispostePositive + CP.CodiciPositivi) AS SommaPositivi Matricola
FROM SceltePositive SP, CodicePositivo CP, CodiceNegativo CN, ScelteNegative SN, STUDENTE
WHERE CP.Studente = SP.Studente and CN.Studente = SN.Studente and SN.Studente = CP.Studente and STUDENTE.Mail = SN.Studente
Group by Matricola
Order BY count(*) desc

/*visualizzare la classifica dei quesiti in base al numero di risposte inserite dagli studenti*/
create view if not exists NumeroScMult (
    idDomanda, nRisposte
) AS
select IDDomanda, count(*)
from RISPOSTA_CODICE
GROUP BY IDDomanda

create view if not exists NumeroCodice (
    idDomanda, nRisposte
) AS
select IDDomanda, count(*)
from RISPOSTA_SCELTA
GROUP BY IDDomanda


/*query per richiamare l'ordine delle domande con più risposte*/
SELECT *
FROM (
    SELECT * FROM NumeroScMult
    UNION ALL
    SELECT * FROM NumeroCodice
) AS CombinedResults
ORDER BY nRisposte ASC;
