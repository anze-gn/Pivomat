<?php

require_once 'model/AbstractDB.php';

class NarociloDB extends AbstractDB {

    public static function getAll(array $params = array()) {
        return parent::query("SELECT n.id, n.potrjeno, n.preklicano, n.stornirano, n.zakljuceno, n.datum, n.idStranka, s.ime, s.priimek, s.email "
            . "FROM Narocilo n, Stranka s "
            . parent::whereString($params, ["potrjeno" => "IS NOT", "preklicano" => "IS NOT", "stornirano" => "IS NOT", "zakljuceno" => "IS", "idStranka" => "="], "n.idStranka = s.id")
            . "ORDER BY id DESC", $params);
    }

    public static function get(array $params) {
        $narocilo = parent::query("SELECT id, potrjeno, preklicano, stornirano, zakljuceno, datum, idStranka "
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
                    . "zakljuceno = :zakljuceno "
                . " WHERE id = :id", $params);
    }

    public static function delete(array $params) {
        return parent::modify("DELETE FROM Narocilo WHERE id = :id", $params);
    }

}