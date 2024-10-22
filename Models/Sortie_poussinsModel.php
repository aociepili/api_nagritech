<?php

namespace App\Models;

use App\Models\Model;

class Sortie_poussinsModel extends Model
{
    protected $id;
    protected $quantite;
    protected $sorties_idSortie;
    protected $clients_id;

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

    public function getSorties_idSortie()
    {
        return $this->sorties_idSortie;
    }
    public function setSorties_idSortie($sorties_idSortie)
    {
        $this->sorties_idSortie = $sorties_idSortie;
        return $this;
    }

    public function getClient_id()
    {
        return $this->clients_id;
    }
    public function setClient_id($clients_id)
    {
        $this->clients_id = $clients_id;
        return $this;
    }





    public function __construct()
    {
        $this->table = 'sortie_poussins';
    }
}
