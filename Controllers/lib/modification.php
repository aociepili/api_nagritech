<?php

/**
 * modAdmin function
 *
 * la fonction cherche les modifications effectuees sur une attribut de l'admin
 * @param [type] $adminData
 * @return bool
 */
function modAdmin($adminData): bool
{
    $test = false;
    $email = !empty(trim($adminData['email']));
    $telephone = !empty(trim($adminData['telephone']));
    $password = !empty(trim($adminData['password']));
    $categorieAdmins_idCategorie = !empty(trim($adminData['categor$categorieAdmins_idCategorie']));


    if ($email || $telephone || $password || $categorieAdmins_idCategorie) {
        $test = true;
    }
    return $test;
}
function modAgent($agentData)
{
    $test = false;
    $email = !empty(trim($agentData['email']));
    $telephone = !empty(trim($agentData['telephone']));
    $password = !empty(trim($agentData['password']));

    if ($email || $telephone || $password) {
        $test = true;
    }
    return $test;
}
function modClient($clientData)
{
    $test = false;
    $email = !empty(trim($clientData['email']));
    $telephone = !empty(trim($clientData['telephone']));
    $password = !empty(trim($clientData['password']));

    if ($email || $telephone || $password) {
        $test = true;
    }
    return $test;
}
function modFournisseur($fournisseurData)
{
    $test = false;
    $email = !empty(trim($fournisseurData['email']));
    $telephone = !empty(trim($fournisseurData['telephone']));
    $password = !empty(trim($fournisseurData['logo']));

    if ($email || $telephone || $password) {
        $test = true;
    }
    return $test;
}
#Personne
function modPersonne($personneData)
{
    $test = false;
    $nom = !empty(trim($personneData['nom']));
    $postnom = !empty(trim($personneData['postnom']));
    $sexe = !empty(trim($personneData['sexe']));

    if ($nom || $postnom || $sexe) {
        $test = true;
    }
    return $test;
}
function modCmdClient($cmdClientData)
{
    $test = false;
    $status = !empty(trim($cmdClientData['status']));
    $natureID = !empty(trim($cmdClientData['natures_idNature']));
    $clientID = !empty(trim($cmdClientData['clients_idClient']));

    if ($status || $natureID || $clientID) {
        $test = true;
    }
    return $test;
}


function modAdresse($adresseData)
{
    $test = false;
    $ville = !empty(trim($adresseData['ville']));
    $commune = !empty(trim($adresseData['commune']));
    $quartier = !empty(trim($adresseData['quartier']));
    $avenue = !empty(trim($adresseData['avenue']));

    if ($ville || $commune || $quartier || $avenue) {
        $test = true;
    }
    return $test;
}
