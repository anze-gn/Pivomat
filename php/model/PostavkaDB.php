<?php

require_once 'model/AbstractDB.php';

class PostavkaDB extends AbstractDB {

    public static function getAll(array $params) {
        return parent::query("SELECT  p.idArtikel as id, p.kolicina as kol, a.naziv, a.kolicina, a.cena, z.naziv as imeZnamke, s.naziv as imeStila "
            . "FROM Postavka p, Artikel a, Znamka z, Stil s "
            . "WHERE idNarocilo = :idNarocilo AND p.idArtikel = a.id AND a.idZnamka = z.id AND a.idStil = s.id "
            . "ORDER BY id ASC", $params);
    }

    public static function get(array $params) {
        $postavka = parent::query("SELECT id, idArtikel, kolicina, idNarocilo "
                        . "FROM Postavka "
                        . "WHERE id = :id",
                $params);
        
        if (count($postavka) == 1) {
            return $postavka[0];
        } else {
            throw new InvalidArgumentException("Postavka z id-jem ".$params['id']." ne obstaja!");
        }
    }

    public static function insert(array $params) {
        return parent::modify("INSERT INTO Postavka "
                . "(idArtikel, kolicina, idNarocilo) "
                . "VALUES "
                . "(:idArtikel, :kolicina, :idNarocilo)",
            $params);
    }

    public static function update(array $params) {
        return
            parent::modify("UPDATE Postavka SET "
                    . "idArtikel = :idArtikel, "
                    . "kolicina = :kolicina, "
                    . "idNarocilo = :idNarocilo, "
                . " WHERE id = :id", $params);
    }

    public static function delete(array $params) {
        return parent::modify("DELETE FROM Postavka WHERE id = :id", $params);
    }

}