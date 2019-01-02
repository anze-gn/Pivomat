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
            throw new InvalidArgumentException("Prodajalec z id-jem $params ne obstaja!");
        }
    }

    public static function getPasswordHash($email) {
        # za preverjanje gesla: password_verify($sent["geslo"], ProdajalecDB::getPasswordHash($sent["email"]))
        $prodajalec = parent::query("SELECT geslo "
            . "FROM Prodajalec "
            . "WHERE email = :email",
            array("email" => $email));

        if (count($prodajalec) == 1) {
            return $prodajalec[0]["geslo"];
        } else {
            #throw new InvalidArgumentException("Prodajalec z email-om $email ne obstaja!");
            return 1;
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