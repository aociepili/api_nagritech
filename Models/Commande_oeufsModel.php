<?php

namespace App\Models;

use App\Models\Model;

class Commande_oeufsModel extends Model
{
    protected $id;
    protected $quantite;
    protected $commandeClients_idCommande;
    protected $montant;
    protected $prixtotal;
    protected $created_at;
    protected $updated_at;



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



    public function getCommandeClients_idCommande()
    {
        return $this->commandeClients_idCommande;
    }
    public function setCommandeClients_idCommande($commandeClients_idCommande)
    {
        $this->commandeClients_idCommande = $commandeClients_idCommande;
        return $this;
    }

    public function getMontant()
    {
        return $this->montant;
    }

    public function setMontant($montant)
    {
        $this->montant = $montant;
        return $this;
    }

    public function getPrixtotal()
    {
        return $this->prixtotal;
    }

    public function setPrixtotal($prixtotal)
    {
        $this->prixtotal = $prixtotal;
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

    public function getUpdated_at()
    {
        return $this->updated_at;
    }

    public function setUpdated_at($updated_at)
    {
        $this->updated_at = $updated_at;
        return $this;
    }






    public function __construct()
    {
        $this->table = 'commande_oeufs';
    }
}