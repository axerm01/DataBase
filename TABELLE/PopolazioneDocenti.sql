-- drop
CALL DropDocente('mario.rossi@example.com');
CALL DropDocente('giovanni.bianchi@example.com');
CALL DropDocente('francesca.verdi@example.com');
CALL DropDocente('luca.neri@example.com');
-- create
CALL CreateDocente('Mario', 'Rossi', 'mario.rossi@example.com', 'Informatica', 'Dipartimento di Informatica', 1234567890);
CALL CreateDocente('Giovanni', 'Bianchi', 'giovanni.bianchi@example.com', 'Matematica', 'Dipartimento di Matematica', 876543210);
CALL CreateDocente('Francesca', 'Verdi', 'francesca.verdi@example.com', 'Fisica', 'Dipartimento di Fisica', 1122334455);
CALL CreateDocente('Luca', 'Neri', 'luca.neri@example.com', 'Chimica', 'Dipartimento di Chimica', 988776655);