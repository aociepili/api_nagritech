<?php

namespace App\Models;

use App\Models\Model;

class PersonnesModel extends Model
{
    protected $id;
    protected $nom;
    protected $titre;
    protected $nom_entreprise;
    protected $prenom;
    protected $postnom;
    protected $sexe;
    protected $adresses_idAdresse;
    protected $annee_existence;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    public function getNom()
    {
        return $this->nom;
    }
    public function setNom($nom)
    {
        $this->nom = $nom;
        return $this;
    }
    public function getTitre()
    {
        return $this->titre;
    }
    public function setTitre($titre)
    {
        $this->titre = $titre;
        return $this;
    }
    public function getNom_entreprise()
    {
        return $this->nom_entreprise;
    }
    public function setNom_entreprise($nom_entreprise)
    {
        $this->nom_entreprise = $nom_entreprise;
        return $this;
    }


    public function getPrenom()
    {
        return $this->prenom;
    }
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
        return $this;
    }


    public function getPostnom()
    {
        return $this->postnom;
    }

    public function setPostnom($postnom)
    {
        $this->postnom = $postnom;
        return $this;
    }
    public function getSexe()
    {
        return $this->sexe;
    }

    public function setSexe($sexe)
    {
        $this->sexe = $sexe;
        return $this;
    }
    public function getAnnee_existence()
    {
        return $this->annee_existence;
    }

    public function setAnnee_existence($annee_existence)
    {
        $this->annee_existence = $annee_existence;
        return $this;
    }
    public function getAdresses_idAdresse()
    {
        return $this->adresses_idAdresse;
    }

    public function setAdresses_idAdresse($adresses_idAdresse)
    {
        $this->adresses_idAdresse = $adresses_idAdresse;
        return $this;
    }

    public function __construct()
    {
        $this->table = 'personnes';
    }
}