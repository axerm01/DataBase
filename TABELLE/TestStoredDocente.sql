Delete
From docente 
where (Mail = 'mario.Bianchi@example.com');
Delete
From docente 
where (Mail = 'mario.Rossi@example.com');

-- Test per la procedura 'CreateDocente'
CALL CreateDocente('Mario', 'Rossi', 'mario.rossi@example.com', 'Informatica', 'Dipartimento di Informatica', 1234567890);
SELECT * FROM docente WHERE Mail = 'mario.rossi@example.com';

-- Test per la procedura 'DropDocente'
CALL DropDocente('mario.rossi@example.com');
SELECT * FROM docente WHERE Mail = 'mario.rossi@example.com';

-- Test per la procedura 'UpdateDocente'
CALL CreateDocente('Mario', 'Rossi', 'mario.Bianchi@example.com', 'Informatica', 'Dipartimento di Informatica', '1234567890');
CALL UpdateDocente('Mario', 'Bianchi', 'mario.Bianchi@example.com', 'Matematica', 'Dipartimento di Matematica', '0987654321');
SELECT * FROM docente WHERE Mail = 'mario.Bianchi@example.com';

-- Test per la procedura 'UpdateDocenteTel'
CALL UpdateDocenteTel('mario.Bianchi@example.com', '879875987');
SELECT * FROM docente WHERE Mail = 'mario.Bianchi@example.com';

-- Test per la procedura 'ViewAllDocenti'
CALL ViewAllDocenti();

-- Test per la procedura 'ViewDocente'
CALL ViewDocente('mario.Bianchi@example.com');
