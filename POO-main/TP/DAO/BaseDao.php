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

            //pour chaque ligne de la table
            foreach ($resultat->fetchAll() as $ligneResultat) {

                $model = $this->transformeTableauEnObjet($ligneResultat);

                $listeModel[] = $model;
            }
        } catch (PDOException $e) {
            echo "Le site rencontre un problème. Veuillez réessayer plus tard...<br>";

            //juste pour l'exemple. Ce n'est pas forcément une bonne idée d'afficher
            //des informations sensibles comme le nom de la base de données
            //On peut par ex l'enregistrer dans un fichier de log
            echo "Info : " . $e->getMessage();
            die();
        }

        return $listeModel;
    }

    public function getNomTable()
    {
        //si la classe s'appelle DAO\UtilisateurDao, $table retourne utilisateur
        return strtolower(substr(get_class($this), 4, -3));
    }

    public function getNomClassModel()
    {
        return "Model\\" . substr(get_class($this), 4, -3);
    }

    public function transformeTableauEnObjet($tableau)
    {

        $nomClasseModel = $this->getNomClassModel();

        //on créé une instance de la classe 
        $model = new $nomClasseModel();

        //pour chaque index de $tableau $ligneResultat
        foreach ($tableau as $key => $valeur) {

            //on en déduit le setter
            $nomSetter = "set" . ucfirst($key);

            //mot_de_passe -> setMotDePasse
            //etape 1: "mot de passe", enlèvement des espaces (str_replace)
            $nomSetter = str_replace("_", " ", $key);

            //etape 2: "Mot De Passe" (ucword)
            $nomSetter = ucwords($nomSetter);

            //etape 3: "MotDePasse (str_replace)
            $nomSetter = str_replace(" ", "", $nomSetter);

            //etape 4: "setMotDePasse (str_replace)
            $nomSetter = "set" . $nomSetter;

            //ou en une seule ligne :
            //$nomSetter = "set" . str_replace(" ", "", ucword(str_replace("_", " ", $key)));


            //si le setter existe bien
            if (method_exists($nomClasseModel, $nomSetter)) {
                $model->$nomSetter($valeur);
            }
        }
        return $model;
    }

    /**
     * Retourne l'utilisateur correspondant à l'ID passé en paramètres
     */
    public function findById($id)
    {
        $sql = "SELECT * FROM " . $this->getNomTable() . " WHERE id = :id";

        try {
            $connexion = new Connexion();

            $requete = $connexion->prepare($sql);

            $requete->execute(
                [
                    ":id" => $id
                ]
            );

            return $this->transformeTableauEnObjet($requete->fetch());
        } catch (PDOException $e) {
            echo "erreur... :(";
        }
    }

    public function deleteById($id)
    {
        $sql = "DELETE FROM " . $this->getNomTable() . " WHERE id = :id";

        try {
            $connexion = new Connexion();

            $requete = $connexion->prepare($sql);

            $requete->execute(
                [
                    ":id" => $id
                ]
            );

        } catch (PDOException $e) {
            echo "erreur... :(". $e->getMessage();
        }
    }
}