<?php

require_once 'model/AbstractDB.php';

class PostavkaDB extends AbstractDB {
    public static function getAll() {
        return parent::query("SELECT id, idArtikel, kolicina, idNarocilo "
            . "FROM Postavka "
            . "ORDER BY id ASC");
    }

    public static function get(array $params) {
        $postavka = parent::query("SELECT id, idArtikel, kolicina, idNarocilo "
                        . "FROM Postavka "
                        . "WHERE id = :id",
                $params);
        
        if (count($postavka) == 1) {
            return $postavka[0];
        } else {
            throw new InvalidArgumentException("Postavka z id-jem $params ne obstaja!");
        }
    }

    public static function insert(array $params) {
        return parent::modify("INSERT INTO Postavka "
                . "(idArtikel, kolicina, idNarocilo) "
                . "VALUES "
                . "(:idArtikel, :kolicina, :idNarocilo)",
            $params);
    }

    public static function update(array $params) {
        return
            parent::modify("UPDATE Postavka SET "
                    . "idArtikel = :idArtikel, "
                    . "kolicina = :kolicina, "
                    . "idNarocilo = :idNarocilo "
                . " WHERE id = :id", $params);
    }

    public static function delete(array $params) {
        return parent::modify("DELETE FROM Postavka WHERE id = :id", $params);
    }

}