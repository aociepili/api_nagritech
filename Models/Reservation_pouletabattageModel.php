<?php

namespace App\Models;

use App\Models\Model;

class Reservation_pouletabattageModel extends Model
{
    protected $id;
    protected $quantite;
    protected $date;
    protected $clients_idClient;
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


    public function getDate()
    {
        return $this->date;
    }
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    public function getClients_idClient()
    {
        return $this->clients_idClient;
    }
    public function setClients_idClient($clients_idClient)
    {
        $this->clients_idClient = $clients_idClient;
        return $this;
    }





    public function __construct()
    {
        $this->table = 'reservation_pouletabattage';
    }
}
