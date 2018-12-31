<?php

require_once 'model/AbstractDB.php';

class ProdajalecDB extends AbstractDB {
    public static function getAll() {
        return parent::query("SELECT id, ime, priimek, email, geslo, aktiviran "
            . "FROM Prodajalec "
            . "ORDER BY id ASC");
    }
    
    public static function getAllActivity(array $params) {
        return parent::query("SELECT id, ime, priimek, email, geslo, aktiviran "
            . "FROM Prodajalec "
            . "WHERE aktiviran = :aktiviran "
            . "ORDER BY id ASC", $params);
    }

    public static function get(array $params) {
        $prodajalec = parent::query("SELECT id, ime, priimek, email, geslo, aktiviran "
                        . "FROM Prodajalec "
                        . "WHERE id = :id",
                $params);
        
        if (count($prodajalec) == 1) {
            return $prodajalec[0];
        } else {
            throw new InvalidArgumentException("Prodajalec z id-jem $params ne obstaja!");
        }
    }

    public static function insert(array $params) {
        return parent::modify("INSERT INTO Prodajalec "
                . "(aktiviran, ime, priimek, email, geslo) "
                . "VALUES "
                . "(:aktiviran, :ime, :priimek, :email, :geslo)",
            $params);
    }

    public static function update(array $params) {
        return
            parent::modify("UPDATE Prodajalec SET "
                    . "aktiviran = :aktiviran, "
                    . "ime = :ime, "
                    . "priimek = :priimek, "
                    . "email = :email, "
                    . "geslo = :geslo "
                . " WHERE id = :id", $params);
    }

    public static function delete(array $params) {
        return parent::modify("DELETE FROM Prodajalec WHERE id = :id", $params);
    }

}