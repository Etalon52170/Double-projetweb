<?php

include_once 'Base.php';

class games {

    private $id;
    private $nbPlayers;
    private $indexx;

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
            $insert_query = "INSERT INTO games (id,nbPlayers,indexx) "
                    . "VALUES (:id, :nbPlayers, :indexx)";
            $query = $db->prepare($insert_query);
            $query->bindParam(":id", $this->id, PDO::PARAM_INT);
            $query->bindParam(":nbPlayers", $this->nbPlayers, PDO::PARAM_INT);
            $query->bindParam(":indexx", $this->indexx, PDO::PARAM_INT);
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
            $query = $c->prepare("SELECT * FROM games WHERE id = :id ");
            $query->bindParam(":id", $id, PDO::PARAM_INT);
            $dbres = $query->execute();
            $d = $query->fetch(PDO::FETCH_BOTH);
            $s = new games();
            $s->id = $d[0];
            $s->nbPlayers = $d[1];
            $s->indexx = $d[2];
            return $s;
        } catch (PDOException $e) {
            echo("méthode insert() non implantée");
            return null;
        }
    }

    public static function findAll() {

        try {
            $c = Base::getConnection();
            $query = $c->prepare("select * from games");
            $query->execute();
            $res = array();
            while ($d = $query->fetch(PDO::FETCH_BOTH)) {
                $s = new games();
                $s->id = $d[0];
                $s->nbPlayers = $d[1];
                $s->indexx = $d[2];
                $res[] = $s;
            }
            return $res;
        } catch (PDOException $e) {
            echo 'fail connection';
            return null;
        }
    }

    public static function incrementGame($id_partie, $nbPlayers) {
        $c = Base::getConnection();
        $query = $c->prepare("update games set nbPlayers= ?
                                               where id = ?");
        $query->bindParam(1, $nbPlayers, PDO::PARAM_INT);
        $query->bindParam(2, $id_partie, PDO::PARAM_INT);
        return $query->execute();
    }

    public function delete() {
        if (isset($this->id)) {
            $db = Base::getConnection();
            $delete_query = "delete from games where id = $this->id";
            try {
                $stmt = $db->prepare($delete_query);
                $this->id = null;
                $this->nbPlayers = null;
                $this->indexx = null;
                return $stmt->execute();
            } catch (PDOException $e) {
                return 0;
                echo "méthode delete() non implantée";
            }
        } else {
            echo "Primary Key undefined : cannot update";
        }
    }

}

?>