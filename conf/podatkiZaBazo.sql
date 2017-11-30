INSERT INTO uporabnik (vloga, ime, priimek, email, geslo) VALUES ('administrator', 'Janez', 'Novak', 'jno@mail.com', 'geslo123');
INSERT INTO uporabnik (vloga, ime, priimek, email, geslo, naslov, telefon) VALUES ('stranka', 'Johnny', 'Bravo', 'jbravo@mail.com', 'heymomma', 'večšžna 123, lj', '0123456789');


INSERT INTO izdelek(ime, cena, opis) VALUES ('Mountain Dew', '0.60', 'Get your MLG on');
INSERT INTO izdelek(ime, cena, opis) VALUES ('Capita DOA Snowboard', '439.90', 'Take on the local hill with this beast');
INSERT INTO izdelek(ime, cena, opis) VALUES ('USB key - 128GB', '22.99', 'Expand your storage with this.');

INSERT INTO ocena(uporabnik_id, izdelek_id, ocena) VALUES (1,2,5);
INSERT INTO slika(path, izdelek_id) VALUES ('PotDoSlike.png', 2);