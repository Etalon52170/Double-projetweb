<?php

include_once 'Base.php';

class Utilisateur {

    private $id_user;
    private $login;
    private $password;
    private $nb_victoire;
    private $nb_partie;
    private $mail;
    private $nbCards;
    private $indexx;
    private $game_id;

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
            $insert_query = "INSERT INTO utilisateur (login,password,nb_victoire,nb_partie,mail,nbCards,indexx,game_id) "
                    . "VALUES (:login, :password, :nb_victoire, :nb_partie, :mail, :nbCards, :indexx, :game_id)";
            $query = $db->prepare($insert_query);
            $query->bindParam(":login", $this->login, PDO::PARAM_STR);
            $query->bindParam(":password", $this->password, PDO::PARAM_STR);
            $query->bindParam(":nb_victoire", $this->nb_victoire, PDO::PARAM_INT);
            $query->bindParam(":nb_partie", $this->nb_partie, PDO::PARAM_INT);
            $query->bindParam(":mail", $this->mail, PDO::PARAM_STR);
            $query->bindParam(":nbCards", $this->nbCards, PDO::PARAM_INT);
            $query->bindParam(":indexx", $this->indexx, PDO::PARAM_INT);
            $query->bindParam(":game_id", $this->game_id, PDO::PARAM_INT);
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
            $s->nbCards = $d[6];
            $s->indexx = $d[7];
            $s->game_id = $d[8];
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
                $s->nbCards = $d[6];
                $s->indexx = $d[7];
                $s->game_id = $d[8];
                $res[] = $s;
            }
            return $res;
        } catch (PDOException $e) {
            echo 'fail connection';
            return null;
        }
    }

    public static function findByLogPwd($log, $pwd) {
        try {
            $c = Base::getConnection();
            $query = $c->prepare("SELECT * FROM utilisateur WHERE login = :login AND password = :password ");
            $query->bindParam(":login", $log, PDO::PARAM_STR);
            $query->bindParam(":password", $pwd, PDO::PARAM_STR);
            $dbres = $query->execute();
            $d = $query->fetch(PDO::FETCH_BOTH);
            $s = new Utilisateur();
            $s->id_user = $d[0];
            $s->login = $d[1];
            $s->password = $d[2];
            $s->nb_victoire = $d[3];
            $s->nb_partie = $d[4];
            $s->mail = $d[5];
            $s->nbCards = $d[6];
            $s->indexx = $d[7];
            $s->game_id = $d[8];
            return $s;
        } catch (PDOException $e) {
            echo("méthode insert() non implantée");
            return null;
        }
    }

    public static function findByLog($log) {
        try {
            $c = Base::getConnection();
            $query = $c->prepare("SELECT * FROM utilisateur WHERE login = :login");
            $query->bindParam(":login", $log, PDO::PARAM_STR);
            $dbres = $query->execute();
            $d = $query->fetch(PDO::FETCH_BOTH);
            $s = new Utilisateur();
            $s->id_user = $d[0];
            $s->login = $d[1];
            $s->password = $d[2];
            $s->nb_victoire = $d[3];
            $s->nb_partie = $d[4];
            $s->mail = $d[5];
            $s->nbCards = $d[6];
            $s->indexx = $d[7];
            $s->game_id = $d[8];
            return $s;
        } catch (PDOException $e) {
            echo("méthode insert() non implantée");
            return null;
        }
    }

    public static function updateIndexx($id_user, $indexx) {
        $c = Base::getConnection();
        $query = $c->prepare("update utilisateur set  indexx= ?
                                                 where id_user = ?");
        $query->bindParam(1, $indexx, PDO::PARAM_INT);
        $query->bindParam(2, $id_user, PDO::PARAM_INT);
        return $query->execute();
    }
    
    public static function updateNbVictoire($id_user,$nb_victoire){
        $c = Base::getConnection();
        $query = $c->prepare("update utilisateur set  nb_victoire= ?
                                                 where id_user = ?");
        $query->bindParam(1, $nb_victoire, PDO::PARAM_INT);
        $query->bindParam(2, $id_user, PDO::PARAM_INT);
        return $query->execute();
    }
    
    public static function updateNbPartie($id_user,$nb_partie){
        $c = Base::getConnection();
        $query = $c->prepare("update utilisateur set  nb_partie= ?
                                                 where id_user = ?");
        $query->bindParam(1, $nb_partie, PDO::PARAM_INT);
        $query->bindParam(2, $id_user, PDO::PARAM_INT);
        return $query->execute();
    }
    
    public static function updatePartie($id_user, $id_partie) {
        $c = Base::getConnection();
        $query = $c->prepare("update utilisateur set  game_id= ?
                                                 where id_user = ?");
        $query->bindParam(1, $id_partie, PDO::PARAM_INT);
        $query->bindParam(2, $id_user, PDO::PARAM_INT);
        return $query->execute();
    }
    
    public static function updatePoint($id_user, $nbPoint) {
        $c = Base::getConnection();
        $query = $c->prepare("update utilisateur set  nbCards= ?
                                                 where id_user = ?");
        $query->bindParam(1, $nbPoint, PDO::PARAM_INT);
        $query->bindParam(2, $id_user, PDO::PARAM_INT);
        return $query->execute();
    }
    
    public static function findByGameId($id_Game) {
        try {
            $c = Base::getConnection();
            $query = $c->prepare("SELECT * FROM utilisateur WHERE game_id = :idGame");
            $query->bindParam(":idGame", $id_Game, PDO::PARAM_INT);
            $dbres = $query->execute();
            $res = array();
            while ($d = $query->fetch(PDO::FETCH_BOTH)) {
                $s = array();
                $s[] = $d[0]; //id_user
                $s[] = $d[1]; //login
                $s[] = $d[6]; //nbCards
                $s[] = $d[7]; //Index de la carte du joueur
                $s[] = $d[8]; //game_id
                $res[] = $s;
            }
            return $res;
        } catch (PDOException $e) {
            echo("méthode insert() non implantée");
            return null;
        }
    }
    
    public static function findByGameIdOderBy($id_Game){
        try {
            $c = Base::getConnection();
            $query = $c->prepare("SELECT * FROM utilisateur WHERE game_id = :idGame ORDER BY nbCards DESC");
            $query->bindParam(":idGame", $id_Game, PDO::PARAM_INT);
            $dbres = $query->execute();
            $res = array();
            while ($d = $query->fetch(PDO::FETCH_BOTH)) {
                $s = array();
                $s[] = $d[0]; //id_user
                $s[] = $d[1]; //login
                $s[] = $d[3]; //nb_victoire
                $s[] = $d[4]; //nb_partie
                $s[] = $d[5]; //mail
                $s[] = $d[6]; //nbCards
                $s[] = $d[7]; //Index
                $s[] = $d[8]; //game_id
                $res[] = $s;
            }
            return $res;
        } catch (PDOException $e) {
            echo("méthode insert() non implantée");
            return null;
        }
    }

}

?>