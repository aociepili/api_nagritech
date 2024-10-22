<?php

namespace App;

class Autoloader
{
    static function register()
    {
        spl_autoload_register([__CLASS__, 'autoload']);
    }

    static function autoload($class)
    {
        // on recupere dans $class la totalite du Namespace de la classe concernee  
        // App\Banque\CompteCourant => Banque/CompteCournat

        // On retire App\
        $class = str_replace(__NAMESPACE__ . '\\', '', $class);

        // on remplace \ par de /  Banque/CompteCournat => Banque/CompteCournat
        $class = str_replace('\\', '/', $class);
        // require_once  __DIR__.'/'.$class.'.php';

        // On verifie si le fichier existe
        $file = __DIR__ . '/' . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
}