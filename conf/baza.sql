CREATE DATABASE IF NOT EXISTS ep_trgovina;
USE ep_trgovina;
DROP TABLE IF EXISTS uporabnik;
CREATE TABLE uporabnik (
    id INT NOT NULL AUTO_INCREMENT,
    vloga VARCHAR(45) NOT NULL,

    ime VARCHAR(45) NOT NULL,
    priimek VARCHAR(45) NOT NULL,
    email VARCHAR(45) NOT NULL,
    geslo VARCHAR(256) NOT NULL,
    
    naslov VARCHAR(500),
    telefon VARCHAR(45),
    
    PRIMARY KEY (id)
);


DELIMITER $$
CREATE TRIGGER check_vloga BEFORE INSERT ON uporabnik
FOR EACH ROW
BEGIN
    IF NEW.vloga NOT IN  ('administrator', 'prodajalec', 'stranka') THEN
        SIGNAL SQLSTATE '45000';
    END IF;
    
    IF NEW.vloga IN ('stranka') AND (NEW.naslov IS NULL OR NEW.telefon IS NULL) THEN
        SIGNAL SQLSTATE '45000';
    END IF;
END;$$
DELIMITER ;

INSERT INTO uporabnik (vloga, ime, priimek, email, geslo) VALUES ('administrator', 'Janez', 'Novak', 'jno@mail.com', 'geslo123');
INSERT INTO uporabnik (vloga, ime, priimek, email, geslo, naslov, telefon) VALUES ('stranka', 'Johnny', 'Bravo', 'jbravo@mail.com', 'heymomma', 'vecna 123, lj', '0123456789');
INSERT INTO uporabnik (vloga, ime, priimek, email, geslo, naslov, telefon) VALUES ('stranka', 'Johnny', 'Bravo', 'jbravo@mail.com', 'heymomma', NULL, '0123456789');


SELECT * FROM uporabnik;