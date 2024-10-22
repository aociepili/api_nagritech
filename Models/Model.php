<?php

namespace App\Models;

use App\Core\Db;

class Model extends Db
{
    // Table de la Table de donnees
    protected $table;

    // Instance de DB
    private $db;
    // lire tout les informations
    public function findAll()
    {
        $query = $this->requete('SELECT * FROM ' . $this->table);
        return $query->fetchAll();
    }


    // lire les informations specifiques 
    public function findBy(array $criteres)
    {
        $champs = [];
        $valeurs = [];

        // on boucle pour eclater le tableau
        foreach ($criteres as $champ => $valeur) {
            // SELECT * FROM annonces WHERE actif=? AND signale=0
            // bindValues (1,valeur)

            $champs[] = "$champ = ?";
            $valeurs[] = $valeur;
        }
        // on transforme le tableau Champs en une chaine de caracteres
        $liste_champs = implode(' AND ', $champs);

        // on execute la requete
        return $this->requete('SELECT * FROM ' . $this->table . ' WHERE ' . $liste_champs, $valeurs)->fetchAll();
    }

    public function find(int $id)
    {
        return $this->requete("SELECT * FROM $this->table WHERE id = $id")->fetch();
    }


    public function create(Model $model)
    {
        $champs = [];
        $valeurs = [];
        $inter = [];

        // on boucle pour eclater le tableau
        foreach ($model as $champ => $valeur) {
            // INSERT INTO  annonces (titre,description,actif) VALUES (?,?,?)
            if ($valeur != null && $champ != 'db' && $champ != 'table') {
                $champs[] = $champ;
                $valeurs[] = $valeur;
                $inter[] = "?";
            }
        }
        // on transforme le tableau Champs en une chaine de caracteres
        $liste_champs = implode(' , ', $champs);
        $liste_inter = implode(' , ', $inter);

        // echo $liste_champs; die($liste_inter);
        // on execute la requete
        return $this->requete('INSERT INTO ' . $this->table . ' (' . $liste_champs . ') VALUES(' . $liste_inter . ')', $valeurs);
    }
    public function createAndId(Model $model)
    {
        $champs = [];
        $valeurs = [];
        $inter = [];

        // on boucle pour eclater le tableau
        foreach ($model as $champ => $valeur) {
            // INSERT INTO  annonces (titre,description,actif) VALUES (?,?,?)
            if ($valeur != null && $champ != 'db' && $champ != 'table') {
                $champs[] = $champ;
                $valeurs[] = $valeur;
                $inter[] = "?";
            }
        }
        // on transforme le tableau Champs en une chaine de caracteres
        $liste_champs = implode(' , ', $champs);
        $liste_inter = implode(' , ', $inter);

        // echo $liste_champs; die($liste_inter);
        // on execute la requete
        return $this->getLastId('INSERT INTO ' . $this->table . ' (' . $liste_champs . ') VALUES(' . $liste_inter . ')', $valeurs);
    }

    public function update(int $id, Model $model)
    {
        $champs = [];
        $valeurs = [];

        // on boucle pour eclater le tableau
        foreach ($model as $champ => $valeur) {
            // UPDATE  annonces SET titre=?,description=?,actif=? WHERE id=?
            if ($valeur !== null && $champ != 'db' && $champ != 'table') {
                $champs[] = "$champ = ?";
                $valeurs[] = $valeur;
            }
        }

        $valeurs[] = $id;
        // on transforme le tableau Champs en une chaine de caracteres
        $liste_champs = implode(' , ', $champs);

        // echo $liste_champs; die($liste_inter);
        // on execute la requete
        return $this->requete('UPDATE ' . $this->table . ' SET ' . $liste_champs . ' WHERE id = ?', $valeurs);
    }

    public function delete(int $id)
    {
        return $this->requete("DELETE FROM {$this->table} WHERE id=? ", [$id]);
    }
    public function requete(string $sql, array $attributs = null)
    {
        // on recupere l'instance de db
        $this->db = Db::getInstance();

        //On verifie si on a des attributs 
        if ($attributs !== null) {

            // requete prepare
            $query = $this->db->prepare($sql);
            $query->execute($attributs);
            return $query;
        } else {
            // requete simple
            return $this->db->query($sql);
        }
    }
    public function getLastId(string $sql, array $attributs = null)
    {
        // on recupere l'instance de db
        $this->db = Db::getInstance();

        //On verifie si on a des attributs 
        if ($attributs !== null) {

            // requete prepare
            $connection = $this->db;
            $connection->exec($sql);
            $idData = $connection->lastInsertId();
            return $idData;
        } else {
            // requete simple
            $connection = $this->db;
            $connection->exec($sql);
            $idData = $connection->lastInsertId();
            return $idData;
        }
    }

    public function hydrate(array $data)
    {

        foreach ($data as $key => $value) {
            # on recupere le nom du setteer correspondant a la cle key
            # titre -> setTitre
            $setter = 'set' . ucfirst($key);

            #on verifie si la methode existe
            if (method_exists($this, $setter)) {
                #on appelle le setter
                $this->$setter($value);
            }
        }
        return $this;
    }
}
