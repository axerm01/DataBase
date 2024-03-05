-- drop
CALL DropTabella(30, 'mario.rossi@example.com');
CALL DropTabella(31, 'mario.rossi@example.com');
CALL DropTabella(32, 'mario.rossi@example.com');
CALL DropTabella(33, 'mario.rossi@example.com');
CALL DropTabella(35, 'giovanni.bianchi@example.com');
CALL DropTabella(36, 'giovanni.bianchi@example.com');
CALL DropTabella(37, 'giovanni.bianchi@example.com');
CALL DropTabella(34, 'giovanni.bianchi@example.com');
CALL DropTabella(39, 'francesca.verdi@example.com');
CALL DropTabella(40, 'francesca.verdi@example.com');
CALL DropTabella(41, 'francesca.verdi@example.com');
CALL DropTabella(38, 'francesca.verdi@example.com');
CALL DropTabella(43, 'luca.neri@example.com');
CALL DropTabella(44, 'luca.neri@example.com');
CALL DropTabella(45, 'luca.neri@example.com');
CALL DropTabella(42, 'luca.neri@example.com');

-- create
CALL CreateTabella('mario.rossi@example.com', 'Tabella1', CURDATE(), 10);
CALL CreateTabella('mario.rossi@example.com', 'Tabella2', CURDATE(), 10);
CALL CreateTabella('mario.rossi@example.com', 'Tabella3', CURDATE(), 10);
CALL CreateTabella('mario.rossi@example.com', 'Tabella4', CURDATE(), 10);
CALL CreateTabella('giovanni.bianchi@example.com', 'Tabella1', CURDATE(), 10);
CALL CreateTabella('giovanni.bianchi@example.com', 'Tabella17', CURDATE(), 10);
CALL CreateTabella('giovanni.bianchi@example.com', 'Tabella7', CURDATE(), 10);
CALL CreateTabella('giovanni.bianchi@example.com', 'Tabella4', CURDATE(), 10);
CALL CreateTabella('francesca.verdi@example.com', 'Tabella1', CURDATE(), 10);
CALL CreateTabella('francesca.verdi@example.com', 'Tabella2', CURDATE(), 10);
CALL CreateTabella('francesca.verdi@example.com', 'Tabella6', CURDATE(), 10);
CALL CreateTabella('francesca.verdi@example.com', 'Tabella4', CURDATE(), 10);
CALL CreateTabella('luca.neri@example.com', 'Tabella1', CURDATE(), 10);
CALL CreateTabella('luca.neri@example.com', 'Tabella111', CURDATE(), 10);
CALL CreateTabella('luca.neri@example.com', 'Tabella3', CURDATE(), 10);
CALL CreateTabella('luca.neri@example.com', 'Tabella4', CURDATE(), 10);