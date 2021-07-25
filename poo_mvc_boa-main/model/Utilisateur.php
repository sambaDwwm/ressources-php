<?php

// permete d'utliser specifiquement une class (namespace)
namespace model;

class Utilisateur
{


  // principe encopusalation 
  // 1 propriete 
  private $nom;
  private $prenom;

  // 2 constructor

  public function __construct($nom, $prenom)
  {
    // $this->nom = strtoupper($nom);
    $this->setNom($nom);
    $this->prenom = $prenom;
  }
  //method
  public function nomComplet()
  {
    return $this->nom . "" . $this->prenom;
  }
  //  accesseur
  /**
   * Get the value of nom
   */
  public function getNom()
  {
    return $this->nom;
  }

  /**
   * Set the value of nom
   *
   * @return  self
   */
  public function setNom($nom)
  {
    $this->nom = strtoupper($nom);

    return $this;
  }



  /**
   * Get the value of prenom
   */
  public function getPrenom()
  {
    return $this->prenom;
  }

  /**
   * Set the value of prenom
   *
   * @return  self
   */
  public function setPrenom($prenom)
  {
    $this->prenom = $prenom;

    return $this;
  }
}
