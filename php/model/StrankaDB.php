<?php

require_once 'model/AbstractDB.php';

class StrankaDB extends AbstractDB {
    public static function getAll() {
        return parent::query("SELECT id, ime, priimek, email, ulica, hisnaSt, postnaSt, telefon, geslo, aktiviran "
            . "FROM Stranka "
            . "ORDER BY id ASC");
    }
    
    public static function getAllActivity(array $params) {
        return parent::query("SELECT id, ime, priimek, email, ulica, hisnaSt, postnaSt, telefon, geslo, aktiviran "
            . "FROM Stranka "
            . "WHERE aktiviran = :aktiviran "
            . "ORDER BY id ASC", $params);
    }

    public static function get(array $params) {
        $stranka = parent::query("SELECT id, ime, priimek, email, ulica, hisnaSt, postnaSt, telefon, geslo, aktiviran "
                        . "FROM Stranka "
                        . "WHERE id = :id",
                $params);
        
        if (count($stranka) == 1) {
            return $stranka[0];
        } else {
            throw new InvalidArgumentException("Stranka z id-jem $params ne obstaja!");
        }
    }
    
    public static function get2(array $params) {
        $stranka = parent::query("SELECT s.id, s.ime, s.priimek, s.email, s.ulica, s.hisnaSt, s.postnaSt, s.telefon, s.geslo, s.aktiviran, k.ime as imeKraja "
                        . "FROM Stranka s, Kraj k "
                        . "WHERE s.id = :id AND s.postnaSt = k.postnaSt",
                $params);
        
        if (count($stranka) == 1) {
            return $stranka[0];
        } else {
            throw new InvalidArgumentException("Stranka z id-jem $params ne obstaja!");
        }
    }

    public static function insert(array $params) {
        return parent::modify("INSERT INTO Stranka "
                . "(aktiviran, ime, priimek, email, ulica, hisnaSt, postnaSt, telefon, geslo) "
                . "VALUES "
                . "(:aktiviran, :ime, :priimek, :email, :ulica, :hisnaSt, :postnaSt, :telefon, :geslo)",
            $params);
    }

    public static function update(array $params) {
        return
            parent::modify("UPDATE Stranka SET "
                    . "aktiviran = :aktiviran, "
                    . "ime = :ime, "
                    . "priimek = :priimek, "
                    . "email = :email, "
                    . "ulica = :ulica, "
                    . "hisnaSt = :hisnaSt, "
                    . "postnaSt = :postnaSt, "
                    . "telefon = :telefon, "
                    . "geslo = :geslo "
                . " WHERE id = :id", $params);
    }

    public static function delete(array $params) {
        return parent::modify("DELETE FROM Stranka WHERE id = :id", $params);
    }

}