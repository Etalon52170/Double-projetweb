<?php

include_once 'Base.php';

class Utilisateur {

    private $id_user;
    private $login;
    private $password;
    private $nb_victoire;
    private $nb_partie;
    private $mail;

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
            $insert_query = "INSERT INTO utilisateur (login,password,nb_victoire,nb_partie,mail) "
                    . "VALUES (:login, :password, :nb_victoire, :nb_partie, :mail)";
            $query = $db->prepare($insert_query);
            $query->bindParam(":login", $this->login, PDO::PARAM_STR);
            $query->bindParam(":password", $this->password, PDO::PARAM_STR);
            $query->bindParam(":nb_victoire", $this->nb_victoire, PDO::PARAM_INT);
            $query->bindParam(":nb_partie", $this->nb_partie, PDO::PARAM_INT);
            $query->bindParam(":mail", $this->mail, PDO::PARAM_STR);
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
            $s->login = $d[1];
            $s->password = $d[2];
            $s->nb_victoire = $d[3];
            $s->nb_partie = $d[4];
            $s->mail = $d[5];
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
                $s->login = $d[1];
                $s->password = $d[2];
                $s->nb_victoire = $d[3];
                $s->nb_partie = $d[4];
                $s->mail = $d[5];
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