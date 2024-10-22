<?php

namespace App\Models;

use App\Models\Model;

class NaturesModel extends Model
{
    protected $id;
    protected $designation;
    protected $type;
    protected $categorie;
    protected $mode;
    protected $status;
    protected $cat_produit_id;
    protected $prixunitaire;
    protected $devise;
    protected $created_at;
    protected $updated_at;


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
    public function getDesignation()
    {
        return $this->designation;
    }

    public function setDesignation($designation)
    {
        $this->designation = $designation;
        return $this;
    }
    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }
    public function getCategorie()
    {
        return $this->categorie;
    }

    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;
        return $this;
    }
    public function getMode()
    {
        return $this->mode;
    }

    public function setMode($mode)
    {
        $this->mode = $mode;
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
    public function getCat_produit_id()
    {
        return $this->cat_produit_id;
    }

    public function setCat_produit_id($cat_produit_id)
    {
        $this->cat_produit_id = $cat_produit_id;
        return $this;
    }
    public function getPrixunitaire()
    {
        return $this->prixunitaire;
    }

    public function setPrixunitaire($prixunitaire)
    {
        $this->prixunitaire = $prixunitaire;
        return $this;
    }

    public function getDevise()
    {
        return $this->devise;
    }

    public function setDevise($devise)
    {
        $this->devise = $devise;
        return $this;
    }

    public function __construct()
    {
        $this->table = 'natures';
    }
}