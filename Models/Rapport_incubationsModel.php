<?php

namespace App\Models;

use App\Models\Model;

class Rapport_incubationsModel extends Model
{
    protected $id;
    protected $quantite;
    protected $date;
    protected $etat_rapportID;
    protected $commentaire;
    protected $agents_idAgent;
    protected $natures_idNature;
    protected $status_rapport_id;
    protected $incubation_id;

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
    public function getIncubation_id()
    {
        return $this->incubation_id;
    }
    public function setIncubation_id($incubation_id)
    {
        $this->incubation_id = $incubation_id;
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

    public function getEtat_rapportID()
    {
        return $this->etat_rapportID;
    }
    public function setEtat_rapportID($etat)
    {
        $this->etat_rapportID = $etat;
        return $this;
    }

    public function getCommentaire()
    {
        return $this->commentaire;
    }
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;
        return $this;
    }

    public function getAgents_idAgent()
    {
        return $this->agents_idAgent;
    }
    public function setAgents_idAgent($agents_idAgent)
    {
        $this->agents_idAgent = $agents_idAgent;
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

    public function getStatus_rapport_id()
    {
        return $this->status_rapport_id;
    }
    public function setStatus_rapport_id($status_rapport_id)
    {
        $this->status_rapport_id = $status_rapport_id;
        return $this;
    }

    public function __construct()
    {
        $this->table = 'rapport_incubations';
    }
}
