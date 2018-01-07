USE ep_trgovina;

SET FOREIGN_KEY_CHECKS = 0;

TRUNCATE uporabnik;
TRUNCATE izdelek;
TRUNCATE ocena;
TRUNCATE slika;
TRUNCATE narocilo;
TRUNCATE narocilo_vsebuje;


-- -----------------------------------------------------
-- UPORABNIKI
-- -----------------------------------------------------

-- ADMIN
INSERT INTO uporabnik (vloga, ime, priimek, email, geslo)
VALUES ('administrator', 'Janez', 'Novak', 'aj@ep.si', '$2y$10$j8Ur7vBUFDXmr9cUyiBAu.ecrAl3VzAmAWwAuOLLBy0u6h9rLMsTq'); -- admin

-- PRODAJALCI
INSERT INTO uporabnik (vloga, ime, priimek, email, geslo)
VALUES ('prodajalec', 'Prodajalec', 'Andraz', 'pa@ep.si', '$2y$10$xClrznsjdlBvKvzmVLiEfO03/9ZQ5Ywlks55XoSpAHMqiXA0Xelsi'); -- ep

INSERT INTO uporabnik (vloga, ime, priimek, email, geslo)
VALUES ('prodajalec', 'Prodajalec', 'Matevz', 'pm@ep.si', '$2y$10$xClrznsjdlBvKvzmVLiEfO03/9ZQ5Ywlks55XoSpAHMqiXA0Xelsi'); -- ep


-- STRANKE
INSERT INTO uporabnik (vloga, ime, priimek, email, geslo, naslov, telefon)
VALUES ('stranka', 'Johnny', 'Bravo', 'jbravo@mail.com', '$2y$10$kTqDDNXfTkg.K98WLyFu9.jrBMze1FZ7ieAk6lUS5H7nNcI4/t2ei', 'večšžna 123, lj', '0123456789'); -- heymomma

INSERT INTO uporabnik (vloga, ime, priimek, email, geslo, naslov, telefon)
VALUES ('stranka', 'Ivan', 'Smith', 'ivan@gmail.com', '$2y$10$zc4c3w8WnBsgwFBySYNhluSY7kT9k8CuEf92yXNitzHwIqMVxTeuK', 'Trubarjeva cesta 5, 1000 Ljubljana', '03124519'); -- ivanivanpass






-- -----------------------------------------------------
-- IZDELKI, OCENE, SLIKE, NAROCILA
-- -----------------------------------------------------

INSERT INTO izdelek(ime, cena, opis) VALUES ('Mountain Dew', '0.60', 'Get your MLG on');
INSERT INTO izdelek(ime, cena, opis) VALUES ('Capita DOA Snowboard', '439.90', 'Take on the local hill with this beast');
INSERT INTO izdelek(ime, cena, opis) VALUES ('USB key - 128GB', '22.99', 'Expand your storage with this.');

INSERT INTO ocena(uporabnik_id, izdelek_id, ocena) VALUES (1,2,5);
INSERT INTO slika(path, izdelek_id) VALUES ('PotDoSlike.png', 2);

INSERT INTO ocena(uporabnik_id, izdelek_id, ocena) VALUES (2,2,4);
INSERT INTO izdelek(ime, cena, opis) VALUES ('Xiaomi Mi Robot Vacuum', '274.59', 'Forget about vacuum cleaning. Get a robot.');

INSERT INTO slika(path, izdelek_id) VALUES ('PotDoDrugeSlikeIstegaIzdelka', 2);

INSERT INTO narocilo(datum, uporabnik_id, stanje, stornirano, postavka) VALUES ('2016-1-23' , 4, 'oddano', null, '714.89');
INSERT INTO narocilo_vsebuje(kolicina, izdelek_id, narocilo_id, cena) VALUES (1, 2, 1, '442.3');
INSERT INTO narocilo_vsebuje(kolicina, izdelek_id, narocilo_id, cena) VALUES (4, 1, 1, '274.59');
INSERT INTO narocilo(datum, uporabnik_id, stanje, stornirano, postavka) VALUES ('2016-7-07' , 2, 'oddano', null, '274.59');
INSERT INTO narocilo_vsebuje(kolicina, izdelek_id, narocilo_id, cena) VALUES (1, 4, 2, '274.59');



-- Izdelki
-- Generirano na http://www.mockaroo.com/
insert into izdelek (ime, cena, opis) values ('Ecolab - Medallion', '28.97', 'Excision of Inferior Mesenteric Vein, Open Approach');
insert into izdelek (ime, cena, opis) values ('Food Colouring - Green', '45.31', 'Destruction of Right Thorax Bursa and Ligament, Open Approach');
insert into izdelek (ime, cena, opis) values ('Spinach - Packaged', '33.59', 'Drainage of Left Lacrimal Duct, Open Approach');
insert into izdelek (ime, cena, opis) values ('Grapes - Green', '36.65', 'Fragmentation in Anus, Via Natural or Artificial Opening');
insert into izdelek (ime, cena, opis) values ('Cocoa Butter', '43.80', 'Drainage of Azygos Vein with Drainage Device, Percutaneous Endoscopic Approach');
insert into izdelek (ime, cena, opis) values ('Grouper - Fresh', '33.15', 'Dilation of Left Femoral Artery with Four or More Intraluminal Devices, Open Approach');
insert into izdelek (ime, cena, opis) values ('Lid - Translucent, 3.5 And 6 Oz', '34.51', 'Restriction of Upper Vein with Intraluminal Device, Open Approach');
insert into izdelek (ime, cena, opis) values ('Vodka - Lemon, Absolut', '38.43', 'Revision of Internal Fixation Device in Left Upper Femur, Percutaneous Approach');
insert into izdelek (ime, cena, opis) values ('Tortillas - Flour, 12', '19.42', 'Computerized Tomography (CT Scan) of Left Upper Extremity using Low Osmolar Contrast');
insert into izdelek (ime, cena, opis) values ('Bouillion - Fish', '39.75', 'Dilation of Small Intestine with Intraluminal Device, Via Natural or Artificial Opening Endoscopic');
insert into izdelek (ime, cena, opis) values ('Pepper - Black, Crushed', '38.98', 'Revision of Radioactive Element in Mediastinum, Open Approach');
insert into izdelek (ime, cena, opis) values ('Octopus', '40.62', 'Resection of Left Lower Lung Lobe, Open Approach');
insert into izdelek (ime, cena, opis) values ('Ezy Change Mophandle', '16.41', 'Repair Left Subclavian Vein, Percutaneous Endoscopic Approach');
insert into izdelek (ime, cena, opis) values ('Sobe - Cranberry Grapefruit', '8.68', 'Extirpation of Matter from Large Intestine, Percutaneous Approach');
insert into izdelek (ime, cena, opis) values ('Bread Base - Toscano', '42.51', 'Release Left Atrium, Percutaneous Approach');
insert into izdelek (ime, cena, opis) values ('Soup - Campbells Mac N Cheese', '47.83', 'Removal of Nonautologous Tissue Substitute from Occipital-cervical Joint, Percutaneous Endoscopic Approach');
insert into izdelek (ime, cena, opis) values ('Bread - Pain Au Liat X12', '42.86', 'Repair Right Vas Deferens, Percutaneous Approach');
insert into izdelek (ime, cena, opis) values ('Bread - Focaccia Quarter', '20.66', 'Bypass Right Common Iliac Artery to Left Renal Artery, Open Approach');
insert into izdelek (ime, cena, opis) values ('Muffin - Zero Transfat', '2.93', 'Drainage of Left Pelvic Bone, Percutaneous Endoscopic Approach');
insert into izdelek (ime, cena, opis) values ('Pie Shells 10', '0.97', 'Alteration of Left Upper Leg, Percutaneous Endoscopic Approach');
insert into izdelek (ime, cena, opis) values ('Coconut - Whole', '43.92', 'Drainage of Left Internal Jugular Vein, Percutaneous Endoscopic Approach');



SET FOREIGN_KEY_CHECKS = 1;