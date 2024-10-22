<?php

namespace App\Models;

use App\Models\Model;

class Commande_fournisseursModel extends Model
{
    protected $id;
    protected $quantite;
    protected $status;
    protected $dateDebut;
    protected $dateFin;
    protected $natures_idNature;
    protected $Fournisseurs_idFournisseur;
    protected $created_at;
    protected $updated_at;



    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getQuantite()
    {
        return $this->quantite;
    }

    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;
        return $this;
    }



    public function getStatus()
    {
        return $this->status;
    }
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }



    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;
        return $this;
    }

    public function getDateFin()
    {
        return $this->dateFin;
    }

    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;
        return $this;
    }

    public function getNatures_idNature()
    {
        return $this->natures_idNature;
    }

    public function setNatures_idNature($natures_idNature)
    {
        $this->natures_idNature = $natures_idNature;
        return $this;
    }

    public function getFournisseurs_idFournisseur()
    {
        return $this->Fournisseurs_idFournisseur;
    }

    public function setFournisseurs_idFournisseur($Fournisseurs_idFournisseur)
    {
        $this->Fournisseurs_idFournisseur = $Fournisseurs_idFournisseur;
        return $this;
    }


    public function getCreated_at()
    {
        return $this->created_at;
    }

    public function setCreated_at($created_at)
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function getUpdated_at()
    {
        return $this->updated_at;
    }

    public function setUpdated_at($updated_at)
    {
        $this->updated_at = $updated_at;
        return $this;
    }


    public function __construct()
    {
        $this->table = 'commande_fournisseurs';
    }
}