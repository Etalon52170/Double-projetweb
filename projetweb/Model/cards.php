<?php

include_once 'Base.php';

class cards {

    private $id;
    private $symbol0;
    private $symbol1;
    private $symbol2;
    private $symbol3;
    private $symbol4;
    private $symbol5;
    private $symbol6;
    private $symbol7;

    public function __construct() {
        
    }

    public function __get($attr_name) {
        if (property_exists(__CLASS__, $attr_name)) {
            return $this->$attr_name;
        }
        $emess = __CLASS__ . ": unknown member $attr_name (getAttr)";
        throw new Exception($emess, 45);
    }

    public function __set($attr_name, $attr_val) {

        if (property_exists(__CLASS__, $attr_name)) {
            $this->$attr_name = $attr_val;
        } else {
            $emess = __CLASS__ . ": unknown member $attr_name (setAttr)";
            echo ($emess);
        }
    }

    public function insert() {

        try {
            $db = Base::getConnection();
            $insert_query = "INSERT INTO cards (id,symbol0,symbol1,symbol2,symbol3,symbol4,symbol5,symbol6,symbol7) "
                    . "VALUES (:id, :symbol0, :symbol1, :symbol2, :symbol3, :symbol4, :symbol5, :symbol6, :symbol7)";
            $query = $db->prepare($insert_query);
            $query->bindParam(":id", $this->id, PDO::PARAM_INT);
            $query->bindParam(":symbol0", $this->symbol0, PDO::PARAM_INT);
            $query->bindParam(":symbol1", $this->symbol1, PDO::PARAM_INT);
            $query->bindParam(":symbol2", $this->symbol2, PDO::PARAM_INT);
            $query->bindParam(":symbol3", $this->symbol3, PDO::PARAM_INT);
            $query->bindParam(":symbol4", $this->symbol4, PDO::PARAM_INT);
            $query->bindParam(":symbol5", $this->symbol5, PDO::PARAM_INT);
            $query->bindParam(":symbol6", $this->symbol6, PDO::PARAM_INT);
            $query->bindParam(":symbol7", $this->symbol7, PDO::PARAM_INT);
            $a = $query->execute();
            $this->id = $db->LastInsertId();
        } catch (PDOException $e) {
            echo "méthode insert() non implantée";
            return null;
        }
        return $this->id;
    }

    public static function findById($id) {
        try {
            $c = Base::getConnection();
            $query = $c->prepare("SELECT * FROM cards WHERE id = :id ");
            $query->bindParam(":id", $id, PDO::PARAM_INT);
            $dbres = $query->execute();
            $d = $query->fetch(PDO::FETCH_BOTH);
            $s = new cards();
            $s->id = $d[0];
            $s->symbol1 = $d[1];
            $s->symbol2 = $d[2];
            $s->symbol3 = $d[3];
            $s->symbol4 = $d[4];
            $s->symbol5 = $d[5];
            $s->symbol6 = $d[6];
            $s->symbol7 = $d[7];
            return $s;
        } catch (PDOException $e) {
            echo("méthode insert() non implantée");
            return null;
        }
    }

    public static function findAll() {

        try {
            $c = Base::getConnection();
            $query = $c->prepare("select * from cards");
            $query->execute();
            $res = array();
            while ($d = $query->fetch(PDO::FETCH_BOTH)) {
                $s = new cards();
                $s->id = $d[0];
                $s->symbol1 = $d[1];
                $s->symbol2 = $d[2];
                $s->symbol3 = $d[3];
                $s->symbol4 = $d[4];
                $s->symbol5 = $d[5];
                $s->symbol6 = $d[6];
                $s->symbol7 = $d[7];
            }
            return $res;
        } catch (PDOException $e) {
            echo 'fail connection';
            return null;
        }
    }

}

?>