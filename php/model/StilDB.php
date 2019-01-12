<?php

require_once 'model/AbstractDB.php';

class StilDB extends AbstractDB {

    public static function get(array $params) {
        $stil = parent::query("SELECT id, naziv "
                . "FROM Stil "
                . "WHERE id = :id",
                $params);
        
        if (count($stil) == 1) {
            return $stil[0];
        } else {
            throw new InvalidArgumentException("Stil z id-jem ".$params['id']." ne obstaja!");
        }
    }

    public static function delete(array $id) {
        
    }

    public static function getAll(array $params = array()) {
        return parent::query("SELECT id, naziv FROM Stil ");
    }

    public static function insert(array $params) {
        
    }

    public static function update(array $params) {
        
    }

}

