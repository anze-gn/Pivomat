<?php

require_once 'model/AbstractDB.php';

class AdminDB extends AbstractDB {

    public static function getAll(array $params = array()) {
        return parent::query("SELECT id, ime, priimek, email "
            . "FROM Admin ");
    }

    public static function get(array $params) {
        $admin = parent::query("SELECT id, ime, priimek, email "
                        . "FROM Admin "
                        . "WHERE id = :id",
                $params);
        
        if (count($admin) == 1) {
            return $admin[0];
        } else {
            throw new InvalidArgumentException("Admin z id-jem $params ne obstaja!");
        }
    }

    public static function getPasswordHash($email) {
        # za preverjanje gesla: password_verify($sent["geslo"], AdminDB::getPasswordHash($sent["email"]))
        $admin = parent::query("SELECT geslo "
            . "FROM Admin "
            . "WHERE email = :email",
            array("email" => $email));

        if (count($admin) == 1) {
            return $admin[0]["geslo"];
        } else {
            throw new InvalidArgumentException("Admin z email-om $email ne obstaja!");
        }
    }

    public static function insert(array $params) {
        
    }

    public static function update(array $params) {
        return
            parent::modify("UPDATE Admin SET "
                    . "ime = :ime, "
                    . "priimek = :priimek, "
                    . "email = :email"
                    . ((strlen($params["geslo"]) > 0) ? ", geslo = :geslo ": " ")
                . " WHERE id = :id", $params);
    }

    public static function delete(array $params) {
    
    }

}