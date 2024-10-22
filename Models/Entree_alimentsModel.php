<?php

namespace App\Models;

use App\Models\Model;

class Entree_alimentsModel extends Model
{
    protected $id;
    protected $quantite;
    protected $entrees_idEntree;
    protected $stock_Aliments_idStock;
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
        return $this->quantite;
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

    public function getStock_Aliments_idStock()
    {
        return $this->stock_Aliments_idStock;
    }

    public function setStock_Aliments_idStock($stock_Aliments_idStock)
    {
        $this->stock_Aliments_idStock = $stock_Aliments_idStock;
        return $this;
    }







    public function __construct()
    {
        $this->table = 'entree_aliments';
    }
}
