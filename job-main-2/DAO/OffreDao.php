<?php


namespace   DAO;

use PDOException;

class OffreDao extends BaseDao {

    public function findByOffre($offre)
    {
         try {
             $connexion = new Connexion();
             $requete = $connexion->prepare("SELECT * FROM offre WHERE offre= :offre");

             $requete->execute(
                 [
                     "offre" => $offre
                 ]
                 );
                 return $this->transformeTableauEnObjet($requete->fetchAll());
         }catch (PDOException $e){
            echo "error...";
         }
    }
}