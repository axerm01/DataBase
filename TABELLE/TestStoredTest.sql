-- Inserisci un nuovo test
CALL CreateTest('Test di Matematica', 'mario.Bianchi@example.com', 1);

-- Visualizza tutti i test associati a un docente
CALL ViewAllTest('mario.Bianchi@example.com');

-- Inserisci un altro test
CALL CreateTest('Test di Storia', 'mario.Bianchi@example.com', 0);

-- Visualizza tutti i test associati a un docente dopo l'inserimento del secondo test
CALL ViewAllTest('mario.Bianchi@example.com');

-- Visualizza un test specifico
CALL ViewTest(1);

-- Elimina un test specifico
CALL DropTest(1);

-- Visualizza tutti i test associati a un docente dopo l'eliminazione del primo test
CALL ViewAllTest('mario.Bianchi@example.com');

-- Elimina un test specifico utilizzando il titolo e l'email del docente
CALL DropTest2('Test di Storia', 'mario.Bianchi@example.com');

-- Visualizza tutti i test associati a un docente dopo l'eliminazione del secondo test
CALL ViewAllTest('mario.Bianchi@example.com');
