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
            throw new InvalidArgumentException("Admin z id-jem ".$params['id']." ne obstaja!");
        }
    }

    public static function getByEmail(array $params) {
        $admin = parent::query("SELECT id, ime, priimek, email, geslo "
            . "FROM Admin "
            . "WHERE email = :email",
            $params);

        if (count($admin) == 1) {
            return $admin[0];
        } else {
            #throw new InvalidArgumentException("Admin z id-jem ".$params['id']." ne obstaja!");
            return false;
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