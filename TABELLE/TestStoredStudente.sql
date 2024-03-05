Delete
From studente 
where (Mail = 'mario.Bianchi@example.com');

-- Test per la procedura 'Createstudente'
CALL Createstudente('Mario', 'Rossi', 'mario.rossi@example.com', 'Informatica', 2001, '1234567890');
SELECT * FROM studente WHERE Mail = 'mario.rossi@example.com';

-- Test per la procedura 'Dropstudente'
CALL Dropstudente('mario.rossi@example.com');
SELECT * FROM studente WHERE Mail = 'mario.rossi@example.com';

-- Test per la procedura 'Updatestudente'
CALL Createstudente('Mario', 'Rossi', 'mario.Bianchi@example.com', 'Informatica', 2001, '1234567890');
CALL Updatestudente('Mario', 'Bianchi', 'mario.Bianchi@example.com', 'Matematica', 2004, '0987654321');
SELECT * FROM studente WHERE Mail = 'mario.Bianchi@example.com';

-- Test per la procedura 'UpdatestudenteTel'
CALL UpdatestudenteTel('mario.Bianchi@example.com', '879875987');
SELECT * FROM studente WHERE Mail = 'mario.Bianchi@example.com';

-- Test per la procedura 'ViewAllDocenti'
CALL ViewAllStudenti();

-- Test per la procedura 'Viewstudente'
CALL Viewstudente('mario.Bianchi@example.com');
