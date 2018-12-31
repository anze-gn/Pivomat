<?php

require_once 'model/AbstractDB.php';
require_once 'model/ZnamkaDB.php';
require_once 'model/StilDB.php';

class PivoDB extends AbstractDB {

    public static function insert(array $params) {
        return parent::modify("INSERT INTO Artikel "
                . "(aktiviran, naziv, idZnamka, opis, kolicina, alkohol, cena, idStil) "
                . "VALUES "
                . "(:aktiviran, :naziv, :idZnamka, :opis, :kolicina, :alkohol, :cena, :idStil)",
            $params);
    }

    public static function update(array $params) {
        return
            parent::modify("UPDATE Artikel SET "
                    . "aktiviran = :aktiviran, "
                    . "naziv = :naziv, "
                    . "idZnamka = :idZnamka, "
                    . "opis = :opis, "
                    . "kolicina = :kolicina, "
                    . "alkohol = :alkohol, "
                    . "cena = :cena, "
                    . "idStil = :idStil "
                . " WHERE id = :id", $params);
    }

    public static function delete(array $params) {
        return parent::modify("DELETE FROM Artikel WHERE id = :id", $params);
    }

    public static function get(array $params) {
        $piva = parent::query("SELECT id, aktiviran, naziv, idZnamka, opis, kolicina, alkohol, cena, idStil "
                        . "FROM Artikel "
                        . "WHERE id = :id",
                $params);
        
        if (count($piva) == 1 && $piva[0]["idZnamka"] && $piva[0]["idStil"]) {
            $a["id"] = $piva[0]["idZnamka"];
            $b["id"] = $piva[0]["idStil"];
            $piva[0]["idZnamka"] = ZnamkaDB::get($a)["naziv"];
            $piva[0]["idStil"] = StilDB::get($b)["naziv"];
            return $piva[0];
        } else {
            throw new InvalidArgumentException("Pivo z id-jem $params ne obstaja!");
        }
    }

    #Ta get ne nadomesti znamke in stila z nazivom, ampak pusti id-je, pomemben pri vnosu edit-a
    public static function get2(array $params) {
        $piva = parent::query("SELECT id, aktiviran, naziv, idZnamka, opis, kolicina, alkohol, cena, idStil "
                        . "FROM Artikel "
                        . "WHERE id = :id",
                $params);
        
        if (count($piva) == 1) {
            return $piva[0];
        } else {
            throw new InvalidArgumentException("Pivo z id-jem $params ne obstaja!");
        }
    }
    
    public static function getAll() {
        $piva = parent::query("SELECT id, aktiviran, naziv, idZnamka, opis, kolicina, alkohol, cena, idStil "
            . "FROM Artikel "
            . "ORDER BY id ASC");
        foreach ($piva as $num => $pivo):
            if($pivo["idZnamka"] && $pivo["idStil"]){
                $a["id"] = $pivo["idZnamka"];
                $b["id"] = $pivo["idStil"];
                $piva[$num]["idZnamka"] = ZnamkaDB::get($a)["naziv"];
                $piva[$num]["idStil"] = StilDB::get($b)["naziv"];
            }
        endforeach;
        return $piva;
    }
    
    public static function getAllActivity(array $params) {
        $piva = parent::query("SELECT id, aktiviran, naziv, idZnamka, opis, kolicina, alkohol, cena, idStil "
            . "FROM Artikel "
            . "WHERE aktiviran = :aktiviran "
            . "ORDER BY id ASC", $params);
        foreach ($piva as $num => $pivo):
            if($pivo["idZnamka"] && $pivo["idStil"]){
                $a["id"] = $pivo["idZnamka"];
                $b["id"] = $pivo["idStil"];
                $piva[$num]["idZnamka"] = ZnamkaDB::get($a)["naziv"];
                $piva[$num]["idStil"] = StilDB::get($b)["naziv"];
            }
        endforeach;
        return $piva;
    }

}