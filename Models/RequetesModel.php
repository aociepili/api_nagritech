<?php

namespace App\Models;

use App\Models\Model;

class RequetesModel extends Model
{
    protected $id;
    protected $question;
    protected $reponse;
    protected $destinateur;
    protected $date;
    protected $lecture;
    protected $expediteur;

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

    public function getQuestion()
    {
        return $this->question;
    }
    public function setQuestion($question)
    {
        $this->question = $question;
        return $this;
    }

    public function getReponse()
    {
        return $this->reponse;
    }
    public function setReponse($reponse)
    {
        $this->reponse = $reponse;
        return $this;
    }


    public function getDestinateur()
    {
        return $this->destinateur;
    }
    public function setDestinateur($destinateur)
    {
        $this->destinateur = $destinateur;
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

    public function getLecture()
    {
        return $this->lecture;
    }
    public function setLecture($lecture)
    {
        $this->lecture = $lecture;
        return $this;
    }



    public function getExpediteur()
    {
        return $this->expediteur;
    }
    public function setExpediteur($expediteur)
    {
        $this->expediteur = $expediteur;
        return $this;
    }



    public function __construct()
    {
        $this->table = 'requetes';
    }
}
