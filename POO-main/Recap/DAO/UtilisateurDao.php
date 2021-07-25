<?php

namespace DAO;

use Connexion;
use Model\Utilisateur;
use PDOException;

class UtilisateurDao extends BaseDao
{
    public function findByPseudo($pseudo)
    {
        try {
            $connexion = new Connexion();

            $requete = $connexion->prepare("SELECT * FROM utilisateur WHERE pseudo = :pseudo");

            $requete->execute(
                [
                    ":pseudo" => $pseudo
                ]
                );

                return $this->transformeTableauEnObjet($requete->fetch());

        } catch(PDOException $e) {
            echo "erreur... :(";
        }
    }
}
