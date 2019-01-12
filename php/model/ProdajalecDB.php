<?php

require_once 'model/AbstractDB.php';

class ProdajalecDB extends AbstractDB {

    public static function getAll(array $params = array()) {
        return parent::query("SELECT id, ime, priimek, email, aktiviran "
            . "FROM Prodajalec "
            . parent::whereString($params, array("aktiviran" => "="))
            . "ORDER BY id ASC", $params);
    }

    public static function get(array $params) {
        $prodajalec = parent::query("SELECT id, ime, priimek, email, aktiviran "
                        . "FROM Prodajalec "
                        . "WHERE id = :id",
                $params);
        
        if (count($prodajalec) == 1) {
            return $prodajalec[0];
        } else {
            throw new InvalidArgumentException("Prodajalec z id-jem ".$params['id']." ne obstaja!");
        }
    }

    public static function getByEmail(array $params) {
        $prodajalec = parent::query("SELECT id, ime, priimek, email, aktiviran, geslo "
            . "FROM Prodajalec "
            . "WHERE email = :email",
            $params);

        if (count($prodajalec) == 1) {
            return $prodajalec[0];
        } else {
            #throw new InvalidArgumentException("Prodajalec z id-jem ".$params['id']." ne obstaja!");
            return false;
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
                    . "email = :email"
                    . ((strlen($params["geslo"]) > 0) ? ", geslo = :geslo ": " ")
                . " WHERE id = :id", $params);
    }

    public static function delete(array $params) {
        return parent::modify("DELETE FROM Prodajalec WHERE id = :id", $params);
    }

}