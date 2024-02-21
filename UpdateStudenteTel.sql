CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateStudenteTel`(MailAtt text, Tel int)
BEGIN
UPDATE studente
SET Telefono = Tel
WHERE Mail = MailAtt;
END