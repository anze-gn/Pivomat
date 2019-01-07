<?php

require_once 'model/AbstractDB.php';

class PivoDB extends AbstractDB {

    public static function insert(array $params) {
        return parent::modify("INSERT INTO Artikel "
                . "(aktiviran, naziv, idZnamka, opis, kolicina, alkohol, cena, idStil"
                . (isset($params['slika']) ? ", slika " : " ")
                . ") "
                . "VALUES "
                . "(:aktiviran, :naziv, :idZnamka, :opis, :kolicina, :alkohol, :cena, :idStil"
                . (isset($params['slika']) ? ", :slika " : " ")
                . ") ",
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
                    . "idStil = :idStil"
                    . (isset($params['slika']) ? ", slika = :slika" : " ")
                . " WHERE id = :id", $params);
    }

    public static function delete(array $params) {
        return parent::modify("DELETE FROM Artikel WHERE id = :id", $params);
    }

    public static function get(array $params) {
        $piva = parent::query("SELECT a.id, a.aktiviran, a.naziv, a.idZnamka, a.opis, a.kolicina, a.alkohol, a.cena, a.idStil, z.naziv as imeZnamke, s.naziv as imeStila "
            . "FROM Artikel a, Znamka z, Stil s "
            . "WHERE a.id = :id AND a.idZnamka = z.id AND a.idStil = s.id",  
              $params);
        
        if (count($piva) == 1) {
            return $piva[0];
        } else {
            throw new InvalidArgumentException("Pivo z id-jem ".$params['id']." ne obstaja!");
        }
    }

    public static function getSlika($id) {
        $slika = null;

        $sql = "SELECT slika FROM Artikel WHERE id = :id";
        $stmt = parent::getConnection()->prepare($sql);
        $stmt->execute(array(":id" => $id));
        $stmt->bindColumn(1, $slika, PDO::PARAM_LOB);
        $stmt->fetch(PDO::FETCH_BOUND);

        if ($slika == null) {
            $slika = file_get_contents('static/images/privzeto_pivo.jpg', 'rb');
        }

        return $slika;
    }
    
    public static function getAll(array $params = array()) {
        return parent::query("SELECT a.id, a.aktiviran, a.naziv, a.idZnamka, a.opis, a.kolicina, a.alkohol, a.cena, a.idStil, z.naziv as imeZnamke, s.naziv as imeStila "
            . "FROM Artikel a, Znamka z, Stil s "
            . parent::whereString($params, array("aktiviran" => "="), "AND a.idZnamka = z.id AND a.idStil = s.id ")
            . "ORDER BY a.id ASC", $params);
    }

    public static function getAllwithURI(array $params) {
        return parent::query("SELECT a.id, a.naziv, a.idZnamka, a.opis, a.kolicina, a.alkohol, a.cena, a.idStil, z.naziv as imeZnamke, s.naziv as imeStila, CONCAT(:prefix, a.id) as uri "
            . "FROM Artikel a, Znamka z, Stil s "
            . parent::whereString($params, array("aktiviran" => "="), " AND a.idZnamka = z.id AND a.idStil = s.id ")
            . "ORDER BY a.id ASC", $params);
    }
}