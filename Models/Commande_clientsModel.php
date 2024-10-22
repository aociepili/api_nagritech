<?php

namespace App\Models;

use App\Models\Model;

class Commande_clientsModel extends Model
{
    protected $id;
    protected $statusCmd_id;
    protected $date;
    protected $id_sortie;
    protected $paiement_img;
    protected $is_delivered;
    protected $natures_idNature;
    protected $clients_idClient;
    protected $created_at;
    protected $updated_at;


    public function getId_sortie()
    {
        return $this->id_sortie;
    }

    public function setId_sortie($id_sortie)
    {
        $this->id_sortie = $id_sortie;
        return $this;
    }
    public function getPaiement_img()
    {
        return $this->paiement_img;
    }

    public function setPaiement_img($paiement_img)
    {
        $this->paiement_img = $paiement_img;
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
    public function getIs_delivered()
    {
        return $this->is_delivered;
    }

    public function setIs_delivered($is_delivered)
    {
        $this->is_delivered = $is_delivered;
        return $this;
    }


    public function getStatusCmd_id()
    {
        return $this->statusCmd_id;
    }
    public function setStatusCmd_id($statusCmd_id)
    {
        $this->statusCmd_id = $statusCmd_id;
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

    public function getClients_idClient()
    {
        return $this->clients_idClient;
    }

    public function setClients_idClient($clients_idClient)
    {
        $this->clients_idClient = $clients_idClient;
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


    public function __construct()
    {
        $this->table = 'commande_clients';
    }
}