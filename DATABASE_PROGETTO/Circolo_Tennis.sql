-- Creazione della tabella CircoloTennis
CREATE TABLE CircoloTennis (
    Nome_Sportivo VARCHAR(50),
    Data_Iscrizione DATE,
    Nome_Circolo VARCHAR(50),
    Numero_Campi_Tennis INT
);
INSERT INTO CircoloTennis (Nome_Sportivo, Data_Iscrizione, Nome_Circolo, Numero_Campi_Tennis)
VALUES
    ('Giuseppe Rossi', '2023-01-15', 'Tennis Club Roma', 3),
    ('Maria Bianchi', '2023-02-02', 'Circolo Tennis Milano', 5),
    ('Luca Verdi', '2023-03-10', 'Palermo Tennis Club', 4),
    ('Marco Esposito', '2023-04-05', 'Palermo Tennis Club', 4),
    ('Laura Rossi', '2023-05-12', 'Palermo Tennis Club', 4),
    ('Roberto Russo', '2023-06-20', 'Palermo Tennis Club', 4),
    ('Francesca Ferrara', '2023-07-08', 'Palermo Tennis Club', 4),
    ('Giovanni Martino', '2023-08-15', 'Palermo Tennis Club', 4),
    ('Alessandra Romano', '2023-09-23', 'Palermo Tennis Club', 4),
	('Marco Rossi', '2023-04-05', 'Tennis Club Roma', 3),
	('Paolo Verdi', '2023-04-05', 'Circolo Tennis Milano', 5),
    ('Anna Neri', '2023-05-20', 'Circolo Tennis Milano', 5),
    ('Marco Rossi', '2023-06-12', 'Circolo Tennis Milano', 5),
    ('Sara Gialli', '2023-07-08', 'Circolo Tennis Milano', 5),
    ('Luigi Bianchi', '2023-08-25', 'Circolo Tennis Milano', 5),
    ('Elena Verde', '2023-09-14', 'Circolo Tennis Milano', 5),
    ('Alessandra Verdi', '2023-05-20', 'Tennis Club Roma', 3),
    ('Fabio Bianchi', '2023-06-12', 'Tennis Club Roma', 3),
    ('Elena Gialli', '2023-07-08', 'Tennis Club Roma', 3),
    ('Paolo Neri', '2023-08-15', 'Tennis Club Roma', 3),
    ('Giovanna Marroni', '2023-09-22', 'Tennis Club Roma', 3),
    ('Alessia Gialli', '2023-12-18', 'Firenze Tennis Center', 2);