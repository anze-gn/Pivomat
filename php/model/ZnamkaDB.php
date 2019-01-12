<?php

require_once 'model/AbstractDB.php';

class ZnamkaDB extends AbstractDB {

    public static function get(array $params) {
        $znamka = parent::query("SELECT id, naziv "
                . "FROM Znamka "
                . "WHERE id = :id",
                $params);
        
        if (count($znamka) == 1) {
            return $znamka[0];
        } else {
            throw new InvalidArgumentException("Znamka z id-jem ".$params['id']." ne obstaja!");
        }
    }

    public static function delete(array $id) {
        
    }

    public static function getAll(array $params = array()) {
        return parent::query("SELECT id, naziv FROM Znamka");
    }

    public static function insert(array $params) {
        
    }

    public static function update(array $params) {
        
    }

}