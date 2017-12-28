-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema ep_trgovina
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `ep_trgovina` ;

-- -----------------------------------------------------
-- Schema ep_trgovina
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `ep_trgovina` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ;
USE `ep_trgovina` ;

-- -----------------------------------------------------
-- Table `ep_trgovina`.`uporabnik`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ep_trgovina`.`uporabnik` ;

CREATE TABLE IF NOT EXISTS `ep_trgovina`.`uporabnik` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `vloga` VARCHAR(45) NOT NULL DEFAULT 'stranka',
  `ime` VARCHAR(45) NOT NULL,
  `priimek` VARCHAR(45) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `geslo` VARCHAR(200) NOT NULL,
  `naslov` VARCHAR(200) NULL,
  `telefon` VARCHAR(45) NULL,
  `aktiven` TINYINT(1) NOT NULL DEFAULT 1,
  UNIQUE INDEX `email_UNIQUE` (`email` ASC),
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ep_trgovina`.`izdelek`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ep_trgovina`.`izdelek` ;

CREATE TABLE IF NOT EXISTS `ep_trgovina`.`izdelek` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `ime` VARCHAR(200) NOT NULL,
  `cena` DECIMAL(10,2) NOT NULL,
  `opis` VARCHAR(1000) NULL,
  `aktiven` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ep_trgovina`.`ocena`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ep_trgovina`.`ocena` ;

CREATE TABLE IF NOT EXISTS `ep_trgovina`.`ocena` (
  `uporabnik_id` INT NOT NULL,
  `izdelek_id` INT NOT NULL,
  `ocena` INT NOT NULL,
  PRIMARY KEY (`uporabnik_id`, `izdelek_id`),
  INDEX `fk_ocena_izdelek_idx` (`izdelek_id` ASC),
  INDEX `fk_ocena_uporabnik1_idx` (`uporabnik_id` ASC),
  CONSTRAINT `fk_ocena_izdelek`
    FOREIGN KEY (`izdelek_id`)
    REFERENCES `ep_trgovina`.`izdelek` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ocena_uporabnik1`
    FOREIGN KEY (`uporabnik_id`)
    REFERENCES `ep_trgovina`.`uporabnik` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ep_trgovina`.`slika`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ep_trgovina`.`slika` ;

CREATE TABLE IF NOT EXISTS `ep_trgovina`.`slika` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `path` VARCHAR(500) NOT NULL,
  `izdelek_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_slika_izdelek1_idx` (`izdelek_id` ASC),
  CONSTRAINT `fk_slika_izdelek1`
    FOREIGN KEY (`izdelek_id`)
    REFERENCES `ep_trgovina`.`izdelek` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ep_trgovina`.`narocilo`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ep_trgovina`.`narocilo` ;

CREATE TABLE IF NOT EXISTS `ep_trgovina`.`narocilo` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `datum` DATETIME NOT NULL,
  `uporabnik_id` INT NOT NULL,
  `stanje` VARCHAR(45) NOT NULL DEFAULT 'oddano' COMMENT 'Stanje narocila:\n- oddano (neobdelano)\n- potrjeno\n- preklicano\n- stornirano\nponovno vpisano narocilo z negativnim zneskom (stornacija) pa ima lahko stanje ‘stornirano_narocilo’. V atribut ‘stornirano’ pa gre ID narocila, ki ga stornira.',
  `stornirano` INT NULL COMMENT 'kaze na ID narocila, ki ga stornira. Postavka je negativna postavka tistega narocila',
  `postavka` DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_narocilo_uporabnik1_idx` (`uporabnik_id` ASC),
  INDEX `fk_narocilo_narocilo1_idx` (`stornirano` ASC),
  CONSTRAINT `fk_narocilo_uporabnik1`
    FOREIGN KEY (`uporabnik_id`)
    REFERENCES `ep_trgovina`.`uporabnik` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_narocilo_narocilo1`
    FOREIGN KEY (`stornirano`)
    REFERENCES `ep_trgovina`.`narocilo` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ep_trgovina`.`narocilo_vsebuje`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ep_trgovina`.`narocilo_vsebuje` ;

CREATE TABLE IF NOT EXISTS `ep_trgovina`.`narocilo_vsebuje` (
  `kolicina` INT NOT NULL COMMENT 'Kolicina dolocenega izdelka\n',
  `izdelek_id` INT NOT NULL,
  `narocilo_id` INT NOT NULL,
  INDEX `fk_narocilo_vsebuje_izdelek1_idx` (`izdelek_id` ASC),
  PRIMARY KEY (`izdelek_id`, `narocilo_id`),
  INDEX `fk_narocilo_vsebuje_narocilo1_idx` (`narocilo_id` ASC),
  CONSTRAINT `fk_narocilo_vsebuje_izdelek1`
    FOREIGN KEY (`izdelek_id`)
    REFERENCES `ep_trgovina`.`izdelek` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_narocilo_vsebuje_narocilo1`
    FOREIGN KEY (`narocilo_id`)
    REFERENCES `ep_trgovina`.`narocilo` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

USE `ep_trgovina`;

DELIMITER $$

USE `ep_trgovina`$$
DROP TRIGGER IF EXISTS `ep_trgovina`.`uporabnik_BEFORE_INSERT` $$
USE `ep_trgovina`$$
CREATE DEFINER = CURRENT_USER TRIGGER `ep_trgovina`.`uporabnik_BEFORE_INSERT` BEFORE INSERT ON `uporabnik` FOR EACH ROW
BEGIN
	IF NEW.vloga = 'administrator' AND EXISTS (SELECT * FROM uporabnik WHERE vloga = 'administrator') THEN
		SIGNAL SQLSTATE '45000' SET message_text = 'Obstaja lahko le en administrator';
	END IF;
	IF NEW.vloga NOT IN ('stranka', 'administrator', 'prodajalec') THEN
        SIGNAL SQLSTATE '45000' SET message_text = 'Nedovoljena vloga';
    END IF;
	IF NEW.vloga IN ('stranka') AND (NEW.naslov IS NULL OR NEW.telefon IS NULL) THEN
		SIGNAL SQLSTATE '45000' SET message_text = 'Za uporabnika `naslov` ali `telefon` ni nastavljeno';
	END IF;
END;$$


USE `ep_trgovina`$$
DROP TRIGGER IF EXISTS `ep_trgovina`.`uporabnik_BEFORE_UPDATE` $$
USE `ep_trgovina`$$
CREATE DEFINER = CURRENT_USER TRIGGER `ep_trgovina`.`uporabnik_BEFORE_UPDATE` BEFORE UPDATE ON `uporabnik` FOR EACH ROW
BEGIN
	IF NEW.vloga = 'administrator' AND OLD.vloga != 'administrator' AND EXISTS (SELECT * FROM uporabnik WHERE vloga = 'administrator') THEN
		SIGNAL SQLSTATE '45000' SET message_text = 'Obstaja lahko le en administrator';
	END IF;
	IF NEW.vloga = 'stranka' AND (NEW.naslov IS NULL OR NEW.telefon IS NULL) THEN
		SIGNAL SQLSTATE '45000' SET message_text = 'Za uporabnika `naslov` ali `telefon` ni nastavljeno';
	END IF;
END;$$


DELIMITER ;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
