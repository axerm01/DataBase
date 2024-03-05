-- drop
CALL DropStudente('mario.rossi@example.com');
CALL DropStudente('giovanni.bianchi@example.com');
CALL DropStudente('francesca.verdi@example.com');
CALL DropStudente('luca.neri@example.com');
CALL DropStudente('studente@example.com');
-- create
CALL CreateStudente('Mario', 'Rossi', 'mario.rossi@example.com','1234567890123456' ,2001, '1234567890');
CALL CreateStudente('Giovanni', 'Bianchi', 'giovanni.bianchi@example.com', '2345678901234567',2002, '876543210');
CALL CreateStudente('Francesca', 'Verdi', 'francesca.verdi@example.com', '3456789012345678',2003, '1122334455');
CALL CreateStudente('Luca', 'Neri', 'luca.neri@example.com', '4567890123456789',2004, '988776655');
CALL CreateStudente('Pato', 'blu', 'studente@example.com', '5678901234567890',2005, '234567890');