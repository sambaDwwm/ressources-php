<?php

namespace DAO;

use Connexion;
use PDOException;

class UtilisateurDao extends BaseDao
{
    /**
     * prend la table utilisateur  
     */
    public function findByPseudo($pseudo)
    {
        try{
            $connexion = new Connexion();
            $requete = $connexion->prepare("SELECT * FROM utilisateur WHERE pseudo = :pseudo ");

            $requete->execute(
                [
                    "pseudo" => $pseudo
                ]
                );
                $resultat = $requete->fetch();
                var_dump($resultat);
                if($resultat){
                    return $this->transformeTableauEnObjet($resultat);
                }else{
                    return false;
                }
                
        }catch (PDOException $e) {
            echo "error....:(";
        }
    }
}