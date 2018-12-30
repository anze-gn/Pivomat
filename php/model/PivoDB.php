<?php

require_once 'model/AbstractDB.php';

class PivoDB extends AbstractDB {

    public static function insert(array $params) {
        return parent::modify("INSERT INTO Artikel "
                . "(aktiviran, naziv, idZnamka, opis, kolicina, alkohol, cena, idStil) "
                . "VALUES "
                . "(:aktiviran, :naziv, :idZnamka, :opis, :kolicina, :alkohol, :cena, :idStil)",
            $params);
    }

    public static function update(array $params) {
        # TODO naredi tako, da se ob spremembi kreira nov artikel, staremu se pa spremeni atribut "posodobljen"
        return
            parent::modify("UPDATE Artikel SET "
                    . "aktiviran = :aktiviran, "
                    . "naziv = :naziv, "
                    . "idZnamka = :idZnamka, "
                    . "opis = :opis, "
                    . "kolicina = :kolicina, "
                    . "alkohol = :alkohol, "
                    . "cena = :cena, "
                    . "idStil = :idStil, "
                . " WHERE id = :id", $params);
    }

    public static function delete(array $params) {
        return parent::modify("DELETE FROM Artikel WHERE id = :id", $params);
    }

    public static function get(array $params) {
        # TODO vračaj znamko in stil (naredi JOIN query), dodaj WHERE posodobljen IS NULL
        $piva = parent::query("SELECT id, aktiviran, naziv, idZnamka, opis, kolicina, alkohol, cena, idStil "
                        . "FROM Artikel "
                        . "WHERE id = :id",
                $params);
        
        if (count($piva) == 1) {
            return $piva[0];
        } else {
            throw new InvalidArgumentException("Pivo z id-jem $params ne bostaja!");
        }
    }

    public static function getAll() {
        # TODO vračaj znamko in stil (naredi JOIN query), dodaj WHERE posodobljen IS NULL
        return parent::query("SELECT id, aktiviran, posodobljen, naziv, idZnamka, opis, kolicina, alkohol, cena, idStil "
            . "FROM Artikel "
            . "ORDER BY id ASC");
    }

}