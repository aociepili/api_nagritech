<?php

namespace App\Models;

use App\Models\Model;

class Stock_pouletsModel extends Model
{
    protected $id;
    protected $designation_lot;
    protected $quantite;
    protected $date;
    protected $etat;
    protected $natures_idNature;
    protected $created_at;
    protected $updated_at;
    protected $fournisseur_id;

    public function getFournisseur_id()
    {
        return $this->fournisseur_id;
    }
    public function setFournisseur_id($fournisseur_id)
    {
        $this->fournisseur_id = $fournisseur_id;
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
    public function getCreated_at()
    {

        return $this->created_at;
    }

    public function setCreated_at($created_at)
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    public function getDesignation_lot()
    {
        return $this->designation_lot;
    }
    public function setDesignation_lot($designation_lot)
    {
        $this->designation_lot = $designation_lot;
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

    public function getDate()
    {
        return $this->date;
    }
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    public function getEtat()
    {
        return $this->etat;
    }
    public function setEtat($etat)
    {
        $this->etat = $etat;
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


    public function __construct()
    {
        $this->table = 'stock_poulets';
    }
}