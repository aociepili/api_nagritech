<?php

namespace App\Models;

use App\Models\Model;

class Entree_biogazModel extends Model
{
    protected $id;
    protected $quantite;
    protected $entrees_idEntree;
    protected $stock_Biogaz_idStock;
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
    public function getQuantite()
    {
        return $this->quantite;
    }

    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;
        return $this;
    }

    public function getEntrees_idEntree()
    {
        return $this->entrees_idEntree;
    }

    public function setEntrees_idEntree($entrees_idEntree)
    {
        $this->entrees_idEntree = $entrees_idEntree;
        return $this;
    }

    public function getStock_Biogaz_idStock()
    {
        return $this->stock_Biogaz_idStock;
    }

    public function setStock_Biogaz_idStock($stock_Biogaz_idStock)
    {
        $this->stock_Biogaz_idStock = $stock_Biogaz_idStock;
        return $this;
    }







    public function __construct()
    {
        $this->table = 'entree_biogaz';
    }
}
