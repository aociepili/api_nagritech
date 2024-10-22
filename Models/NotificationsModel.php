<?php

namespace App\Models;

use App\Models\Model;

class NotificationsModel extends Model
{
    protected $id;
    protected $description;
    protected $date;
    protected $titre;
    protected $image_news;
    protected $auteurID;
    protected $role;
    protected $active;
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

    public function getImage_news()
    {
        return $this->image_news;
    }

    public function setImage_news($image_news)
    {
        $this->image_news = $image_news;
        return $this;
    }
    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
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
    public function getTitre()
    {
        return $this->titre;
    }

    public function setTitre($titre)
    {
        $this->titre = $titre;
        return $this;
    }
    public function getAuteurID()
    {
        return $this->auteurID;
    }

    public function setAuteurID($auteurID)
    {
        $this->auteurID = $auteurID;
        return $this;
    }
    public function getRole()
    {
        return $this->role;
    }

    public function setRole($role)
    {
        $this->role = $role;
        return $this;
    }
    public function getActive()
    {
        return $this->active;
    }

    public function setActive($active)
    {
        $this->active = $active;
        return $this;
    }

    public function __construct()
    {
        $this->table = 'notifications';
    }
}