<?php
function isValidEmail($email)
{
    $test = false;
    $atom   = '[-a-z0-9!#$%&\'*+\\/=?^_`{|}~]';   // caractères autorisés avant l'arobase
    $domain = '([a-z0-9]([-a-z0-9]*[a-z0-9]+)?)'; // caractères autorisés après l'arobase (nom de domaine)

    $regex = '/^' . $atom . '+' .   // Une ou plusieurs fois les caractères autorisés avant l'arobase
        '(\.' . $atom . '+)*' .         // Suivis par zéro point ou plus
        // séparés par des caractères autorisés avant l'arobase
        '@' .                           // Suivis d'une arobase
        '(' . $domain . '{1,63}\.)+' .  // Suivi par 1 &#224; 63 caractères autorisés pour le nom de domaine
        // séparés par des points
        $domain . '{2,63}$/i';          // Suivi de 2 &#224; 63 caractères autorisés pour le nom de domaine

    // test de l'adresse e-mail
    if (preg_match($regex, $email)) {
        #Email Valide
        $test = true;
    } else {
    }
    return $test;
}

function isValidTelephone($telephone)
{
    $test = false;
    if (!preg_match("#^(\+[1-9]{1}|\+[123456789][0-9]{1}|\+[123456789][0-9]{2}|0)[98765432][0-9]{8}$#", $telephone)) {
        #numero inavlide
        $test = false;
    } else {
        #Numero valide
        $test = true;
    }
    return $test;
}

    #explication de notre regex :
    #^ : pour dire dès le début de la chaine $numero on veut "(\+33|0)"
    #(\+243|0) : pour dire +33 ou 0, la barre oblique est "ou", les parenthèses nous servent à englober notre condition
    # [98] : suivi de 6 ou 7, les crochets (une classe) nous permettent de demander 6 ou 7, on aurait par exemple pu mettre 679 pour accepte les 09
    # [0-9] : une plage de 0 à 9 (grâce au tiret), de 8 caractères grâce à {8} qui le suit
    # $ : pour dire la fin de la chaine doit s'arrêter là et ne pas accepter autre chose après le numéro