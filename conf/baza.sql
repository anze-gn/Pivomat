-- MySQL Script generated by MySQL Workbench
-- Fri Dec 28 22:54:28 2018
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema pivomat
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `pivomat` ;

-- -----------------------------------------------------
-- Schema pivomat
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `pivomat` DEFAULT CHARACTER SET utf8 ;
USE `pivomat` ;

-- -----------------------------------------------------
-- Table `pivomat`.`Admin`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pivomat`.`Admin` ;

CREATE TABLE IF NOT EXISTS `pivomat`.`Admin` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `ime` VARCHAR(45) NOT NULL,
  `priimek` VARCHAR(45) NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  `geslo` CHAR(255) NOT NULL,
  PRIMARY KEY (id))
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `pivomat`.`Artikel`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pivomat`.`Artikel` ;

CREATE TABLE IF NOT EXISTS `pivomat`.`Artikel` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `aktiviran` TINYINT(1) NOT NULL,
  `naziv` VARCHAR(45) NOT NULL,
  `idZnamka` INT NOT NULL,
  `opis` TEXT(100) NOT NULL,
  `kolicina` DOUBLE NOT NULL,
  `alkohol` DOUBLE NOT NULL,
  `cena` DOUBLE NOT NULL,
  `idStil` INT NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT `znamka`
    FOREIGN KEY (idZnamka)
    REFERENCES `pivomat`.`Znamka` (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `stil`
    FOREIGN KEY (idStil)
    REFERENCES `pivomat`.`Stil` (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `pivomat`.`Kraj`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pivomat`.`Kraj` ;

CREATE TABLE IF NOT EXISTS `pivomat`.`Kraj` (
  `postnaSt` INT NOT NULL,
  `ime` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`postnaSt`))
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `pivomat`.`Narocilo`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pivomat`.`Narocilo` ;

CREATE TABLE IF NOT EXISTS `pivomat`.`Narocilo` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `potrjeno` TIMESTAMP NULL DEFAULT NULL,
  `preklicano` TIMESTAMP NULL DEFAULT NULL,
  `stornirano` TIMESTAMP NULL DEFAULT NULL,
  `datum` TIMESTAMP NOT NULL DEFAULT NOW(),
  `idStranka` INT NOT NULL,
  PRIMARY KEY (id),
  INDEX `stranka_idx` (idStranka ASC),
  CONSTRAINT `stranka`
    FOREIGN KEY (idStranka)
    REFERENCES `pivomat`.`Stranka` (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `pivomat`.`Postavka`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pivomat`.`Postavka` ;

CREATE TABLE IF NOT EXISTS `pivomat`.`Postavka` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `idArtikel` INT NOT NULL,
  `kolicina` INT NOT NULL,
  `idNarocilo` INT NOT NULL,
  PRIMARY KEY (id),
  INDEX `artikel_idx` (`idArtikel` ASC),
  INDEX `narocilo_idx` (`idNarocilo` ASC),
  CONSTRAINT `artikel`
    FOREIGN KEY (`idArtikel`)
    REFERENCES `pivomat`.`Artikel` (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `narocilo`
    FOREIGN KEY (`idNarocilo`)
    REFERENCES `pivomat`.`Narocilo` (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `pivomat`.`Prodajalec`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pivomat`.`Prodajalec` ;

CREATE TABLE IF NOT EXISTS `pivomat`.`Prodajalec`(
  `id` INT NOT NULL AUTO_INCREMENT,
  `ime` VARCHAR(45) NOT NULL,
  `priimek` VARCHAR(45) NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  `geslo` CHAR(255) NOT NULL,
  `aktiviran` TINYINT(1) NULL,
  PRIMARY KEY (id))
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `pivomat`.`Stil`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pivomat`.`Stil` ;

CREATE TABLE IF NOT EXISTS `pivomat`.`Stil`(
  `id` INT NOT NULL AUTO_INCREMENT,
  `naziv` VARCHAR(45) NOT NULL,
  PRIMARY KEY (id))
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `pivomat`.`Stranka`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pivomat`.`Stranka` ;

CREATE TABLE IF NOT EXISTS `pivomat`.`Stranka` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `ime` VARCHAR(45) NOT NULL,
  `priimek` VARCHAR(45) NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  `ulica` VARCHAR(45) NOT NULL,
  `hisnaSt` INT NOT NULL,
  `postnaSt` INT NOT NULL,
  `telefon` VARCHAR(15) NOT NULL,
  `geslo` CHAR(255) NOT NULL,
  `aktiviran` TINYINT(1) NOT NULL,
  PRIMARY KEY (id),
  INDEX `posta_idx` (`postnaSt` ASC),
  CONSTRAINT `posta`
    FOREIGN KEY (`postnaSt`)
    REFERENCES `pivomat`.`Kraj` (`postnaSt`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `pivomat`.`Znamka`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pivomat`.`Znamka` ;

CREATE TABLE IF NOT EXISTS `pivomat`.`Znamka`(
  `id` INT NOT NULL AUTO_INCREMENT,
  `naziv` VARCHAR(45) NOT NULL,
  PRIMARY KEY (id))
ENGINE = InnoDB;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -------------------------------
-- INSERTI
-- -------------------------------
INSERT INTO `Admin` (`id`,`ime`,`priimek`,`email`,`geslo`) VALUES (1,'Admin','Admin','admin@admin.net', 'geslo');

INSERT INTO `Prodajalec` (`id`,`ime`,`priimek`,`email`,`geslo`,`aktiviran`) VALUES (1,'Janez','Novak','janez.novak@pivomat.si','geslo',1);
INSERT INTO `Prodajalec` (`id`,`ime`,`priimek`,`email`,`geslo`,`aktiviran`) VALUES (NULL,'Metka','Dolenc','metka.dolenc@pivomat.si','geslo',1);
INSERT INTO `Prodajalec` (`id`,`ime`,`priimek`,`email`,`geslo`,`aktiviran`) VALUES (NULL,'Marjetka','Kovač','marjetka.kovac@pivomat.si','geslo',0);

INSERT INTO `Kraj` (`postnaSt`,`ime`) VALUES (1000,'Ljubljana');

INSERT INTO `Stranka` (`id`,`ime`,`priimek`,`email`,`ulica`,`hisnaSt`,`postnaSt`,`telefon`,`geslo`,`aktiviran`) VALUES (1,'Urban','Urbanija','urban@gmail.com','Dunajska cesta',256,1000,'051000000','geslo',1);
INSERT INTO `Stranka` (`id`,`ime`,`priimek`,`email`,`ulica`,`hisnaSt`,`postnaSt`,`telefon`,`geslo`,`aktiviran`) VALUES (2,'Lara','Oblak','lara.oblak@hotmail.com','Gosposvetska ulica',39,1000,'031000000','geslo',1);
INSERT INTO `Stranka` (`id`,`ime`,`priimek`,`email`,`ulica`,`hisnaSt`,`postnaSt`,`telefon`,`geslo`,`aktiviran`) VALUES (3,'Rudi','Jerman','rudi.jerman@gmail.com','Šmartinska cesta',15,1000,'070000000','geslo',0);
INSERT INTO `Stranka` (`id`,`ime`,`priimek`,`email`,`ulica`,`hisnaSt`,`postnaSt`,`telefon`,`geslo`,`aktiviran`) VALUES (4,'Tončka','Stele','toncka@hotmail.com','Kolodvorska ulica',34,1000,'069000000','geslo',0);

INSERT INTO `Stil` (`id`,`naziv`) VALUES (1,'Abbey Trippel');
INSERT INTO `Stil` (`id`,`naziv`) VALUES (2,'Abt / Quadrupel');
INSERT INTO `Stil` (`id`,`naziv`) VALUES (3,'Amber / Red Ale');
INSERT INTO `Stil` (`id`,`naziv`) VALUES (4,'American IPA');
INSERT INTO `Stil` (`id`,`naziv`) VALUES (5,'American Pale Ale');
INSERT INTO `Stil` (`id`,`naziv`) VALUES (6,'American Wheat');
INSERT INTO `Stil` (`id`,`naziv`) VALUES (7,'Barley Wine');
INSERT INTO `Stil` (`id`,`naziv`) VALUES (8,'Belgian Dubbel');
INSERT INTO `Stil` (`id`,`naziv`) VALUES (9,'Belgian Strong Ale');
INSERT INTO `Stil` (`id`,`naziv`) VALUES (10,'Berliner Weisse');
INSERT INTO `Stil` (`id`,`naziv`) VALUES (11,'Bière de Garde');
INSERT INTO `Stil` (`id`,`naziv`) VALUES (12,'Black IPA');
INSERT INTO `Stil` (`id`,`naziv`) VALUES (13,'Brown Ale');
INSERT INTO `Stil` (`id`,`naziv`) VALUES (14,'California Common');
INSERT INTO `Stil` (`id`,`naziv`) VALUES (15,'Double IPA / Imperial IPA');
INSERT INTO `Stil` (`id`,`naziv`) VALUES (16,'Dunkelweizen');
INSERT INTO `Stil` (`id`,`naziv`) VALUES (17,'Fruit Beer');
INSERT INTO `Stil` (`id`,`naziv`) VALUES (18,'German Hefeweizen');
INSERT INTO `Stil` (`id`,`naziv`) VALUES (19,'Golden Ale / Blond Ale');
INSERT INTO `Stil` (`id`,`naziv`) VALUES (20,'Gose');
INSERT INTO `Stil` (`id`,`naziv`) VALUES (21,'Imperial Stout');
INSERT INTO `Stil` (`id`,`naziv`) VALUES (22,'India Pale Ale - IPA');
INSERT INTO `Stil` (`id`,`naziv`) VALUES (23,'Lager');
INSERT INTO `Stil` (`id`,`naziv`) VALUES (24,'Pale Ale');
INSERT INTO `Stil` (`id`,`naziv`) VALUES (25,'Pilsner');
INSERT INTO `Stil` (`id`,`naziv`) VALUES (26,'Porter');
INSERT INTO `Stil` (`id`,`naziv`) VALUES (27,'Premium Bitter / ESB');
INSERT INTO `Stil` (`id`,`naziv`) VALUES (28,'Saison');
INSERT INTO `Stil` (`id`,`naziv`) VALUES (29,'Schwarzbier');
INSERT INTO `Stil` (`id`,`naziv`) VALUES (30,'Session IPA');
INSERT INTO `Stil` (`id`,`naziv`) VALUES (31,'Sour / Wild Ale');
INSERT INTO `Stil` (`id`,`naziv`) VALUES (32,'Sour Red/Brown');
INSERT INTO `Stil` (`id`,`naziv`) VALUES (33,'Spice / Herb / Vegetable');
INSERT INTO `Stil` (`id`,`naziv`) VALUES (34,'Stout');
INSERT INTO `Stil` (`id`,`naziv`) VALUES (35,'Tripel');
INSERT INTO `Stil` (`id`,`naziv`) VALUES (36,'Weizenbock');
INSERT INTO `Stil` (`id`,`naziv`) VALUES (37,'Wheat Ale');
INSERT INTO `Stil` (`id`,`naziv`) VALUES (38,'White IPA');
INSERT INTO `Stil` (`id`,`naziv`) VALUES (39,'Witbier');

INSERT INTO `Znamka` (`id`,`naziv`) VALUES (1,'Mali grad');
INSERT INTO `Znamka` (`id`,`naziv`) VALUES (2,'Maister brewery');
INSERT INTO `Znamka` (`id`,`naziv`) VALUES (3,'Mister');
INSERT INTO `Znamka` (`id`,`naziv`) VALUES (4,'Hopsbrew');
INSERT INTO `Znamka` (`id`,`naziv`) VALUES (5,'Barut');
INSERT INTO `Znamka` (`id`,`naziv`) VALUES (6,'Tektonik');

INSERT INTO `Artikel` (`id`,`aktiviran`,`naziv`,`idZnamka`,`opis`,`kolicina`,`alkohol`,`cena`,`idStil`) VALUES (1,1,'Pale Ale',1,'Pivo v stilu ameriškega pale ale je paradni konj pivovarne Mali Grad. Jantarno-zlato barvo hmeljnega napitka pokriva velika in obstojna pena bele barve. Nežna sadna aroma s pridihom karamele in visoka stopnja mehurčkov daje zelo okusnemu in pitnemu pivu prijetno svežino in osvežilen občutek.',0.5,4.9,2.60,24);
INSERT INTO `Artikel` (`id`,`aktiviran`,`naziv`,`idZnamka`,`opis`,`kolicina`,`alkohol`,`cena`,`idStil`) VALUES (2,1,'Your Bitter Ex',1,'Za stilom ESB se skrivajo močnejša klasična angleška \"bitter\" piva in tudi kamniški Bitter Ex v tem kontekstu ni nobena izjema. Motna jantarna barva je pokrita z manjšo umazano belo peno, aroma pa je sladkasta z izrazitejšo zeliščo noto, kar na koncu lepo zaokroži v lahko in pitno pivo.',0.5,5.5,2.80,27);
INSERT INTO `Artikel` (`id`,`aktiviran`,`naziv`,`idZnamka`,`opis`,`kolicina`,`alkohol`,`cena`,`idStil`) VALUES (3,1,'India Pale Ale',1,'IPA pivo z oranžno-zlato barvo, katerega krasi solidna in obstojna pena bež barve ima prijetno sadno aromo agrumov in grenivke. Okus tega IPA piva zaznamuje zelo konkretna grenkoba, kar bo zadovoljilo tudi bolj zahtevne ljubitelje piv stila IPA.',0.5,6.0,2.90,22);
INSERT INTO `Artikel` (`id`,`aktiviran`,`naziv`,`idZnamka`,`opis`,`kolicina`,`alkohol`,`cena`,`idStil`) VALUES (4,0,'Your Obsession',1,'Pivo stila session IPA ima motno zlato barvo, pokriva pa ga obstojna, velika bela pena. Lahka sadna aroma z agrumi in tropskim sadjem ter nižjo stopnjo alkohola poskrbi za lahkotno in tako izjemno pitno pivo.',0.5,4.8,2.90,30);
INSERT INTO `Artikel` (`id`,`aktiviran`,`naziv`,`idZnamka`,`opis`,`kolicina`,`alkohol`,`cena`,`idStil`) VALUES (5,0,'Black Magic Woman',1,'Pivo popolnoma črne barve in veliko kremasto bež peno je mešanica klasične IPE in temnega piva. Zmerna grenkoba in srednje močna aroma sladkega sadja in praženega sladu z dodatkom kave.',0.5,6.0,2.90,12);
INSERT INTO `Artikel` (`id`,`aktiviran`,`naziv`,`idZnamka`,`opis`,`kolicina`,`alkohol`,`cena`,`idStil`) VALUES (6,1,'Gallus',2,'Osvežilni Pilsner z značilno mehurčkasto zlato barvo in belo peno, ki dokaj kmalu izzveni. Nežna travnata nota hmelja, prijetna grenkoba in komaj zaznavna sladkasta aroma vodijo v nadvse okusno in pitno pivo.',0.5,4.5,2.05,25);
INSERT INTO `Artikel` (`id`,`aktiviran`,`naziv`,`idZnamka`,`opis`,`kolicina`,`alkohol`,`cena`,`idStil`) VALUES (7,1,'Noordung',2,'V pivovarni Maister so dotično pivo poimenovali po Hermanu Potočniku Noordungu, svetovno priznanemu raketnemu inženirju in enemu izmed pionirjev vesoljskih poletov. Gosto in kremasto pivo abstraktne črne barve z veliko, obstojno peno odlikuje aroma temne čokolade, z dodanimi notami vanilje in pražene kave. Lahko pitno in zelo okusno. ',0.33,4.7,3,26);
INSERT INTO `Artikel` (`id`,`aktiviran`,`naziv`,`idZnamka`,`opis`,`kolicina`,`alkohol`,`cena`,`idStil`) VALUES (8,1,'General Maister',2,'V prijetni sadni aromi okusnega IPA piva se prepletajo note agrumov in tropskega sadja, okus pa zaznamuje rahla hmeljna grenčica.',0.33,6,2.55,22);
INSERT INTO `Artikel` (`id`,`aktiviran`,`naziv`,`idZnamka`,`opis`,`kolicina`,`alkohol`,`cena`,`idStil`) VALUES (9,0,'Vega',2,'Pivo Vega so v pivovarni Maister poimenovali po Juriju Vegi, našemu najbolj slavnemu matematiku. Nežna hmeljna grenkoba in prijetna aroma tropskih sadežev, agrumov in sladke breskve naredi Vego zelo okusno in lahko pitno pivo.  ',0.5,5.5,3,24);
INSERT INTO `Artikel` (`id`,`aktiviran`,`naziv`,`idZnamka`,`opis`,`kolicina`,`alkohol`,`cena`,`idStil`) VALUES (10,0,'Rusjan',2,'Kar štiri različne vrste aromatičnega hmelja zaznamuje okus svežega in pitnega IPA piva z aromo sladkega sadja, agrumov in vonja borovcev.',0.33,6,2.55,22);
INSERT INTO `Artikel` (`id`,`aktiviran`,`naziv`,`idZnamka`,`opis`,`kolicina`,`alkohol`,`cena`,`idStil`) VALUES (11,1,'Nolan',3,'MISTER Nolan je svetlo in lahko pivo citrusnih arom varjen s popolno kombinacijo najbolj aromatičnih ameriških hmeljev ter z dodatki pšeničnega sladu, ki prispeva k svilnatemu telesu.',0.5,4.5,3,24);
INSERT INTO `Artikel` (`id`,`aktiviran`,`naziv`,`idZnamka`,`opis`,`kolicina`,`alkohol`,`cena`,`idStil`) VALUES (12,0,'Russel',3,'MISTER Russell je pivo stila IPA s 6% alkohola. Varjen je s slovenskim hmeljem, ki daje tropsko aromo ter karamelnimi in čokoladnimi sladovi, ki delajo Russlla za pravo sladico med pivi.',0.33,6,3.2,22);
INSERT INTO `Artikel` (`id`,`aktiviran`,`naziv`,`idZnamka`,`opis`,`kolicina`,`alkohol`,`cena`,`idStil`) VALUES (13,1,'Pale Ale',4,'Pale Ale pivo izdelano na ameriški način. Motna oranžno-jantarna barva, na kateri kraljuje zelo obstojna bela kremasta pena, skriva v sebi aromo agrumov in sveže trave. Gre za izjemno pitno in osvežilno pivo.',0.5,4.8,2.85,24);
INSERT INTO `Artikel` (`id`,`aktiviran`,`naziv`,`idZnamka`,`opis`,`kolicina`,`alkohol`,`cena`,`idStil`) VALUES (14,1,'Oatmeal stout',4,'Izjemno polno pivo stila Stout je popolnoma črne barve, vrh pa krasi velika pena krem barve. Aroma temne čokolade z dodatkom vanilje vodi v izjemno prijetno in pitno pivo.',0.5,6.5,2.9,34);
INSERT INTO `Artikel` (`id`,`aktiviran`,`naziv`,`idZnamka`,`opis`,`kolicina`,`alkohol`,`cena`,`idStil`) VALUES (15,1,'Porter',4,'Okusen porter s popolnoma črno barvo in kremasto peno bež barve se lahko pohvali z nežno aromo kave, čokolade ter lahkimi travnatimi notami hmelja.',0.5,6,2.85,26);
INSERT INTO `Artikel` (`id`,`aktiviran`,`naziv`,`idZnamka`,`opis`,`kolicina`,`alkohol`,`cena`,`idStil`) VALUES (16,1,'Tropical wheat',4,'V aromatičnem belgijskem pšeničnem pivu z močno oranžno barvo in belo kremasto peno prevladuje konkretna hmeljna grenkoba v kombinaciji z izrazito sladko aromo tropskega sadja pasijonke in manga.',0.5,4.5,2.7,6);
INSERT INTO `Artikel` (`id`,`aktiviran`,`naziv`,`idZnamka`,`opis`,`kolicina`,`alkohol`,`cena`,`idStil`) VALUES (17,1,'Kri pomaranče',4,'Pivo stila IPA je izdelano s tremi vrstami hmelja in seveda, kot pove že samo ime, je dodana tudi lupina sladke pomaranče. V pivu je čutiti izredno prijetno sadno aromo in izrazito hmeljno grenkobo.',0.5,7,2.9,22);
INSERT INTO `Artikel` (`id`,`aktiviran`,`naziv`,`idZnamka`,`opis`,`kolicina`,`alkohol`,`cena`,`idStil`) VALUES (18,0,'Illusion',4,'Lahkotno in izjemno osvežilno IPA pivo se ponaša s prijetno sadno aromo, rahlim pikantnim priokusom in srednjo hmeljno grenkobo.',0.5,5.3,2.9,30);
INSERT INTO `Artikel` (`id`,`aktiviran`,`naziv`,`idZnamka`,`opis`,`kolicina`,`alkohol`,`cena`,`idStil`) VALUES (19,0,'IPA',4,'Pivo IPA s konkretno hmeljno grenkobo in sladko aromo tropskega sadja v kombinaciji s svežo travno noto in zelišči.',0.5,6,2.85,22);
INSERT INTO `Artikel` (`id`,`aktiviran`,`naziv`,`idZnamka`,`opis`,`kolicina`,`alkohol`,`cena`,`idStil`) VALUES (20,0,'Lucky Irish',4,'Staran v Jamesonovih sodih.',0.5,6.5,3.4,22);
INSERT INTO `Artikel` (`id`,`aktiviran`,`naziv`,`idZnamka`,`opis`,`kolicina`,`alkohol`,`cena`,`idStil`) VALUES (21,0,'Summer snow',5,'Barutov Summer snow se izraža z nežnimi kiselkastami notami vina, belega grozdja in rahlim pridihom začimb. Okusno, lahkotno in zelo osvežilno.',0.5,3.2,2.85,10);
INSERT INTO `Artikel` (`id`,`aktiviran`,`naziv`,`idZnamka`,`opis`,`kolicina`,`alkohol`,`cena`,`idStil`) VALUES (22,1,'Roadside picnic',5,'Divji Pale Ale s čudovitim hmeljem Aurora in ščepecem sečuanskega popra, ki poudari sadnost. Suho, visoko pitno in harmonično Session pivo.',0.5,4.5,2.95,28);
INSERT INTO `Artikel` (`id`,`aktiviran`,`naziv`,`idZnamka`,`opis`,`kolicina`,`alkohol`,`cena`,`idStil`) VALUES (23,0,'NorMal Ölstar Pale Ale',6,'NorMal je pravi poletnež, a paše tudi ob bolj turobnih dneh. Krasi ga zlato rumena barva, ki jo daje mešanica pretežno svetlih praženih sladov. Ti ustvarijo ravno dovolj sladkobe, da napolnijo telo, nekaj odstotkov svetlega karamelnega sladu in neslajene pšenice pa poskrbi za prijetno viskoznost. Temu lepo parira subtilna grenčica. Za ta pale ale, ki je zvarjen po vzoru ameriških kraft pivovarjev, so poleg slovenskega eksperimentalnega hmelja uporabili tudi nekaj zvenečih ameriških hmeljev, ki se izražajo v aromah citrusov in eksotičnega sadja, zaznati pa je tudi nekaj odtenkov cvetličnih vonjav.',0.5,5.1,2.30,24);
INSERT INTO `Artikel` (`id`,`aktiviran`,`naziv`,`idZnamka`,`opis`,`kolicina`,`alkohol`,`cena`,`idStil`) VALUES (24,1,'Hercule witbier',6,'V pivovarni Tektonik so zvarili sezonsko belgijsko pivo stila Wit, po tradicionalnem receptu. Posebnost tega piva je velika količina neslajene pšenice v skupni masi surovin, ki mu daje značilno motno, belkasto-zlato barvo ter mehak in osvežilen okus. Tega oblikuje še dodatek lupine grenke pomaranče in koriandrovih semen, ki so za ta stil skoraj obvezna. Sveže zdrobljena semena indijskega koriandra, lupine grenke pomaranče curacao poudarjajo svežino, ki jo dodatek črnega popra samo še izostri. Slovenski hmelj Styrian Golding ima tu le vlogo moderatorja, ki poveže arome in okuse v lahko, aromatično in sveže pivo, z relativno nizko alkoholno stopnjo, kar je kot nalašč za vroče poletne dni.',0.33,4.8,2.10,39);
INSERT INTO `Artikel` (`id`,`aktiviran`,`naziv`,`idZnamka`,`opis`,`kolicina`,`alkohol`,`cena`,`idStil`) VALUES (25,0,'Iggi India Pale Ale',6,'Danes je IPA najbolj priljubljen stil piva v segmentu craft pivovarn. Krasi ga konkretna grenčica in bogata aroma hmelja, telo pa je podobno šibkejšemu bratu stila Pale Ale, torej srednje močno, z rahlo noto sladke karamele ali praženega sladu, vendar se požirek konča s suhim zaključkom. V pivovarni Tektonik so zvarili pivo stila IPA, katerega telo se močno naslanja na britansko tradicijo, a je bogato odišavljeno z ameriškimi hmelji, katerih značilnost je prijetna cvetna aroma, aroma dišečih citrusov, pikantnega popra ter likuricije.',0.33,6.5,2.20,22);
INSERT INTO `Artikel` (`id`,`aktiviran`,`naziv`,`idZnamka`,`opis`,`kolicina`,`alkohol`,`cena`,`idStil`) VALUES (26,1,'Dizzy IPA Americana',6,'Dizzy je Tektonikov drugi zvarek stila IPA. Tokrat so se pri Tektoniku pri izdelavi recepta zgledovali po tradiciji kraft pivovarn z zahodne obale ameriškega kontinenta. V svetlem in lahkem telesu je zaznati subtilne okuse praženega sladu s kančkom karamele, a ta je predvsem nosilec bogatih arom eksotičnega sadja. Najbolj izstopata dišeča mango in pasijonka, nekoliko v ozadju se skrivajo še ananas, liči ter nekaj svetlega koščičastega sadja. Za ta stil je sicer značilna močna grenčica, a je v tem primeru bolj obrzdana za prijetnejše ravnovesje.',0.33,6.1,2.20,22);