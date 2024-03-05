-- Inserisci un codice associato a un test
CALL CreateCodice(3, 'Risultato del test 1');

-- Visualizza tutti i codici associati a un test
CALL ViewAllCodice(3);

-- Inserisci un altro codice associato allo stesso test
CALL CreateCodice(3, 'Risultato del test 3');

-- Visualizza tutti i codici associati allo stesso test
CALL ViewAllCodice(3);

-- Visualizza un codice specifico
CALL ViewCodice(3);

-- Elimina un codice
CALL DropCodice(3);

-- Visualizza tutti i codici dopo l'eliminazione
CALL ViewAllCodice(3);
