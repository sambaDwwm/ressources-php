<?php

namespace Model;


class Utilisateur
{
    protected $id;
    protected $pseudo;
    protected $motDePasse;
    protected $entreprise;


    public function __construct($pseudo = "", $id = NULL)
    {
        $this->id = $id;
        $this->pseudo = $pseudo;
    }

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of pseudo
     */
    public function getPseudo()
    {
        return $this->pseudo;
    }

    /**
     * Set the value of pseudo
     */
    public function setPseudo($pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * Get the value of motDePasse
     */
    public function getMotDePasse()
    {
        return $this->motDePasse;
    }

    /**
     * Set the value of motDePasse
     */
    public function setMotDePasse($motDePasse): self
    {
        $this->motDePasse = $motDePasse;

        return $this;
    }

    /**
     * Get the value of entreprise
     */
    public function getEntreprise()
    {
        return $this->entreprise;
    }

    /**
     * Set the value of entreprise
     */
    public function setEntreprise($entreprise): self
    {
        $this->entreprise = $entreprise;

        return $this;
    }
}