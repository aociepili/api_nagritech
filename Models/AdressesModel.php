<?php

namespace App\Models;

use App\Models\Model;

class AdressesModel extends Model
{
    protected $id;
    protected $pays;
    protected $ville;
    protected $commune;
    protected $quartier;
    protected $avenue;




    public function getId()
    {
        return $this->id;
    }
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }


    public function getPays()
    {
        return $this->pays;
    }
    public function setPays($pays)
    {
        $this->pays = $pays;
        return $this;
    }
    public function getVille()
    {
        return $this->ville;
    }
    public function setville($ville)
    {
        $this->ville = $ville;
        return $this;
    }
    public function getCommune()
    {
        return $this->commune;
    }
    public function setCommune($commune)
    {
        $this->commune = $commune;
        return $this;
    }
    public function getQuartier()
    {
        return $this->quartier;
    }
    public function setQuartier($quartier)
    {
        $this->quartier = $quartier;
        return $this;
    }
    public function getAvenue()
    {
        return $this->avenue;
    }
    public function setAvenue($avenue)
    {
        $this->avenue = $avenue;
        return $this;
    }


    public function __construct()
    {
        $this->table = 'adresses';
    }
}