<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('../Autoloader.php');
include('controllers.php');


use App\Autoloader;
use App\Models\ClientsModel;
use App\Models\PersonnesModel;
use App\Models\AdressesModel;

Autoloader::register();

# Store Client Personne Physique
function storeClient($clientData)
{
    #test de chargement 
    chargementClient($clientData);

    $clientData["services_id"] = CLIENT_SERVICE;
    $clientData["email"] = $clientData["nom"] . "@nagritech.com";
    $testService = testServicebyId(CLIENT_SERVICE);
    $testTranche = testTrancheAgebyId($clientData["tranche_age_id"]);

    #Test validite de l'Email
    // $testEmail = filter_var($clientData["email"], FILTER_VALIDATE_EMAIL);

    // $testEmailExist = CompareEmail($clientData["email"]);
    #Test sur telephone
    $testTelephone = isValidTelephone($clientData["telephone"]);
    $testTelephoneExist = isExistTelephone($clientData["telephone"]);


    if (($testTelephone)) {
        if ($testTelephoneExist && $testService && $testTranche) {
            #Adresse Valide 
            #Creation de l'adresse
            createAdresse($clientData);
            #rechercher de l'ID de l'adresse creee
            $idAdresse = getLastAdresse($clientData)->id;

            if (empty($idAdresse)) {
                return success205("Pas d'enregistrement A");
            } else {

                # Processus de Creation Personne
                createPersonne($clientData, $idAdresse);

                #rechercher de l'ID de la Personne creee
                $idPersonne = getLastPersonne($clientData, $idAdresse)->id;


                if (empty($idPersonne)) {
                    return success205("Pas d'enregistrement P");
                } else {

                    #Processus de Creation de l'administrateur
                    createClient($clientData, $idPersonne);
                    createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_CLIENT);
                    $message = "client created successfully";
                    return success201($message);
                }
            }
        } else {
            error406("Adresse Email Invalide");
        }
    } else {
        error406("Numero de Telephone Invalide");
    }
}
# Store Client Personne Morale
function storeClientMoral($clientData)
{
    #test de chargement 
    chargementClientMoral($clientData);

    $clientData["services_id"] = CLIENT_SERVICE;
    // $clientData["email"] = $clientData["nom"] . "@nagritech.com";
    $testService = testServicebyId(CLIENT_SERVICE);


    #Test validite de l'Email
    $testEmail = filter_var($clientData["email"], FILTER_VALIDATE_EMAIL);

    $testEmailExist = CompareEmail($clientData["email"]);
    #Test sur telephone
    $testTelephone = isValidTelephone($clientData["telephone"]);
    $testTelephoneExist = isExistTelephone($clientData["telephone"]);


    if (($testTelephone)) {
        if ($testTelephoneExist && $testService  &&  $testEmail && $testEmailExist) {
            #Adresse Valide 
            #Creation de l'adresse
            createAdresse($clientData);
            #rechercher de l'ID de l'adresse creee
            $idAdresse = getLastAdresse($clientData)->id;

            if (empty($idAdresse)) {
                return success205("Pas d'enregistrement A");
            } else {

                # Processus de Creation Personne
                createPersonneMorale($clientData, $idAdresse);
                #rechercher de l'ID de la Personne creee
                $idPersonne = getLastPersonneMorale($clientData, $idAdresse)->id;

                if (empty($idPersonne)) {
                    return success205("Pas d'enregistrement P");
                } else {

                    #Processus de Creation de l'administrateur
                    createClientMoral($clientData, $idPersonne);
                    createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_CLIENT);
                    $message = "client created successfully";
                    return success201($message);
                }
            }
        } else {
            error406("Adresse Email Invalide");
        }
    } else {
        error406("Numero de Telephone Invalide");
    }
}


#Delete
function deleteClient($clientParams)
{
    $clientModel = new ClientsModel();
    paramsVerify($clientParams, "Client");

    # On recupere les informations venues de POST

    $clientID = $clientParams['id'];

    $clientFoundData = $clientModel->find($clientID);
    $personneID = $clientFoundData->personnes_idPersonne;

    if ($clientID == $clientFoundData->id) {
        $clientModel->delete($clientFoundData->id);
        $test = deletePersonneData($personneID);

        if ($test) {
            $message = "Client deleted successfully";
            createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_CLIENT);
            return success200($message);
        }
    } else {
        $message = "Client not delete ";
        return error405($message);
    }
}

#Get
function getClientById($clientParams)
{
    $clientModel = new ClientsModel();
    paramsVerify($clientParams, "Client");
    $dataFoundClient = $clientModel->find($clientParams['id']);

    if (!empty($dataFoundClient)) {
        $dataClientAll = getClientDataById($dataFoundClient->id);
        $message = "client Fetched successfully";
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_CLIENT);
        return datasuccess200($message, $dataClientAll);
    } else {
        $message = "Client not found";
        return success205($message);
    }
}

function getClientByTelephone($data)
{
    $clientModel = new ClientsModel();
    $telephone = $data["telephone"];
    $telephoneIsValid = isValidTelephone($telephone);

    if ($telephoneIsValid) {

        $phone = array(
            "telephone" => $telephone,
        );

        $clientModel = new ClientsModel();
        $userClient = $clientModel->findBy($phone);

        if (empty((array)$userClient)) {
            $message = "Client not found : il y a aucun client possedant ce numero";
            return success205($message);
        } else {
            $clientID = $userClient[0]->id;
            $dataClientAll = getClientDataById($clientID);
            $message = "client Fetched successfully";
            createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_CLIENT);
            return datasuccess200($message, $dataClientAll);
        }
    } else {
        success203(" Numero Invalide : Veuillez verifier le numero de telephone");
    }
}

function getListClientMoral()
{
    $clientModel = new ClientsModel();
    $data = array(
        "is_legal_person" => true,
    );
    $clientMoral = (array)$clientModel->findBy($data);

    if (!empty($clientMoral)) {
        $message = "Liste des Client (Personne Morale)";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_CLIENT);
        return dataTableSuccess200($message, $clientMoral);
    } else {
        $message = "Pas de Client (Personne Morale)";
        return success205($message);
    }
}
function getListClientPhysique()
{
    $clientModel = new ClientsModel();
    $data = array(
        "is_legal_person" => false,
    );
    $clientMoral = (array)$clientModel->findBy($data);

    if (!empty($clientMoral)) {
        $message = "Liste des Client (Personne Physique)";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_CLIENT);
        return dataTableSuccess200($message, $clientMoral);
    } else {
        $message = "Pas de Client (Personne Physique)";
        return success205($message);
    }
}
function getListClient()
{
    $clientModel = new ClientsModel();

    $clients = (array)$clientModel->findAll();

    if (!empty($clients)) {
        $dataClient = getListClientDataById($clients);
        $message = "Liste des clients";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_CLIENT);
        return dataTableSuccess200($message, $dataClient);
    } else {
        $message = "Pas de client dans la BD";
        return success205($message);
    }
}

# Update
function updateClient($clientData, $clientParams)
{
    $clientModel = new ClientsModel();
    $adresseModel = new AdressesModel();
    $personneModel = new PersonnesModel();
    $adresse = $adresseModel;
    $personne = $personneModel;
    $client = $clientModel;

    paramsVerify($clientParams, "Client");

    #client
    $email = $clientData["email"];
    $telephone = $clientData["telephone"];
    $password = $clientData["password"];
    $cpassword  = password_hash($password, PASSWORD_BCRYPT, COST);
    $today = getSiku();

    #chargement client
    $client->setEmail($email);
    $client->setTelephone($telephone);
    $client->setPassword($cpassword);
    $client->setUpdated_at($today);

    #Personne
    $nom = $clientData["nom"];
    $postnom = $clientData["postnom"];
    $prenom = $clientData["prenom"];
    $sexe = $clientData["sexe"];
    $personne->setNom($nom);
    $personne->setPostnom($postnom);
    $personne->setPrenom($prenom);
    $personne->setSexe($sexe);

    #adresse
    $pays = $clientData["pays"];
    $ville = $clientData["ville"];
    $commune = $clientData["commune"];
    $quartier = $clientData["quartier"];
    $avenue = $clientData["avenue"];
    $adresse->setPays($pays);
    $adresse->setVille($ville);
    $adresse->setCommune($commune);
    $adresse->setQuartier($quartier);
    $adresse->setAvenue($avenue);

    $clientID = $clientParams["id"];
    $clientFound = $clientModel->find($clientID);

    if (empty($clientFound)) {
        $message = "No client Found ";
        return success205($message);
    } else {
        #Personne ID
        $personneID = $clientFound->personnes_idPersonne;

        #Adresse ID
        $personData = $personneModel->find($personneID);
        $adresseID = $personData->adresses_idAdresse;

        $test = ($clientID == $clientFound->id);

        if ($test) {
            #Avant de Update on verifie si la table a subi de modification
            if (modClient($clientData) and isset($today)) {
                $clientModel->update($clientID, $client);
            }
            if (modAdresse($clientData)) {
                $adresseModel->update($adresseID, $adresse);
            }
            if (modPersonne($clientData)) {
                $personneModel->update($personneID, $personne);
            }

            # On modifie l'Adresse et personne  dans la BD
            createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_CLIENT);
            $message = "client updated successfully";
            return success200($message);
        } else {
            $message = "No client Found ";
            return success205($message);
        }
    }
}
# Update Client Moral
function updateClientMoral($clientData, $clientParams)
{
    $clientModel = new ClientsModel();
    $adresseModel = new AdressesModel();
    $personneModel = new PersonnesModel();
    $adresse = $adresseModel;
    $personne = $personneModel;
    $client = $clientModel;

    paramsVerify($clientParams, "Client");

    #client
    $email = $clientData["email"];
    $telephone = $clientData["telephone"];
    $password = $clientData["password"];
    $cpassword  = password_hash($password, PASSWORD_BCRYPT, COST);
    $today = getSiku();

    #chargement client
    $client->setEmail($email);
    $client->setTelephone($telephone);
    $client->setPassword($cpassword);
    $client->setUpdated_at($today);

    #Personne
    $nom_entreprise = $clientData["nom_entreprise"];
    $nom = $clientData["nom"];
    $titre = $clientData["postnom"];
    $annee_existence = $clientData["prenom"];
    $sexe = $clientData["sexe"];
    $personne->setNom_entreprise($nom_entreprise);
    $personne->setNom($nom);
    $personne->setTitre($titre);
    $personne->setAnnee_existence($annee_existence);
    $personne->setSexe($sexe);

    #adresse
    $pays = $clientData["pays"];
    $ville = $clientData["ville"];
    $commune = $clientData["commune"];
    $quartier = $clientData["quartier"];
    $avenue = $clientData["avenue"];
    $adresse->setPays($pays);
    $adresse->setVille($ville);
    $adresse->setCommune($commune);
    $adresse->setQuartier($quartier);
    $adresse->setAvenue($avenue);

    $clientID = $clientParams["id"];
    $clientFound = $clientModel->find($clientID);

    if (empty($clientFound)) {
        $message = "No client Found ";
        return success205($message);
    } else {
        #Personne ID
        $personneID = $clientFound->personnes_idPersonne;

        #Adresse ID
        $personData = $personneModel->find($personneID);
        $adresseID = $personData->adresses_idAdresse;

        $test = ($clientID == $clientFound->id);

        if ($test) {
            #Avant de Update on verifie si la table a subi de modification
            if (modClient($clientData) and isset($today)) {
                $clientModel->update($clientID, $client);
            }
            if (modAdresse($clientData)) {
                $adresseModel->update($adresseID, $adresse);
            }
            if (modPersonne($clientData)) {
                $personneModel->update($personneID, $personne);
            }

            # On modifie l'Adresse et personne  dans la BD
            $message = "client updated successfully";
            createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_CLIENT);
            return success200($message);
        } else {
            $message = "No client Found ";
            return success205($message);
        }
    }
}
function updatePassword($clientData, $clientParams)
{
    $clientModel = new ClientsModel();
    $client = $clientModel;

    paramsVerify($clientParams, "Client");

    #client
    $newPassword = $clientData["new_password"];
    $oldPassword = $clientData["old_password"];

    $cNewpassword  = password_hash($newPassword, PASSWORD_BCRYPT, COST);
    $today = getSiku();

    #chargement client
    $client->setPassword($cNewpassword);
    $client->setUpdated_at($today);


    $clientID = $clientParams["id"];
    $clientFound = $clientModel->find($clientID);

    if (empty($clientFound)) {
        $message = "No client Found ";
        return success205($message);
    } else {
        $test = ($clientID == $clientFound->id);

        if ($test) {
            if (password_verify($oldPassword, $clientFound->password)) {
                #Avant de Update on verifie si la table a subi de modification
                $clientModel->update($clientID, $client);

                # On modifie l'Adresse et personne  dans la BD
                $message = "Mot de passe du client updated successfully";
                createActivity(TYPE_OP_CHANGE_PWD, STATUS_OP_OK, TABLE_CLIENT);
                return success200($message);
            } else {
                createActivity(TYPE_OP_CHANGE_PWD, STATUS_OP_NOT, TABLE_CLIENT);
                $message = "Verifier l'ancien mot de passe";
                return success205($message);
            }
        } else {
            $message = "No client Found ";
            return success205($message);
        }
    }
}

function createClient($clientData, $idPersonne)
{
    $clientModel = new ClientsModel();
    $client = $clientModel;
    #client
    $email = $clientData["email"];
    $telephone = $clientData["telephone"];
    $password = $clientData["password"];
    $cpassword  = password_hash($password, PASSWORD_BCRYPT, COST);
    $status = "1";
    $token = "OFF";
    $personnes_idPersonne = $idPersonne;
    $services_id = $clientData["services_id"];
    $tranche_age_id = $clientData["tranche_age_id"];
    $today = getSiku();

    #chargement client
    $client->setEmail($email);
    $client->setTrancheAgeId($tranche_age_id);
    $client->setTelephone($telephone);
    $client->setPassword($cpassword);
    $client->setStatus($status);
    $client->setToken($token);
    $client->setPersonnes_idPersonne($personnes_idPersonne);
    $client->setServices_id($services_id);
    $client->setCreated_at($today);
    $clientModel->create($client);
}
function createClientMoral($clientData, $idPersonne)
{
    $clientModel = new ClientsModel();
    $client = $clientModel;
    #client
    $email = $clientData["email"];
    $telephone = $clientData["telephone"];
    $password = $clientData["password"];
    $cpassword  = password_hash($password, PASSWORD_BCRYPT, COST);
    $status = "1";
    $token = "OFF";
    $personnes_idPersonne = $idPersonne;
    $services_id = $clientData["services_id"];
    $tranche_age_id = 0;
    $is_legal_person = true;
    $today = getSiku();

    #chargement client
    $client->setEmail($email);
    $client->setTrancheAgeId($tranche_age_id);
    $client->setTelephone($telephone);
    $client->setPassword($cpassword);
    $client->setStatus($status);
    $client->setToken($token);
    $client->setPersonnes_idPersonne($personnes_idPersonne);
    $client->setServices_id($services_id);
    $client->setIs_legal_person($is_legal_person);
    $client->setCreated_at($today);
    $clientModel->create($client);
}


function CompareEmail($data)
{

    $test = false;
    $email = array(
        "email" => $data,
    );

    $clientModel = new ClientsModel();
    $userClient = (object)$clientModel->findBy($email);

    if (empty((array)$userClient)) {
        # client n'existe pas
        $test = true;
        return $test;
    } else {
        success203(" Cette adresse mail existe deja");
    }
}

function isExistTelephone($telephone)
{
    $test = false;
    $dataTelephone = array(
        "telephone" => $telephone,
    );

    $clientModel = new ClientsModel();
    $userClient = (object)$clientModel->findBy($dataTelephone);

    if (empty((array)$userClient)) {
        # Le numero de telephone n'existe pas
        $test = true;
        return $test;
    } else {
        success203(" Ce numero de telephone existe deja");
    }
}

function login($dataClient)
{
    #Test validite de l'Email
    $test = isValidTelephone($dataClient["telephone"]);

    if (isset($dataClient['telephone']) and isset($dataClient['password'])) {
        if ($test == 1) {
            $telephone = $dataClient['telephone'];
            $pwd = $dataClient['password'];
            $userClient = getUserClientbyTelephone($telephone);

            if (password_verify($pwd, $userClient->password)) {
                $clientModel = new ClientsModel();
                $client = $clientModel;
                # Generation du Token
                require_once 'php-jwt/includes/config.php';
                require_once 'php-jwt/classes/JWT.php';

                // On crée le header
                $header = formatHeader();
                // On crée le contenu (payload)
                $payload = [
                    'isOnline' => true,
                    'id' => $userClient->id,
                    'role' => 'isClient',
                    'email' => $userClient->telephone
                ];
                $jwt = new JWT();
                $token = $jwt->generate($header, $payload, SECRET, TOKEN_VALIDITE);
                $payloadBD = $jwt->getPayload($token);
                $user = (array) $payloadBD;

                $client->setToken($token);
                $clientID = $userClient->id;
                $clientModel->update($clientID, $client);
                logActivity($payload, TYPE_OP_LOGIN, STATUS_OP_OK, TABLE_CLIENT);
                $message = "Client s'est connecté avec succés";
                return datasuccess202($message, $token, $user);
            } else {
                return error401("Pas de connexion : Numero de Telephone ou Mot de passe incorrect");
            }
        } else {
            error406("Numero de Telephone Invalide");
        }
    } else {
        $message = "Veuillez renseigner votre Numero de telephone ou votre mot de passe";
        error422($message);
    }
}

function getUserClientbyTelephone($data)
{

    $login = array(
        "telephone" => $data,
    );
    $clientModel = new ClientsModel();
    $userClient = (object)$clientModel->findBy($login);

    if (empty((array)$userClient)) {
        error401("Numero de Telephone ou Mot de passe incorrect");
    } else {
        $client = (array)$userClient;
        $user = $client[0];
        return $user;
    }
}

function isAuthentify()
{
    require 'php-jwt/authentification.php';
    require_once 'php-jwt/classes/JWT.php';

    $jwt = new JWT();
    $payload = authentification();
    $user = (array)json_decode($payload);
    $id = $user['id'];

    $clientModel = new ClientsModel();
    $userClient = (object)$clientModel->find($id);

    $payloadBD = $jwt->getPayload($userClient->token);
    $userClient = (array) $payloadBD;

    if (($user['isOnline'] == $userClient['isOnline'])) {
        # client est connecte
        $message = "Client est connecté";
        return datasuccess200($message, $user);
    } else {
        error405("Client s'est deja  deconnecté");
    }
}


function logout()
{
    require_once 'php-jwt/authentification.php';
    $payload = authentification();

    $user = (array)json_decode($payload);

    if (isset($user['id'])) {
        $id = $user['id'];


        $clientModel = new ClientsModel();
        $userClient = (object)$clientModel->find($id);

        if (empty((array)$userClient)) {
            # Admin n'est pas connecte
            $message = "Client est hors ligne";
            return error401($message);
        } else {
            # Deconnexion de l' Admin 
            $client = $clientModel;

            # Generation du Token
            require_once 'php-jwt/includes/config.php';
            require_once 'php-jwt/classes/JWT.php';

            # On crée le header
            $header = formatHeader();
            # On crée le contenu (payload)
            $payload = [
                'isOnline' => false,
                'id' => $userClient->id,
                'role' => 'isclient',
                'email' => $userClient->telephone
            ];

            $jwt = new JWT();
            $tokenOff = $jwt->generate($header, $payload, SECRET, 0);

            $client->setToken($tokenOff);
            $clientID = $userClient->id;
            $clientModel->update($clientID, $client);
            logActivity($payload, TYPE_OP_LOGOUT, STATUS_OP_OK, TABLE_CLIENT);
            $message = "Client s'est deconnecté";
            return success202($message);
        }
    } else {

        $message = "La session de l'client a deja expiré";
        return error401($message);
    }
}

function activeClient($clientParams)
{

    $clientModel = new clientsModel();
    $client = $clientModel;

    paramsVerify($clientParams, "client");
    $dataclient = $clientModel->find($clientParams['id']);
    $status = $dataclient->status;

    if (!empty($dataclient)) {
        switch ($status) {
            case '1': {
                    $message = "Le compte client est deja activé";
                    return success205($message);
                }
            case '0': {
                    # Active...
                    $client->setStatus(true);
                    $clientModel->update($clientParams['id'], $client);
                    $message = "Le compte client est activé";
                    return success205($message);
                }

            default: {
                    $message = "Verifier le status de l'client";
                    return success205($message);
                }
        }
    } else {
        $message = "client not found";
        return success205($message);
    }
}
function desactiveClient($clientParams)
{
    $clientModel = new clientsModel();
    $client = $clientModel;

    paramsVerify($clientParams, "client");
    $dataclient = $clientModel->find($clientParams['id']);
    $status = $dataclient->status;

    if (!empty($dataclient)) {
        switch ($status) {
            case '0': {
                    $message = "Le compte client est deja desactivé";
                    return success205($message);
                }
            case '1': {
                    # Active...
                    $client->setStatus(false);
                    $clientModel->update($clientParams['id'], $client);
                    $message = "Le compte client est désactivé";
                    return success205($message);
                }

            default: {
                    $message = "Verifier le status de l'client";
                    return success205($message);
                }
        }
    } else {
        $message = "client not found";
        return success205($message);
    }
}