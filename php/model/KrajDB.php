<?php

require_once 'model/AbstractDB.php';

class KrajDB extends AbstractDB {

    public static function getAll(array $params = array()) {
        return parent::query("SELECT postnaSt, ime "
            . "FROM Kraj "
            . "ORDER BY ime ASC");
    }

    public static function get(array $params) {
        $posta = parent::query("SELECT postnaSt, ime "
                        . "FROM Kraj "
                        . "WHERE postnaSt = :postnaSt",
                $params);
        
        if (count($posta) == 1) {
            return $posta[0];
        } else {
            throw new InvalidArgumentException("Kraj s pošto ".$params['postnaSt']." ne obstaja!");
        }
    }

    public static function insert(array $params) {

    }

    public static function update(array $params) {

    }

    public static function delete(array $params) {
        
    }

}