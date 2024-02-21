CREATE DEFINER=`root`@`localhost` PROCEDURE `DropStudente`(in MailParam text)
BEGIN
DELETE FROM studente
WHERE Mail = MailParam;
END