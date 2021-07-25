<?php

namespace DAO;

use Connexion;
use PDOException;

class BaseDao
{
    public function findAll()
    {

        $listeModel = [];

        try {

            $connexion = new Connexion();

            $resultat = $connexion->query("SELECT * FROM " . $this->getNomTable());

            //pour chaque lignes (enregistrement) de la table
            foreach ($resultat->fetchAll() as $ligneResultat) {

                $model = $this->transformeTableauEnObjet($ligneResultat);

                $listeModel[] = $model;
            }
        } catch (PDOException $e) {
            echo "Le site rencontre un problème veuillez réessayer plus tard...<br>";
            //note : juste pour l'exemple, ce n'est pas forcement une bonne idée d'afficher
            //des informations sensibles comme le nom de la base de données...
            //Ca peut etre plus interressant de l'enregistrer dans un fichier de log
            echo "Info : " . $e->getMessage();
            die();
        }

        return $listeModel;
    }

    public function getNomTable()
    {
        //ex : si la classe s'appelle "DAO\UtilisateurDao"
        //retourne "utilisateur"
        return strtolower(substr(get_class($this), 4, -3));
    }

    public function getNomClasseModel()
    {
        //ex : si la classe s'appelle "DAO\UtilisateurDao"
        //retourne "Model\Utilisateur"
        return "Model\\" . substr(get_class($this), 4, -3);
    }

    public function transformeTableauEnObjet($tableau)
    {
        $nomClasseModel = $this->getNomClasseModel();

        //on créer une instance de la classe (ex : new Utilisateur())
        //rappel : instance = un objet créé grâce à une classe
        $model = new $nomClasseModel();

        //pour chaque index de $tableau 
        //(cad pour chaque colonne de la table)
        foreach ($tableau as $key => $valeur) {

            //on en déduit le setter (ex : setMotDePasse)

            //mot_de_passe -> setMotDePasse
            //etape 1 : "mot de passe"
            $nomSetter =  str_replace("_", " ", $key);

            //etape 2 : "Mot De Passe" 
            $nomSetter = ucwords($nomSetter);

            //etape 3 : "MotDePasse" 
            $nomSetter =  str_replace(" ", "", $nomSetter);

            //etape 4 : "setMotDePasse" 
            $nomSetter =  "set" . $nomSetter;

            //ou en une seule ligne :
            //$nomSetter =  "set" . str_replace(" ", "", ucwords(str_replace("_", " ", $key)));

            //si le setter existe bien (pour exclure les colonnes numerotées)
            if (method_exists($nomClasseModel, $nomSetter)) {
                //on appel le setter avec la valeur en paramètre
                //ex : setPrenom("franck")
                $model->$nomSetter($valeur);
            }
        }

        return $model;
    }
}
