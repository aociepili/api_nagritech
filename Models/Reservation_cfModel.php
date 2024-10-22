<?php

namespace App\Models;

use App\Models\Model;

class Reservation_cfModel extends Model
{
    protected $id;
    protected $dateEntree;
    protected $dateFin;
    protected $dateSortie;
    protected $libelle;
    protected $detail;
    protected $status;
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



    public function getDateEntree()
    {
        return $this->dateEntree;
    }
    public function setDateEntree($dateEntree)
    {
        $this->dateEntree = $dateEntree;
        return $this;
    }
    public function getDateFin()
    {
        return $this->dateFin;
    }
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;
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

    public function getLibelle()
    {
        return $this->libelle;
    }
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
        return $this;
    }
    public function getDetail()
    {
        return $this->detail;
    }
    public function setDetail($detail)
    {
        $this->detail = $detail;
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
        $this->table = 'reservation_cf';
    }
}
