<?php

namespace App\Core;

use PDO;
use PDOException;

class Db extends PDO
{


    private static $instance;

    # Information de connexion BD
    private const DB_HOST = 'localhost';
    private const DB_USER = 'root';
    private const DB_PASS = '';
    private const DB_NAME = 'db_nagritech_v4';


    private function __construct()
    {
        // DSN de connexion
        $_dsn = 'mysql:dbname=' . self::DB_NAME . ';host=' . self::DB_HOST;

        // on appelle le constructeur de la classe PDO
        try {
            parent::__construct($_dsn, self::DB_USER, self::DB_PASS);
            $this->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAME utf8');
            $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::FETCH_ASSOC);


            // echo "connexion à la BD a été établie avec succès !";
        } catch (PDOException $e) {
            // echo "Impossible de se connecter à la BD !";
            die($e->getMessage());
        }
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}