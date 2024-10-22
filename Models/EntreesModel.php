<?php

namespace App\Models;

use App\Models\Model;

class EntreesModel extends Model
{
    protected $id;
    protected $date;
    protected $natures_idNature;
    protected $motifSorties_idMotif;
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





    public function getDate()
    {
        return $this->date;
    }

    public function setDate($date)
    {
        $this->date = $date;
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

    public function getMotifSorties_idMotif()
    {
        return $this->motifSorties_idMotif;
    }

    public function setMotifSorties_idMotif($motifSorties_idMotif)
    {
        $this->motifSorties_idMotif = $motifSorties_idMotif;
        return $this;
    }


    public function __construct()
    {
        $this->table = 'entrees';
    }
}