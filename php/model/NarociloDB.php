<?php

require_once 'model/AbstractDB.php';

class NarociloDB extends AbstractDB {

    public static function getAll(array $params = array()) {
        return parent::query("SELECT id, potrjeno, preklicano, stornirano, datum, idStranka "
            . "FROM Narocilo "
            . "ORDER BY id ASC");
    }

    public static function get(array $params) {
        $narocilo = parent::query("SELECT id, potrjeno, preklicano, stornirano, datum, idStranka "
                        . "FROM Narocilo "
                        . "WHERE id = :id",
                $params);
        
        if (count($narocilo) == 1) {
            return $narocilo[0];
        } else {
            throw new InvalidArgumentException("Naročilo z id-jem $params ne obstaja!");
        }
    }

    public static function insert(array $params) {
        return parent::modify("INSERT INTO Narocilo "
                . "(idStranka) "
                . "VALUES "
                . "(:idStranka)",
            $params);
    }

    public static function update(array $params) {
        return
            parent::modify("UPDATE Narocilo SET "
                    . "potrjeno = :potrjeno, "
                    . "preklicano = :preklicano, "
                    . "stornirano = :stornirano, "
                    . "idStranka = :idStranka "
                . " WHERE id = :id", $params);
    }

    public static function delete(array $params) {
        return parent::modify("DELETE FROM Narocilo WHERE id = :id", $params);
    }

}