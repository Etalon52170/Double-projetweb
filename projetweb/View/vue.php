<?php

include_once './Modele/Famille.php';
include_once './Modele/Semaine.php';
include_once './Modele/Pour.php';

class Vue {

    protected $tab;
    
     protected $tabSelecteur = array(
        'dispo'=>'disponibilites',      
        'fam'=>'recherche',                
        'list' => 'liste',                    
        'newFam' => 'newFam',                        
        'enfant' => 'enf',                            
        'newEnf' => 'newEnf',                                
        'inscr'=> 'inscription',                                    
        'updateE' => 'newEnf',                                        
        'updateF' => 'newFam',                                            
        'enfant2'=>'enf2',                                                
        'listeFactu' => 'listeFactures',
        'listE'=>'listeEnfants',
        'factu'=>'facture',
         'acceuil'=>'acceuilpage'
        );

    public function __construct() {
        
    }
    
    

    public function __set($attr_name, $attr_val) {

        if (property_exists(__CLASS__, $attr_name)) {
            $this->$attr_name = $attr_val;
        } else {
            $emess = __CLASS__ . ": unknown member $attr_name (setAttr)";
            echo ($emess);
        }
    }

    public function __get($attr_name) {
        if (property_exists(__CLASS__, $attr_name)) {
            return $this->$attr_name;
        }
        $emess = __CLASS__ . ": unknown member $attr_name (getAttr)";
        throw new Exception($emess, 45);
    }
    private function acceuilpage(){
        $res ='
        <header>
            <div id=\'inscription\'>
                <a href = \'blog.php?a=inscription\'>Inscription</a>
            </div>
            <form id=\'login\' action=\'auth.php\' method=\'post\'>
                <div classboutton\'>
                    <button type=\'submit\'>Connexion</button>
                </div>
                <div id= identification >
                    <input type=\'text\' name=\'nom\' placeholder= \'Nom\' required/>
		<input type=\'password\' name=\'pwd\' placeholder= \'Mot de passe\' required/>
                ';
        return $res;
    }
    private function newEnf() {
        if (isset($_GET['b'])) {
            $res = '<form METHOD="POST" ACTION="index.php?a=saveE&b=' . $_GET['b'] . '">
				<p>Nom enfant: <input type="text" name="Nom"/></p>
 				<p>Prénom enfant: <input type="text" name="Prenom"/></p>
                                <p>Adresse enfant: <input type="text" name="Adresse"/></p>
                                <p>Sexe enfant (F/M): <input type="text" name="Sexe"/></p>
                                <p>Date de naissance(AAAA-MM-JJ): <input type="text" name="Date"/></p>
                                <p>Lieu de naissance: <input type="text" name="Lieu"/></p>
 				<p><input type="submit" name="confirme" value="OK"></p>
 				<form/>';
            if(isset($_GET['c'])){
                $res = '<form METHOD="POST" ACTION="index.php?a=saveE&b=' . $_GET['b'] . '&c='.$_GET['c'].'">
				<p>Nom enfant: <input type="text" name="Nom"/></p>
 				<p>Prénom enfant: <input type="text" name="Prenom"/></p>
                                <p>Adresse enfant: <input type="text" name="Adresse"/></p>
                                <p>Sexe enfant (F/M): <input type="text" name="Sexe"/></p>
                                <p>Date de naissance(AAAA-MM-JJ): <input type="text" name="Date"/></p>
                                <p>Lieu de naissance: <input type="text" name="Lieu"/></p>
 				<p><input type="submit" name="confirme" value="OK"></p>
 				<form/>';
            }
        }
        return $res;
    }

    private function enf2() {
        $res = 'Ajout enfant réussi<br/>'
                . '<a href=index.php?a=incr&b=' . $this->tab . '>Voulez vous inscrire cet enfant ?</a>';
        return $res;
    }

    private function enf() {
        $res = 'Ajout de la famille réussi<br/>'
                . '<a href=index.php?a=creerE&b=' . $this->tab . '>Créer un nouvel enfant ?</a>';
        return $res;
    }

    private function newFam() {
        if (isset($_GET['b'])) {
            $res = '<form METHOD="POST" ACTION="index.php?a=saveF&b=' . $_GET['b'] . '">
				<p>Nom de la famille: <input type="text" name="Nom"/></p>
 				<p>Prénom du responsable: <input type="text" name="Prenom"/></p>
                                <p>Type du responsable (mère/père/..): <input type="text" name="Type"/></p>
                                <p>Adresse du responsable: <input type="text" name="Adresse"/></p>
                                <p>Numéro de téléphone du responsable: <input type="text" name="Numtel"/></p>
                                <p>Numéro allocation du responsable: <input type="text" name="Numalo"/></p>
                                <p>Coef du responsable: <input type="text" name="Coef"/></p>
                                <p>En ville  (oui/non): <input type="text" name="Ville"/></p>
                                <p>Bons vacance (oui/non): <input type="text" name="Vacance"/></p>
 				<p><input type="submit" name="confirme" value="OK"></p>
        <form/>';
        } else {
            $res = '<form METHOD="POST" ACTION="index.php?a=saveF">
				<p>Nom de la famille: <input type="text" name="Nom"/></p>
 				<p>Prénom du responsable: <input type="text" name="Prenom"/></p>
                                <p>Type du responsable (mère/père/..): <input type="text" name="Type"/></p>
                                <p>Adresse du responsable: <input type="text" name="Adresse"/></p>
                                <p>Numéro de téléphone du responsable: <input type="text" name="Numtel"/></p>
                                <p>Numéro allocation du responsable: <input type="text" name="Numalo"/></p>
                                <p>Coef du responsable: <input type="text" name="Coef"/></p>
                                <p>En ville  (oui/non): <input type="text" name="Ville"/></p>
                                <p>Bons vacance (oui/non): <input type="text" name="Vacance"/></p>
 				<p><input type="submit" name="confirme" value="OK"></p>
        <form/>';
        }
        return $res;
    }

    private function defaut() {
        $res = '<section><a href=index.php?a=dispo>Afficher toutes les disponibilités</a></section> <br/>
<section><a href=index.php?a=listeFactu>Afficher toutes les factures</a></section> <br/>
<section><a href=index.php?a=search>Recherche/Ajout de familles/enfants</a></section> <br/>';
        return $res;
    }

    private function recherche() {
        $res = '<div id="Global"><div id="gauche"><h2>Familles</h2><form METHOD="POST" ACTION="index.php?a=saveR">
				<p>Nom de la famille recherché: <input type="text" name="Nom"/></p>
 				<p>Prénom du responsable: <input type="text" name="Prenom"/></p>
 				<p><input type="submit" name="confirme" value="OK"></p>
 				</form>'
                . '<a href=index.php?a=creerF>Créer une nouvelle famille</a></div><br/>'
                .'<div id="droite"><h2>Enfants</h2>'
                . '<form METHOD="POST" ACTION="index.php?a=searchE">
				<p>Nom enfant recherché: <input type="text" name="Nom"/></p>
 				<p><input type="submit" name="confirme2" value="OK"></p>
 				</form></div></div>';
        return $res;
    }


    public function listeEnfants() {
        $res = '';
        if (!empty($this->tab)) {
            $res.="<h2>Enfants</h2><table>";
            foreach ($this->tab as $e) {
                $res = $res . "<table>";
                        $res = $res . "<tr><th>Nom de Famille</th><td>" . $e->nom_enf . "</td> </tr>";
                        $res = $res . "<tr><th>Prenom</th><td>" . $e->prenom_enf . "</td> </tr>";
                        $res = $res . "<tr><th>Adresse</th><td>" . $e->adr_enf . "</td> </tr>";
                        $res = $res . "<tr><th>Sexe</th><td>" . $e->sexe_enf . "</td> </tr>";
                        $res = $res . "<tr><th>Date de naissance</th><td>" . $e->datn_enf . "</td> </tr>";
                        $res = $res . "<tr><th>Lieu de naissance</th><td>" . $e->lieu_naiss_enf . "</td> </tr>";
                        $res = $res . "<tr><th><a href='index.php?a=updateE&b=".$e->no_fam."&c=" . $e->no_enf . "'>Update</a></th><td><a href='index.php?a=inscr&id=" . $e->no_enf . "'>Inscription</a></td> </tr>";
                        $res = $res . "</table><br/>";
            }
        } else {
            $res .= 'Aucun enfant avec ce nom et ce responsable est inscrite'
                    . '<br/><br/><section><a href=index.php?a=search>Faire une nouvelle recherche</a></section> <br/>';
        }
        return $res;
    }
    
    public function liste() {
        $res = '';
        if (!empty($this->tab)) {
            $res.="<h2>Famille</h2>";
            foreach ($this->tab as $value) {
                $res = $res . "<table>";
                $res = $res . "<tr><th>Nom de Famille</th><td>" . $value->nom_resp . "</td> </tr>";
                $res = $res . "<tr><th>Prenom du responsable</th><td>" . $value->pre_resp . "</td> </tr>";
                $res = $res . "<tr><th>Type du responsable</th><td>" . $value->type_resp . "</td> </tr>";
                $res = $res . "<tr><th>Adresse du Responsable</th><td>" . $value->adr_resp . "</td> </tr>";
                $res = $res . "<tr><th>Numéro de téléphone Responsable</th><td>" . $value->tel_resp . "</td> </tr>";
                $res = $res . "<tr><th>Numéro d'allocation Responsable</th><td>" . $value->noalloc_caf_resp . "</td> </tr>";
                $res = $res . "<tr><th>Coefficient du Responsable</th><td>" . $value->qf_resp . "</td> </tr>";
                $res = $res . "<tr><th>En ville</th><td>" . $value->en_ville . "</td> </tr> ";
                $res = $res . "<tr><th>Bons vacances</th><td>" . $value->bons_vac . "</td> </tr>";
                $res = $res . "<tr><th><a href='index.php?a=updateF&b=" . $value->no_fam . "'>Update</a></th><td><a href='index.php?a=creerE&b=" . $value->no_fam . "'>Creer un Enfant</a></td> </tr>";
                $res = $res . "</table><br />";


                $res.="<h2>Enfants</h2><table>";
                $tab = Enfant::findByFamille($value->no_fam);
                if (!empty($tab)) {
                    foreach ($tab as $e) {
                        $res = $res . "<table>";
                        $res = $res . "<tr><th>Nom de Famille</th><td>" . $e->nom_enf . "</td> </tr>";
                        $res = $res . "<tr><th>Prenom</th><td>" . $e->prenom_enf . "</td> </tr>";
                        $res = $res . "<tr><th>Adresse</th><td>" . $e->adr_enf . "</td> </tr>";
                        $res = $res . "<tr><th>Sexe</th><td>" . $e->sexe_enf . "</td> </tr>";
                        $res = $res . "<tr><th>Date de naissance</th><td>" . $e->datn_enf . "</td> </tr>";
                        $res = $res . "<tr><th>Lieu de naissance</th><td>" . $e->lieu_naiss_enf . "</td> </tr>";
                        $res = $res . "<tr><th><a href='index.php?a=updateE&b=".$e->no_fam."&c=" . $e->no_enf . "'>Update</a></th><td><a href='index.php?a=inscr&id=" . $e->no_enf . "'>Inscription</a></td> </tr>";
                        $res = $res . "</table><br/>";
                    }
                } else {
                    $res .= 'Cette famille ne possède aucun enfant';
                }
            }
        } else {
            $res .= 'Aucune famille avec ce nom et ce responsable est inscrite'
                    . '<br/><br/><section><a href=index.php?a=search>Faire une nouvelle recherche</a></section> <br/>';
        }
        return $res;
    }

    public function inscription() {
        $e = Enfant::findById($_GET['id']);

        include_once ('Modele/Site.php');
        include_once ('Modele/Unite.php');
        include_once ('Modele/Semaine.php');

        $res = "<p><h2>Inscription de l'enfant " . $e->nom_enf . " " . $e->prenom_enf . "</h2>
        <form method='post' action='index.php?a=saveInscr&id=".$_GET['id']."'>
            <label for='deduc_jour'>Déduction par jour</label> : 
            <input type='text' id='deduc_jour' name='deduc_jour' /><br />


            <label for='nom_accomp'>Nom de l'accompagnateur</label> : 
            <input type='text' id='nom_accomp' name='nom_accomp' /><br />

             <label for='prenom_accomp'>Prénom de l'accompagnateur</label> : 
            <input type='text' id='prenom_accomp' name='prenom_accomp' /><br />

            <label for='montant_inscr'>Montant de l'inscription</label> : 
            <input type='text' id='montant_inscr' name='montant_inscr' /><br />

            <label for='lieu_inscr'>Lieu de l'inscription</label> : 
            <input type='text' id='lieu_inscr' name='lieu_inscr' /><br />

            <label for='unite'>Centre - unité</label> : 
            <select id='unite' name='unite' >";

        foreach (Site::findAll() as $s) {
            foreach (Unite::findBySite($s->no_site) as $u) {
                $res.="<option value='" . $u->no_unite . "'>" . $s->nom_site . " - " . $u->nom_unite . "</option>";
            }
        }





        $res.="</select><br />
            <label for='semaine'>Semaine</label> : 
            <select id='semaine' name='semaine'>";

        foreach (Semaine::findAll() as $sem) {
            $res.="<option value='" . $sem->sem_sej . "'>" . $sem . "</option>";
        }




        $res.="</select><br /></p>
            <p>
                <h2>Facture</h2>

                <label for='dateFact'>Date de la facture</label> : 
                <input type='date' id='dateFact' name='dateFact' /><br />

                <label for='montFact'>Montant de la facture</label> : 
                <input type='text' id='montFact' name='montFact'/><br />

                <label for='modePaiement'>Mode de paiement</label> : 
                <input type='text' id='modePaiement' name='modePaiement' /><br />
            </p>
            <input type='reset' value='Réinitialiser' />
            <input type='submit' value='Inscrire' />

        </form>";

        return $res;
    }



    public function disponibilites() {
        include_once 'Modele/Site.php';
        include_once 'Modele/Unite.php';

        if (isset($_POST['nomSite'])) {
            $res = "<p><h3>Disponibilités du site " . Site::findById($_POST['nomSite'])->nom_site . "</h3>";

            $res.= "<table>
            <tr><th>Unité</th><th></tr>";

            foreach (Unite::findBySite($_POST['nomSite']) as $u) {
                $res.="<tr><td>" . $u->nom_unite . "<td></tr>";
            }



            $res .= "</table></p>";
        } else {

            $res = "
            <form method='post' action='index.php?a=dispo'>
            <label for='nomSite'>Choisir le site</label> :
            <select id='nomSite' name='nomSite'>";

            foreach (Site::findAll() as $s) {
                $res.="<option value='" . $s->no_site . "'>" . $s->nom_site . "</option>";
            }


            $res.="</select><br />
            <input type='submit' value='Vérifier disponibilités' />
            </form>";
        }

        return $res;
    }
    
    public function facture()
    {

        $f = Facture::findById($_GET['id']);
        $res = "<p><h2>Facture n° " . $f->no_fact . "</h2></p>";



            $i = Inscription::findByFacture($f->no_fact);
            $e = Enfant::findById($i->no_enf);
            $fam = Famille::findById($e->no_fam);            

            $res.= "<table>";
            
            $res.= "<tr><th>Facture au nom de </th><td>".$fam->nom_resp." ".$fam->pre_resp."</td></tr>";
            $res.= "<tr><th>Pour l'enfant </th><td>".$e->nom_enf." ".$e->prenom_enf."</td></tr>";
            $res.= "<tr><th>Période d'inscription</th><td>" . Semaine::findById(Pour::findByInscription($i->no_inscript)->sem_sej) . "</td><tr>";
            $res.= "<tr><th>Date de la facture</th><td>" . $f->date_fact . "</td></tr>";
            $res.= "<tr><th>Montant de la facture</th><td>" . $f->montant_fact . "</td></tr>";
            $res.= "<tr><th>Mode de paiement</th><td>" . $f->mode_paiement . "</td></tr>";

            $res.="</table></p><h2>Détails de l'inscription</h2>";

            $res .= "<p><table>";

            $res.= "<tr><th>Déduction par jour</th><td>".$i->deduc_jour."</td></tr>";
            $res.= "<tr><th>Accompagnateur</th><td>".$i->nom_accompagnateur_enf." ".$i->pre_accompagnateur_enf."</td></tr>";
            $res.= "<tr><th>Montant de l'inscription</th><td>".$i->montant_inscr."</td></tr>";
            $res.= "<tr><th>Lieu de l'inscription</th><td>".$i->lieu_inscr."</td></tr>";
            $res.="</table></p>";

        return $res;

    }
    
    public function listeFactures()
    {
        $res = '<p><h2>Liste des factures</h2><br />
        <table>';

        

        foreach(Facture::findAll() as $f)
        {
            $i = Inscription::findByFacture($f->no_fact);
            $e = Enfant::findById($i->no_enf);
            $fam = Famille::findById($e->no_fam);            

            
            $res.= "<tr><th>Facture au nom de </th><td>".$fam->nom_resp." ".$fam->pre_resp."</td></tr>";
            $res.= "<tr><th>Pour l'enfant </th><td>".$e->nom_enf." ".$e->prenom_enf."</td></tr>";
            $res.= "<tr><th>Période d'inscription</th><td>" . Semaine::findById(Pour::findByInscription($i->no_inscript)->sem_sej) . "</td><tr>";
            $res.= "<tr><th>Date de la facture</th><td>" . $f->date_fact . "</td></tr>";
            $res.= "<tr><th></th><td><a href='index.php?a=factu&id=".$f->no_fact."'>Plus de détails</a></td></tr>";

        }

        $res.='</table>';

        return $res;
    }

    public function affichageGeneral($select) {
        $res = '<!DOCTYPE html>
<html lang="fr">

<head>
	<title>Affichage général</title>
	<meta charset="utf-8" />
	<link rel="stylesheet" href="style.css" type="text/css" /> 
</head>  
  
<body>';

        if(array_key_exists($select, $this->tabSelecteur))
        {
            
            $m = $this->tabSelecteur[$select];
            
            $res .= $this->$m();
        }
        else
        {
            $res .= $this->defaut();
        }

        $res = $res . '<br/><br/><br/><div id="separe2"><br/><br/><br/></div><br/><br/></body></html>';
        return $res;
    }

}

?>