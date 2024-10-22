<?php

namespace App\Models;

use App\Models\Model;

class AgentsModel extends Model
{
    protected $id;
    protected $telephone;
    protected $email;
    protected $password;

    protected $token;
    protected $status;
    protected $personnes_idPersonne;
    protected $services_id;
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

    public function getPassword()
    {
        return $this->password;
    }
    public function setPassword($password)
    {
        $this->password = $password;
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

    public function getToken()
    {
        return $this->token;
    }
    public function setToken($token)
    {
        $this->token = $token;
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
        $this->table = 'agents';
    }
}