CREATE TABLE Automobile (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    NomeModello VARCHAR(100),
    Cilindrata INT,
    TipoCombustibile VARCHAR(50),
    Prezzo DECIMAL(10, 2)
);
INSERT INTO Automobile (NomeModello, Cilindrata, TipoCombustibile, Prezzo)
VALUES
    ('Toyota Camry', 2500, 'Benzina', 28000.00),
    ('Honda Accord', 2000, 'Ibrido', 32000.00),
    ('Ford Mustang', 5000, 'Benzina', 45000.00),
    ('Volkswagen Golf', 1600, 'Diesel', 26000.00),
    ('Chevrolet Malibu', 2200, 'Benzina', 30000.00),
    ('Tesla Model 3', 0, 'Elettrico', 50000.00),
    ('Nissan Altima', 2400, 'Benzina', 27000.00),
    ('BMW 3 Series', 2000, 'Benzina', 40000.00),
    ('Audi A4', 1800, 'Diesel', 38000.00),
    ('Mercedes-Benz C-Class', 2500, 'Benzina', 42000.00),
    ('Hyundai Sonata', 2000, 'Ibrido', 32000.00),
    ('Mazda6', 2500, 'Benzina', 29000.00),
    ('Subaru Legacy', 2300, 'Benzina', 31000.00),
    ('Kia Optima', 2000, 'Ibrido', 33000.00),
    ('Lexus ES', 3500, 'Benzina', 48000.00),
    ('Volvo S60', 2000, 'Ibrido', 42000.00),
    ('Jaguar XF', 3000, 'Diesel', 55000.00),
    ('Porsche Panamera', 4000, 'Benzina', 90000.00),
    ('Alfa Romeo Giulia', 2000, 'Benzina', 38000.00),
    ('Acura TLX', 2500, 'Ibrido', 35000.00),
    ('Genesis G80', 3600, 'Benzina', 47000.00),
    ('Infiniti Q50', 3000, 'Benzina', 40000.00),
    ('Cadillac CT5', 2500, 'Benzina', 42000.00),
    ('Buick Regal', 2000, 'Benzina', 33000.00),
    ('Lincoln MKZ', 2400, 'Benzina', 38000.00),
    ('Chrysler 300', 3600, 'Benzina', 40000.00),
    ('Dodge Charger', 3600, 'Benzina', 43000.00),
    ('Chevrolet Impala', 3100, 'Benzina', 32000.00),
    ('Ford Fusion', 2000, 'Ibrido', 29000.00);

