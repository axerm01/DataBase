CREATE TABLE Gatti (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    NomeRazza VARCHAR(100),
    Nome VARCHAR(50),
    Colore VARCHAR(50),
    Peso DECIMAL(5, 2)
);
INSERT INTO Gatti (NomeRazza, Nome, Colore, Peso)
VALUES
    ('Siamense', 'Luna', 'Seal Point', 4.2),
    ('Persiano', 'Felix', 'Bianco', 5.8),
    ('Maine Coon', 'Max', 'Blu Tabby', 8.5),
    ('Ragdoll', 'Bella', 'Blue Bicolor', 4.0),
    ('Scottish Fold', 'Leo', 'Crema e Bianco', 6.2),
    ('Sphynx', 'Oliver', 'Nudo', 3.7),
    ('Bengala', 'Milo', 'Rosso Marmorizzato', 5.5),
    ('Siamese', 'Mia', 'Chocolate Point', 4.0),
    ('British Shorthair', 'Simba', 'Grigio', 7.2),
    ('Persiano', 'Sophie', 'Blu e Bianco', 5.4),
    ('Maine Coon', 'Charlie', 'Tabby', 8.0),
    ('Ragdoll', 'Lily', 'Blue Bicolor', 4.2),
    ('Scottish Fold', 'Mocha', 'Fawn', 6.5),
    ('Sphynx', 'Lola', 'Calico', 3.8),
    ('Bengala', 'Oscar', 'Marmorizzato', 5.0),
    ('Siamese', 'Milo', 'Lilac Point', 4.1),
    ('British Shorthair', 'Bella', 'Cinzia', 7.8),
    ('Persiano', 'Zoe', 'Crema', 5.7),
    ('Maine Coon', 'Leo', 'Nero Tabby', 9.2),
    ('Ragdoll', 'Ruby', 'Blue Bicolor', 4.5),
    ('Scottish Fold', 'Sam', 'Fawn e Bianco', 6.8),
    ('Sphynx', 'Daisy', 'Sable', 3.5),
    ('Bengala', 'Max', 'Leopardato', 6.0),
    ('Siamese', 'Lily', 'Chocolate Point', 4.3),
    ('British Shorthair', 'Milo', 'Blu e Bianco', 7.0),
    ('Persiano', 'Sophie', 'Rosso e Bianco', 5.9),
    ('Maine Coon', 'Oliver', 'Tabby', 8.3),
    ('Ragdoll', 'Mia', 'Lynx Point', 4.8),
    ('Scottish Fold', 'Leo', 'Fawn', 6.0),
    ('Sphynx', 'Mocha', 'Bicolore', 3.6);
