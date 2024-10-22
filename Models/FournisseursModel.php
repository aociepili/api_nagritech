<?php

namespace App\Models;

use App\Models\Model;

class FournisseursModel extends Model
{
    protected $id;
    protected $email;
    protected $telephone;
    protected $logo;
    protected $personnes_idPersonne;
    protected $services_id;
    protected $cat_produit_id;
    protected $tranche_age_id;
    protected $status;
    protected $is_legal_person;
    protected $created_at;
    protected $updated_at;

    public function getTrancheAgeId()
    {
        return $this->tranche_age_id;
    }

    public function setTrancheAgeId($tranche_age_id)
    {
        $this->tranche_age_id = $tranche_age_id;
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

    public function getIs_legal_person()
    {
        return $this->is_legal_person;
    }
    public function setIs_legal_person($is_legal_person)
    {
        $this->is_legal_person = $is_legal_person;
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


    public function getCatProduit()
    {
        return $this->cat_produit_id;
    }

    public function setCatProduit($cat_produit_id)
    {
        $this->cat_produit_id = $cat_produit_id;
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



    public function getEmail()
    {
        return $this->email;
    }
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function getTelephone()
    {
        return $this->telephone;
    }
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
        return $this;
    }

    public function getLogo()
    {
        return $this->logo;
    }
    public function setLogo($logo)
    {
        $this->logo = $logo;
        return $this;
    }

    public function getPersonnes_idPersonne()
    {
        return $this->personnes_idPersonne;
    }
    public function setPersonnes_idPersonne($personnes_idPersonne)
    {
        $this->personnes_idPersonne = $personnes_idPersonne;
        return $this;
    }

    public function getServices_id()
    {
        return $this->services_id;
    }
    public function setServices_id($services_id)
    {
        $this->services_id = $services_id;
        return $this;
    }




    public function __construct()
    {
        $this->table = 'fournisseurs';
    }
}