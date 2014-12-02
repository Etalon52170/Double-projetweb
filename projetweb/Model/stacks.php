<?php

include_once 'Base.php';
/**
 * Description of stack
 *
 * @author yannick
 */
class stacks {
    
    private $id;
    private $game_id;
    private $carte_id;
    private $numOrder;
    
    public function __construct(){}
    
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
            $insert_query = "INSERT INTO stacks (id,game_id,card_id,numOrder) "
                    . "VALUES (:id, :game_id, :card_id, :numOrder)";
            $query = $db->prepare($insert_query);
            $query->bindParam(":id", $this->id, PDO::PARAM_INT);
            $query->bindParam(":game_id", $this->game_id, PDO::PARAM_INT);
            $query->bindParam(":card_id", $this->card_id, PDO::PARAM_INT);
            $query->bindParam(":numOrder", $this->numOrder, PDO::PARAM_INT);
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
            $query = $c->prepare("SELECT * FROM stacks WHERE id = :id ");
            $query->bindParam(":id", $id, PDO::PARAM_INT);
            $dbres = $query->execute();
            $d = $query->fetch(PDO::FETCH_BOTH);
            $s = new stacks();
            $s->id = $d[0];
            $s->game_id = $d[1];
            $s->card_id = $d[2];
            $s->numOrder = $d[3];
            return $s;
        } catch (PDOException $e) {
            echo("méthode insert() non implantée");
            return null;
        }
    }

    public static function findAll() {

        try {
            $c = Base::getConnection();
            $query = $c->prepare("select * from stacks");
            $query->execute();
            $res = array();
            while ($d = $query->fetch(PDO::FETCH_BOTH)) {
                $s = new stacks();
                $s->id = $d[0];
                $s->game_id = $d[1];
                $s->card_id = $d[2];
                $s->numOrder = $d[3];
                $res[] = $s;
            }
            return $res;
        } catch (PDOException $e) {
            echo 'fail connection';
            return null;
        }
    }

    public function delete() {
        if (isset($this->id)) {
            $db = Base::getConnection();
            $delete_query = "delete from stacks where id = $this->id";
            try {
                $stmt = $db->prepare($delete_query);
                $this->id = null;
                $this->game_id = null;
                $this->carte_id = null;
                $this->numOrder = null;
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
