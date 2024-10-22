<?php

namespace App\Models;

use App\Models\Model;

class Reservation_pouletabattageModel extends Model
{
    protected $idvente;
    protected $quantite;
    protected $date;
    protected $client;
    protected $reservation_PouletAbattage_idEntree;



    public function getIdvente()
    {
        return $this->idvente;
    }
    public function setIdvente($idvente)
    {
        $this->idvente = $idvente;
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

    public function getClient()
    {
        return $this->client;
    }
    public function setClient($client)
    {
        $this->client = $client;
        return $this;
    }

    public function getReservation_PouletAbattage_idEntree()
    {
        return $this->reservation_PouletAbattage_idEntree;
    }
    public function setReservation_PouletAbattage_idEntree($reservation_PouletAbattage_idEntree)
    {
        $this->reservation_PouletAbattage_idEntree = $reservation_PouletAbattage_idEntree;
        return $this;
    }





    public function __construct()
    {
        $this->table = 'vente_pouletabattage';
    }
}