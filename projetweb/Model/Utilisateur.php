<?php


class Utilisateur {

    private $id_user;
    private $pseudo;
    private $score;
    private $nb_game;


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
            $insert_query = "INSERT INTO user (id_user,pseudo,score,nb_game) "
                    . "VALUES (:pseudo, :score, :nb_game)";
            $query = $db->prepare($insert_query);
            $query->bindParam(":pseudo", $this->pseudo, PDO::PARAM_STR);
            $query->bindParam(":score", $this->score, PDO::PARAM_INT);
            $query->bindParam(":nb_game", $this->nb_game, PDO::PARAM_INT);
            $a = $query->execute();
            $this->id_user = $db->LastInsertId();
        } catch (PDOException $e) {
            echo "méthode insert() non implantée";
            return null;
        }
        return $a;
    }

    public static function findById($id) {
        try {
            $c = Base::getConnection();
            $query = $c->prepare("SELECT * FROM utilisateur WHERE id_user = :id_user ");
            $query->bindParam(":id_user", $id, PDO::PARAM_INT);
            $dbres = $query->execute();
            $d = $query->fetch(PDO::FETCH_BOTH);
            $s = new Utilisateur();
            $s->id_user = $d[0];
            $s->pseudo = $d[1];
            $s->score = $d[2];
            $s->nb_game = $d[3];
            return $s;
        } catch (PDOException $e) {
            echo("méthode insert() non implantée");
            return null;
        }
    }

    public static function findAll() {

        try {
            $c = Base::getConnection();
            $query = $c->prepare("select * from utilisateur");
            $query->execute();
            $res = array();
            while ($d = $query->fetch(PDO::FETCH_BOTH)) {
                $s = new Utilisateur();
                $s->id_user = $d[0];
                $s->pseudo = $d[1];
                $s->score = $d[2];
                $s->nb_game = $d[3];
                $res[] = $s;
            }
            return $res;
        } catch (PDOException $e) {
            echo 'fail connection';
            return null;
        }
    }

}

?>