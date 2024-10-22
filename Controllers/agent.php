<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('../Autoloader.php');;
include('controllers.php');


use App\Autoloader;
use App\Models\AgentsModel;
use App\Models\PersonnesModel;
use App\Models\AdressesModel;

Autoloader::register();


# Store
function storeAgent($agentData)
{
    #test de chargement 
    chargementAgent($agentData);

    if ($agentData["services_id"] == null) {
        $agentData["services_id"] = AGENT_SERVICE;
    }

    $testService = testServicebyId($agentData["services_id"]);

    #Test validite de l'Email
    // $testEmail = testEmail($agentData["email"]);
    $testEmail = filter_var($agentData["email"], FILTER_VALIDATE_EMAIL);
    $testEmailExist = CompareEmail($agentData["email"]);

    if ($testEmail && $testEmailExist && $testService) {
        #Adresse Valide 
        #Creation de l'adresse
        createAdresse($agentData);
        #rechercher de l'ID de l'adresse creee
        $idAdresse = getLastAdresse($agentData)->id;

        if (empty($idAdresse)) {
            return error422("Pas d'enregistrement A");
        } else {

            # Processus de Creation Personne
            createPersonne($agentData, $idAdresse);

            #rechercher de l'ID de la Personne creee
            $idPersonne = getLastPersonne($agentData, $idAdresse)->id;

            if (empty($idPersonne)) {
                return error422("Pas d'enregistrement P");
            } else {
                #Processus de Creation de l'agentistrateur
                createAgent($agentData, $idPersonne);
                createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_AGENT);
                $message = "Agent created successfully";
                return success202($message);
            }
        }
    } else {
        error422("Adresse Email Invalide");
    }
}


#Delete
function deleteAgent($agentParams)
{
    $agentModel = new AgentsModel();

    paramsVerify($agentParams, "Agent");
    $agentID = $agentParams['id'];

    $agentFoundData = $agentModel->find($agentID);
    $personneID = $agentFoundData->personnes_idPersonne;

    if ($agentID == $agentFoundData->id) {
        $agentModel->delete($agentFoundData->id);
        $test = deletePersonneData($personneID);

        if ($test) {
            createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_AGENT);
            $message = "Agent deleted successfully";
            return success200($message);
        }
    } else {
        $message = "Agent not delete ";
        return error405($message);
    }
}

#Get
function getAgentById($agentParams)
{
    $agentModel = new AgentsModel();

    paramsVerify($agentParams, "Agent");
    $dataFoundAgent = $agentModel->find($agentParams['id']);

    if (!empty($dataFoundAgent)) {
        $dataAgent = getAgentDataById($dataFoundAgent->id);
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_AGENT);
        $message = "Agent Fetched successfully";
        return datasuccess200($message, $dataAgent);
    } else {
        $message = "No Agent Found";
        return success205($message);
    }
}

function getListAgent()
{
    $agentModel = new AgentsModel();
    $agents = (array)$agentModel->findAll();

    if (!empty($agents)) {
        $dataAgent = getListAgentDataById($agents);
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_AGENT);
        $message = "Liste des Agents";
        return dataTableSuccess200($message, $dataAgent);
    } else {
        $message = "Pas d'agent dans la BD";
        return success205($message);
    }
}

# Update
function updateAgent($agentData, $agentParams)
{
    $agentModel = new AgentsModel();
    $adresseModel = new AdressesModel();
    $personneModel = new PersonnesModel();
    $adresse = $adresseModel;
    $personne = $personneModel;
    $agent = $agentModel;

    paramsVerify($agentParams, "Agent");
    $testEmail = filter_var($agentData["email"], FILTER_VALIDATE_EMAIL);
    $testEmailExist = CompareEmailUpdate($agentData["email"], $agentParams['id']);
    $testService = testServicebyId($agentData["services_id"]);

    if ($testEmail && $testEmailExist && $testService) {
        #Agent
        $email = $agentData["email"];
        $services_id = $agentData["services_id"];
        $telephone = $agentData["telephone"];
        $password = $agentData["password"];
        $cpassword  = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
        $today = getSiku();

        #chargement Agent
        $agent->setEmail($email);
        $agent->setServices_id($services_id);
        $agent->setTelephone($telephone);
        $agent->setPassword($cpassword);
        $agent->setUpdated_at($today);

        #Personne
        $nom = $agentData["nom"];
        $postnom = $agentData["postnom"];
        $prenom = $agentData["prenom"];
        $sexe = $agentData["sexe"];
        $personne->setNom($nom);
        $personne->setPostnom($postnom);
        $personne->setPrenom($prenom);
        $personne->setSexe($sexe);

        #adresse
        $pays = $agentData["pays"];
        $ville = $agentData["ville"];
        $commune = $agentData["commune"];
        $quartier = $agentData["quartier"];
        $avenue = $agentData["avenue"];
        $adresse->setPays($pays);
        $adresse->setVille($ville);
        $adresse->setCommune($commune);
        $adresse->setQuartier($quartier);
        $adresse->setAvenue($avenue);

        $agentID = $agentParams["id"];
        $AgentFound = $agentModel->find($agentID);

        if (empty($AgentFound)) {
            $message = "No Agent Found or Internal Server Error";
            return error500($message);
        } else {
            #Personne ID
            $personneID = $AgentFound->personnes_idPersonne;

            #Adresse ID
            $personData = $personneModel->find($personneID);
            $adresseID = $personData->adresses_idAdresse;

            $test = ($agentID == $AgentFound->id);

            if ($test) {

                #Avant de Update on verifie si la table a subi de modification
                if (modAgent($agentData) and isset($today)) {

                    $agentModel->update($agentID, $agent);
                }
                if (modAdresse($agentData)) {
                    $adresseModel->update($adresseID, $adresse);
                }
                if (modPersonne($agentData)) {
                    $personneModel->update($personneID, $personne);
                }

                # On modifie l'Adresse et personne  dans la BD
                createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_AGENT);
                $message = "Agent updated successfully";
                return success200($message);
            } else {
                $message = "No Agent Found";
                return success205($message);
            }
        }
    } else {
        error422("Adresse Email Invalide");
    }
}

function createAgent($agentData, $idPersonne)
{

    $agentModel = new AgentsModel();
    $agent = $agentModel;
    #Agent
    $email = $agentData["email"];
    $telephone = $agentData["telephone"];
    $password = $agentData["password"];
    $cpassword  = password_hash($password, PASSWORD_BCRYPT, COST);
    $status = "1";
    $token = "OFF";
    $personnes_idPersonne = $idPersonne;
    $services_id = $agentData["services_id"];
    $today = getSiku();

    #chargement Agent
    $agent->setEmail($email);
    $agent->setTelephone($telephone);
    $agent->setPassword($cpassword);
    $agent->setStatus($status);
    $agent->setToken($token);
    $agent->setPersonnes_idPersonne($personnes_idPersonne);
    $agent->setServices_id($services_id);
    $agent->setCreated_at($today);

    $agentModel->create($agent);
}

function CompareEmail($data)
{

    $test = false;
    $email = array(
        "email" => $data,
    );

    $agentModel = new AgentsModel();
    $userAgent = (object)$agentModel->findBy($email);

    if (empty((array)$userAgent)) {
        # Agent n'existe pas
        $test = true;
        return $test;
    } else {
        error401(" Cette adresse mail existe deja");
    }
}

function CompareEmailUpdate($email, $id)
{

    $test = false;
    $email = array(
        "email" => $email,
    );

    $agentModel = new AgentsModel();
    $userAgent = $agentModel->findBy($email);
    if (empty((array)$userAgent)) {
        # Agent n'existe pas
        $test = true;
        return $test;
    } else {
        if ($id == $userAgent[0]->id) {
            # Cet adresse Mail existe et elle appartenait a ce meme agent
            $test = true;
            return $test;
        } else {
            error401(" Cette adresse mail existe deja");
        }
    }
}

function login($dataAgent)
{
    #Test validite de l'Email
    $test = filter_var($dataAgent["email"], FILTER_VALIDATE_EMAIL);

    if (isset($dataAgent['email']) and isset($dataAgent['password'])) {
        if ($test) {
            $email = $dataAgent['email'];
            $pwd = $dataAgent['password'];
            $userAgent = getUserAgentbyEmail($email);

            if (password_verify($pwd, $userAgent->password)) {
                $agentModel = new AgentsModel();
                $agent = $agentModel;

                # Generation du Token
                require_once 'php-jwt/includes/config.php';
                require_once 'php-jwt/classes/JWT.php';

                // On crée le header
                $header = formatHeader();
                // On crée le contenu (payload)
                $payload = [
                    'isOnline' => true,
                    'id' => $userAgent->id,
                    'role' => 'isAgent',
                    'email' => $userAgent->email
                ];
                $jwt = new JWT();
                $token = $jwt->generate($header, $payload, SECRET, TOKEN_VALIDITE);
                $payloadBD = $jwt->getPayload($token);
                $user = (array) $payloadBD;
                $agent->setToken($token);
                $agentID = $userAgent->id;
                $agentModel->update($agentID, $agent);
                logActivity($payload, TYPE_OP_LOGIN, STATUS_OP_OK, TABLE_AGENT);

                $message = "Agent s'est connecté avec succés";
                return datasuccess202($message, $token, $user);
            } else {
                return error401("Pas de connexion");
            }
        } else {
            error406("Adresse Email Invalide");
        }
    } else {
        $message = "Veuillez renseigner votre Email ou votre mot de passe";
        error422($message);
    }
}

function getUserAgentbyEmail($data)
{
    $email = $data;
    $login = array(
        "email" => $email,
    );
    $agentModel = new AgentsModel();
    $userAgent = (object)$agentModel->findBy($login);

    if (empty((array)$userAgent)) {
        error401("Email ou Mot de passe incorrect");
    } else {
        $agent = (array)$userAgent;
        $user = $agent[0];
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

    $agentModel = new AgentsModel();
    $userAgent = (object)$agentModel->find($id);

    $payloadBD = $jwt->getPayload($userAgent->token);
    $userAgent = (array) $payloadBD;

    if (($user['isOnline'] == $userAgent['isOnline'])) {
        # Agent est connecte
        $message = "Agent est connecté";
        return datasuccess200($message, $user);
    } else {
        error405("Agent s'est deja  deconnecté");
    }
}


function logout()
{
    require_once 'php-jwt/authentification.php';
    $payload = authentification();

    $user = (array)json_decode($payload);

    if (isset($user['id'])) {
        $id = $user['id'];

        $agentModel = new AgentsModel();
        $userAgent = (object)$agentModel->find($id);


        if (empty((array)$userAgent)) {
            # agent n'est pas connecte
            $message = "Agent est hors ligne";
            return error401($message);
        } else {
            # Deconnexion de l' agent 
            $agent = $agentModel;

            # Generation du Token
            require_once 'php-jwt/includes/config.php';
            require_once 'php-jwt/classes/JWT.php';

            # On crée le header
            $header = formatHeader();
            # On crée le contenu (payload)
            $payload = [
                'isOnline' => false,
                'id' => $userAgent->id,
                'role' => 'isAgent',
                'email' => $userAgent->email
            ];

            $jwt = new JWT();
            $tokenOff = $jwt->generate($header, $payload, SECRET, 0);

            $agent->setToken($tokenOff);
            $agentID = $userAgent->id;
            $agentModel->update($agentID, $agent);
            logActivity($payload, TYPE_OP_LOGOUT, STATUS_OP_OK, TABLE_AGENT);

            $message = "Agent s'est deconnecté";
            return success202($message);
        }
    } else {

        $message = "La session de l'Agent a deja expiré";
        return error401($message);
    }
}

function activeAgent($agentParams)
{

    $agentModel = new AgentsModel();
    $agent = $agentModel;

    paramsVerify($agentParams, "agent");
    $dataagent = $agentModel->find($agentParams['id']);
    $status = $dataagent->status;

    if (!empty($dataagent)) {
        switch ($status) {
            case '1': {
                    $message = "Le compte agent est deja activé";
                    return success205($message);
                }
            case '0': {
                    # Active...
                    $agent->setStatus(true);
                    $agentModel->update($agentParams['id'], $agent);
                    createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_AGENT);
                    $message = "Le compte agent est activé";
                    return success205($message);
                }

            default: {
                    $message = "Verifier le status de l'agent";
                    return success205($message);
                }
        }
    } else {
        $message = "agent not found";
        return success205($message);
    }
}
function desactiveAgent($agentParams)
{
    $agentModel = new AgentsModel();
    $agent = $agentModel;

    paramsVerify($agentParams, "agent");
    $dataagent = $agentModel->find($agentParams['id']);
    $status = $dataagent->status;

    if (!empty($dataagent)) {
        switch ($status) {
            case '0': {
                    $message = "Le compte agent est deja desactivé";
                    return success205($message);
                }
            case '1': {
                    # Active...
                    $agent->setStatus(false);
                    $agentModel->update($agentParams['id'], $agent);
                    createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_AGENT);
                    $message = "Le compte agent est désactivé";
                    return success205($message);
                }

            default: {
                    $message = "Verifier le status de l'agent";
                    return success205($message);
                }
        }
    } else {
        $message = "agent not found";
        return success205($message);
    }
}

function updatePassword($agentData, $agentParams)
{
    $agentModel = new AgentsModel();
    $agent = $agentModel;

    paramsVerify($agentParams, "Agent");

    #agent
    $newPassword = $agentData["new_password"];
    $oldPassword = $agentData["old_password"];

    $cNewpassword  = password_hash($newPassword, PASSWORD_BCRYPT, COST);
    $today = getSiku();

    #chargement client
    $agent->setPassword($cNewpassword);
    $agent->setUpdated_at($today);


    $agentID = $agentParams["id"];
    $agentFound = $agentModel->find($agentID);

    if (empty($agentFound)) {
        $message = "No agent Found ";
        return success205($message);
    } else {
        $test = ($agentID == $agentFound->id);

        if ($test) {
            if (password_verify($oldPassword, $agentFound->password)) {
                #Avant de Update on verifie si la table a subi de modification
                $agentModel->update($agentID, $agent);

                # On modifie l'Adresse et personne  dans la BD
                $message = "Mot de passe de l'agent updated successfully";
                createActivity(TYPE_OP_CHANGE_PWD, STATUS_OP_OK, TABLE_AGENT);
                return success200($message);
            } else {
                createActivity(TYPE_OP_CHANGE_PWD, STATUS_OP_NOT, TABLE_AGENT);
                $message = "Verifier l'ancien mot de passe";
                return success205($message);
            }
        } else {
            $message = "No Agent Found ";
            return success205($message);
        }
    }
}