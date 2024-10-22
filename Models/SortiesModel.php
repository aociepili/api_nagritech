<?php

namespace App\Models;

use App\Models\Model;

class SortiesModel extends Model
{
    protected $id;
    protected $date;
    protected $natures_idNature;
    protected $motifSorties_idMotif;
    protected $admins_id;
    protected $agents_id;
    protected $role_id;
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

    public function getAdmins_id()
    {
        return $this->admins_id;
    }
    public function setAdmins_id($admins_id)
    {
        $this->admins_id = $admins_id;
        return $this;
    }
    public function getAgents_id()
    {
        return $this->agents_id;
    }
    public function setAgents_id($agents_id)
    {
        $this->agents_id = $agents_id;
        return $this;
    }
    public function getRole_id()
    {
        return $this->role_id;
    }
    public function setRole_id($role_id)
    {
        $this->role_id = $role_id;
        return $this;
    }


    public function __construct()
    {
        $this->table = 'sorties';
    }
}