create view if not exists StatisticaTest(
    Matricola, StatoTest
) AS 
SELECT Matricola, Stato
FROM SVOLGIMENTO, STUDENTE
WHERE mail = MailStudente


SELECT Matricola
FROM Statistica
WHERE Stato = 'completo'
GROUP BY Matricola
ORDER BY COUNT(*) DESC;


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

