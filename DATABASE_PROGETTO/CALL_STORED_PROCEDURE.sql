-- DropDocente
CALL DropDocente('mario@rossi');
CALL DropDocente('luca@bianchi');
CALL DropDocente('giulia@verdi');
CALL DropDocente('andrea@nero');
CALL DropDocente('laura@giallo');

-- CreateDcente
CALL CreateDocente('mario', 'rossi', 'mario@rossi', 'fisica', 'matematica', NULL, 'zigzagoon');
CALL CreateDocente('luca', 'bianchi', 'luca@bianchi', 'chimica', 'biologia', 123, 'charmander');
CALL CreateDocente('giulia', 'verdi', 'giulia@verdi', 'italiano', 'storia', 456, 'squirtle');
CALL CreateDocente('andrea', 'nero', 'andrea@nero', 'inglese', 'geografia', 789, 'bulbasaur');
CALL CreateDocente('laura', 'giallo', 'laura@giallo', 'arte', 'musica', NULL, 'pikachu');
/*
-- CheckDocente
SET @risposta := 0;
CALL CheckDocente ('mario@rossi','zigzagoon', @risposta);
SELECT @risposta;

-- ViewAllDocenti
CALL ViewAllDocenti();

-- ViewDocente
call ViewDocente('mario@rossi');
*/
-- DropStudente
CALL DropStudente('mario@rossi');
CALL DropStudente('luca@bianchi');
CALL DropStudente('giulia@verdi');
CALL DropStudente('andrea@nero');
CALL DropStudente('laura@giallo');

-- CreateDcente
CALL CreateStudente('mario', 'rossi', 'mario@rossi', 'a', 2001, NULL, 'zigzagoon');
CALL CreateStudente('luca', 'bianchi', 'luca@bianchi', 'b', 2002, 123, 'charmander');
CALL CreateStudente('giulia', 'verdi', 'giulia@verdi', 'c', 2002, 456, 'squirtle');
CALL CreateStudente('andrea', 'nero', 'andrea@nero', 'd', 1999, 789, 'bulbasaur');
CALL CreateStudente('laura', 'giallo', 'laura@giallo', 'e', 1980, NULL, 'pikachu');

-- CheckStudente
/*
SET @risposta := 0;
CALL CheckStudente ('mario@rossi','zigzagoon', @risposta);
SELECT @risposta;

-- ViewAllStudentigalleria
CALL ViewAllStudenti();

-- ViewStudente
call ViewStudente('mario@rossi');
*/
-- DropAllTest
CALL DropAllTest();
-- DropTest
CALL DropTest(1);
-- CreateTest
CALL CreateTest('CIAO NANO',0,'Mario@rossi');
-- ViewTest
CALL ViewTest(1);