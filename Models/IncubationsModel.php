<?php

namespace App\Models;

use App\Models\Model;

class IncubationsModel extends Model
{
    protected $id;
    protected $dateEntree;
    protected $datePrevue;
    protected $dateSortie;
    protected $quantite;
    protected $status_id;

    protected $agents_idAgent;
    protected $natures_idNature;
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



    public function getDateEntree()
    {
        return $this->dateEntree;
    }
    public function setDateEntree($dateEntree)
    {
        $this->dateEntree = $dateEntree;
        return $this;
    }
    public function getDatePrevue()
    {
        return $this->datePrevue;
    }
    public function setDatePrevue($datePrevue)
    {
        $this->datePrevue = $datePrevue;
        return $this;
    }
    public function getDateSortie()
    {
        return $this->dateSortie;
    }
    public function setDateSortie($dateSortie)
    {
        $this->dateSortie = $dateSortie;
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

    public function getStatus_id()
    {
        return $this->status_id;
    }
    public function setStatus_id($status_id)
    {
        $this->status_id = $status_id;
        return $this;
    }

    public function getAgents_idAgent()
    {
        return $this->agents_idAgent;
    }
    public function setAgents_idAgent($agents_idAgent)
    {
        $this->agents_idAgent = $agents_idAgent;
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
        $this->table = 'incubations';
    }
}