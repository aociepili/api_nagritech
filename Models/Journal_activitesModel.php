<?php

namespace App\Models;

use App\Models\Model;

class Journal_activitesModel extends Model
{
    protected $id;
    protected $user_id;
    protected $role_id;
    protected $type_op_id;
    protected $status_op_id;
    protected $table_id;

    protected $created_at;

    public function getTable_id()
    {
        return $this->table_id;
    }

    public function setTable_id($table_id)
    {
        $this->table_id = $table_id;
        return $this;
    }


    public function getStatus_op_id()
    {
        return $this->status_op_id;
    }

    public function setStatus_op_id($status_op_id)
    {
        $this->status_op_id = $status_op_id;
        return $this;
    }

    public function getType_op_id()
    {
        return $this->type_op_id;
    }

    public function setType_op_id($type_op_id)
    {
        $this->type_op_id = $type_op_id;
        return $this;
    }

    public function getRole_id()
    {
        return $this->role_id;
    }

    public function setRole_id($role_id)
    {
        $this->role_id = $role_id;
        return $this;
    }


    public function getUser_id()
    {
        return $this->user_id;
    }

    public function setUser_id($user_id)
    {
        $this->user_id = $user_id;
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


    public function __construct()
    {
        $this->table = 'journal_activites';
    }
}