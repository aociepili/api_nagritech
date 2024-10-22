<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('../Autoloader.php');
include('controllers.php');


use App\Autoloader;
use App\Models\AdminsModel;
use App\Models\ClientsModel;
use App\Models\AgentsModel;
use App\Models\PersonnesModel;
use App\Models\AdressesModel;

Autoloader::register();

# Store
function storeAdmin($adminData)
{

    #test de chargement 
    chargementAdmin($adminData);

    // $adminData["services_id"] = ADMIN_SERVICE;
    $adminData["categorieAdmins_idCategorie"] = CAT_ADMIN_GLOBAL;

    $categorieAdmins_idCategorie = $adminData["categorieAdmins_idCategorie"];
    $services_id = $adminData["services_id"];

    $testEmail = filter_var($adminData["email"], FILTER_VALIDATE_EMAIL);
    $testExist = CompareEmail($adminData["email"]);
    $testCatAdmin = testCatAdminbyId($categorieAdmins_idCategorie);
    $testService = testServicebyId($services_id);


    if ($testEmail  && $testExist && $testCatAdmin && $testService) {
        #Adresse Valide 
        #Creation de l'adresse
        createAdresse($adminData);
        #rechercher de l'ID de l'adresse creee
        $idAdresse = getLastAdresse($adminData)->id;

        if (empty($idAdresse)) {
            return success205("Pas d'enregistrement Adresse");
        } else {

            # Processus de Creation Personne
            createPersonne($adminData, $idAdresse);

            #rechercher de l'ID de la Personne creee
            $idPersonne = getLastPersonne($adminData, $idAdresse)->id;

            if (empty($idPersonne)) {
                return success205("Pas d'enregistrement Personne");
            } else {
                #Processus de Creation de l'administrateur
                createAdmin($adminData, $idPersonne);
                createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_ADMIN);
                $message = "Admin created successfully";
                return success201($message);
            }
        }
    } else {
        error406("Adresse Email Invalide");
    }
}


#Delete
function deleteAdmin($adminParams)
{
    $adminModel = new AdminsModel();

    paramsVerify($adminParams, "Admin");
    # On recupere les informations venues de POST

    $adminID = $adminParams['id'];

    $adminData = $adminModel->find($adminID);
    $personneID = $adminData->personnes_idPersonne;

    if ($adminID == $adminData->id) {
        $adminModel->delete($adminData->id);
        $test = deletePersonneData($personneID);

        if ($test) {
            createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_ADMIN);
            $message = "Admin deleted successfully";
            return success200($message);
        }
    } else {
        $message = "Admin not delete";
        return success205($message);
    }
}

#Get
function getAdminById($adminParams)
{
    $adminModel = new AdminsModel();

    paramsVerify($adminParams, "Admin");
    $dataAdmin = $adminModel->find($adminParams['id']);


    if (!empty($dataAdmin)) {
        $dataAdminAll = getAdminDataById($dataAdmin->id);
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_ADMIN);
        $message = "Admin Fetched successfully";
        return datasuccess200($message, $dataAdminAll);
    } else {
        $message = "Admin not found";
        return success205($message);
    }
}
#Get
function activeAdmin($adminParams)
{
    $adminModel = new AdminsModel();
    $admin = $adminModel;

    paramsVerify($adminParams, "Admin");
    $dataAdmin = $adminModel->find($adminParams['id']);
    $status = $dataAdmin->status;

    if (!empty($dataAdmin)) {
        switch ($status) {
            case '1': {
                    $message = "Le compte Admin est deja activé";
                    return success205($message);
                }
            case '0': {
                    # Active...
                    $admin->setStatus(true);
                    $adminModel->update($adminParams['id'], $admin);
                    createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_ADMIN);
                    $message = "Le compte Admin est activé";
                    return success205($message);
                }

            default: {
                    $message = "Verifier le status de l'Admin";
                    return success205($message);
                }
        }
    } else {
        $message = "Admin not found";
        return success205($message);
    }
}
function desactiveAdmin($adminParams)
{
    $adminModel = new AdminsModel();
    $admin = $adminModel;

    paramsVerify($adminParams, "Admin");
    $dataAdmin = $adminModel->find($adminParams['id']);
    $status = $dataAdmin->status;

    if (!empty($dataAdmin)) {
        switch ($status) {
            case '0': {
                    $message = "Le compte Admin est deja desactivé";
                    return success205($message);
                }
            case '1': {
                    # Active...
                    $admin->setStatus(false);
                    $adminModel->update($adminParams['id'], $admin);
                    createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_ADMIN);
                    $message = "Le compte Admin est désactivé";
                    return success205($message);
                }

            default: {
                    $message = "Verifier le status de l'Admin";
                    return success205($message);
                }
        }
    } else {
        $message = "Admin not found";
        return success205($message);
    }
}

function getListAdmin()
{

    $adminModel = new AdminsModel();

    $admins = (array)$adminModel->findAll();

    if (!empty($admins)) {
        $dataAdmin = getListAdminDataById($admins);
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_ADMIN);
        $message = "Liste des Administrateurs";

        return dataTableSuccess200($message, $dataAdmin);
    } else {
        $message = "Pas d'admin dans la BD";
        return success205($message);
    }
}


# Update
function updateAdmin($adminData, $adminParams)
{

    $adminModel = new AdminsModel();
    $adresseModel = new AdressesModel();
    $personneModel = new PersonnesModel();
    $adresse = $adresseModel;
    $personne = $personneModel;
    $admin = $adminModel;
    paramsVerify($adminParams, "Admin");

    $services_id = $adminData["services_id"];
    $testService = testServicebyId($services_id);
    $testEmail = filter_var($adminData["email"], FILTER_VALIDATE_EMAIL);
    $test = CompareEmailupdate($adminData['email'], $adminParams['id']);
    if ($testEmail && $test && $testService) {
        #Admin
        $email = $adminData["email"];
        $serivices_id = $adminData["services_id"];
        $telephone = $adminData["telephone"];
        $password = $adminData["password"];
        $cpassword  = password_hash($password, PASSWORD_BCRYPT, COST);
        $today = getSiku();

        #chargement Admin
        $admin->setEmail($email);
        $admin->setServices_id($serivices_id);
        $admin->setTelephone($telephone);
        $admin->setPassword($cpassword);
        $admin->setUpdated_at($today);

        #Personne
        $nom = $adminData["nom"];
        $postnom = $adminData["postnom"];
        $prenom = $adminData["prenom"];
        $sexe = $adminData["sexe"];
        $personne->setNom($nom);
        $personne->setPostnom($postnom);
        $personne->setPrenom($prenom);
        $personne->setSexe($sexe);

        #adresse
        $pays = $adminData["pays"];
        $ville = $adminData["ville"];
        $commune = $adminData["commune"];
        $quartier = $adminData["quartier"];
        $avenue = $adminData["avenue"];
        $adresse->setPays($pays);
        $adresse->setVille($ville);
        $adresse->setCommune($commune);
        $adresse->setQuartier($quartier);
        $adresse->setAvenue($avenue);

        $adminID = $adminParams["id"];
        $AdminFound = $adminModel->find($adminID);


        if (empty($AdminFound)) {
            $message = "No Admin found";
            return error500($message);
        } else {
            #Personne ID
            $personneID = $AdminFound->personnes_idPersonne;

            #Adresse ID
            $personData = $personneModel->find($personneID);
            $adresseID = $personData->adresses_idAdresse;

            if ($adminID == $AdminFound->id) {
                #Avant de Update on verifie si la table a subi de modification
                if (modAdmin($adminData) or isset($today)) {
                    $adminModel->update($adminID, $admin);
                }
                if (modAdresse($adminData)) {
                    $adresseModel->update($adresseID, $adresse);
                }
                if (modPersonne($adminData)) {
                    $personneModel->update($personneID, $personne);
                }

                # On modifie l'Adresse et personne  dans la BD
                createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_ADMIN);
                $message = "Admin updated successfully";
                return success200($message);
            } else {
                $message = "Admin not  Update ";
                return success205($message);
            }
        }
    } else {
        error406("Adresse Email Invalide");
    }
}

function createAdmin($adminData, $idPersonne)
{

    $adminModel = new AdminsModel();
    $admin = $adminModel;
    #Admin
    $email = $adminData["email"];
    $telephone = $adminData["telephone"];
    $password = $adminData["password"];
    $cpassword  = password_hash($password, PASSWORD_BCRYPT, COST);
    $status = "1";
    $token = "OFF";
    $personnes_idPersonne = $idPersonne;
    $categorieAdmins_idCategorie = $adminData["categorieAdmins_idCategorie"];
    $services_id = $adminData["services_id"];
    $today = getSiku();

    #chargement Admin
    $admin->setEmail($email);
    $admin->setTelephone($telephone);
    $admin->setPassword($cpassword);
    $admin->setStatus($status);
    $admin->setToken($token);
    $admin->setPersonnes_idPersonne($personnes_idPersonne);
    $admin->setcategorieAdmins_idCategorie($categorieAdmins_idCategorie);
    $admin->setServices_id($services_id);
    $admin->setCreated_at($today);

    $adminModel->create($admin);
}

function login($dataAdmin)
{
    #Test validite de l'Email
    $test = filter_var($dataAdmin["email"], FILTER_VALIDATE_EMAIL);

    if (isset($dataAdmin['email']) and isset($dataAdmin['password'])) {
        if ($test) {
            $email = $dataAdmin['email'];
            $pwd = $dataAdmin['password'];
            $userAdmin = getUserAdminbyEmail($email);

            if (password_verify($pwd, $userAdmin->password)) {
                $adminModel = new AdminsModel();
                $admin = $adminModel;


                # Generation du Token
                require_once 'php-jwt/includes/config.php';
                require_once 'php-jwt/classes/JWT.php';

                // On crée le header
                $header = formatHeader();
                $typeService = getDesignationTypeServiceById($userAdmin->services_id);
                // On crée le contenu (payload)
                $payload = [
                    'isOnline' => true,
                    'id' => $userAdmin->id,
                    'role' => 'isAdmin',
                    'type' => $typeService,
                    'email' => $userAdmin->email
                ];

                $jwt = new JWT();
                $token = $jwt->generate($header, $payload, SECRET, TOKEN_VALIDITE);

                $payloadBD = $jwt->getPayload($token);
                $user = (array) $payloadBD;

                $admin->setToken($token);
                $adminID = $userAdmin->id;
                $adminModel->update($adminID, $admin);
                logActivity($payload, TYPE_OP_LOGIN, STATUS_OP_OK, TABLE_ADMIN);

                $message = "Administrateur s'est connecté avec succés";
                return datasuccess202($message, $token, $user);
            } else {
                return error401("Adresse Email ou mot de passe incorrect");
            }
        } else {
            error406("Adresse Email Invalide");
        }
    } else {
        $message = "Veuillez renseigner votre Email ou votre mot de passe";
        error422($message);
    }
}

function getUserAdminbyEmail($data)
{
    $email = $data;
    $login = array(
        "email" => $email,
    );
    $adminModel = new AdminsModel();
    $userAdmin = (object)$adminModel->findBy($login);

    if (empty((array)$userAdmin)) {
        error401("Email ou Mot de passe incorrect");
    } else {
        $admin = (array)$userAdmin;
        $user = $admin[0];
        return $user;
    }
}
function CompareEmail($data)
{

    $test = false;
    $email = array(
        "email" => $data,
    );

    $adminModel = new AdminsModel();
    $userAdmin = (object)$adminModel->findBy($email);

    if (empty((array)$userAdmin)) {
        # Admin n'existe pas
        $test = true;
        return $test;
    } else {
        error401(" Cette adresse mail existe deja");
    }
}
function CompareEmailupdate($data, $id)
{

    $test = false;
    $email = array(
        "email" => $data,
    );

    $adminModel = new AdminsModel();
    $userAdmin = $adminModel->findBy($email);
    if (empty((array)$userAdmin)) {
        # Admin n'existe pas
        $test = true;
        return $test;
    } else {
        if ($id == $userAdmin[0]->id) {
            $test = true;
            return $test;
        } else {
            error401(" Cette adresse mail existe deja");
        }
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

    $adminModel = new AdminsModel();
    $userAdmin = (object)$adminModel->find($id);

    $payloadBD = $jwt->getPayload($userAdmin->token);
    $userAdmin = (array) $payloadBD;

    if (($user['isOnline'] == $userAdmin['isOnline'])) {
        # Admin est connecte
        $message = "Admin est connecté";
        return datasuccess200($message, $user);
    } else {
        error405("Admin s'est deja  deconnecté");
    }
}

function logout()
{
    require_once 'php-jwt/authentification.php';
    $payload = authentification();

    $user = (array)json_decode($payload);


    if (isset($user['id'])) {
        $id = $user['id'];

        $adminModel = new AdminsModel();
        $userAdmin = (object)$adminModel->find($id);

        if (empty((array)$userAdmin)) {
            # Admin n'est pas connecte
            $message = "Admin n'est pas en ligne";
            return error401($message);
        } else {
            # Deconnexion de l' Admin 
            $admin = $adminModel;

            # Generation du Token
            require_once 'php-jwt/includes/config.php';
            require_once 'php-jwt/classes/JWT.php';

            # On crée le header
            $header = formatHeader();
            # On crée le contenu (payload)
            $payload = [
                'isOnline' => false,
                'id' => $userAdmin->id,
                'role' => 'isAdmin',
                'email' => $userAdmin->email
            ];
            $jwt = new JWT();
            $tokenOff = $jwt->generate($header, $payload, SECRET, 0);

            logActivity($payload, TYPE_OP_LOGOUT, STATUS_OP_OK, TABLE_ADMIN);

            // $tokenOff = "OFF";
            $admin->setToken($tokenOff);
            $adminID = $userAdmin->id;
            $adminModel->update($adminID, $admin);


            $message = "Admin s'est deconnecté";
            return success202($message);
        }
    } else {
        $message = "La session de l'Admin a deja expiré";
        return error401($message);
    }
}

function resetPasswordClient($clientParams)
{
    $clientModel = new ClientsModel();
    $client = $clientModel;

    paramsVerify($clientParams, "Client");
    // debug400("Test","ca marche");

    #client
    // $newPassword = $clientData["new_password"];
    $newPassword = DEFAULT_PWD;
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
            #Avant de Update on verifie si la table a subi de modification
            $clientModel->update($clientID, $client);
            createActivity(TYPE_OP_RESET_PWD, STATUS_OP_OK, TABLE_CLIENT);

            # On modifie l'Adresse et personne  dans la BD
            $message = "Mot de passe du client a été réinitialisé avec succées";
            return success200($message);
        } else {
            $message = "No client Found ";
            return success205($message);
        }
    }
}
function resetPasswordAgent($agentParams)
{

    $agentModel = new AgentsModel();
    $agent = $agentModel;

    paramsVerify($agentParams, "agent");

    #Agent
    // $newPassword = $agentData["new_password"];
    $newPassword = DEFAULT_PWD;
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
            #Avant de Update on verifie si la table a subi de modification
            $agentModel->update($agentID, $agent);
            createActivity(TYPE_OP_RESET_PWD, STATUS_OP_OK, TABLE_AGENT);

            # On modifie l'Adresse et personne  dans la BD
            $message = "Mot de passe de l'agent a été réinitialisé avec succées";
            return success200($message);
        } else {
            $message = "No agent Found ";
            return success205($message);
        }
    }
}

function updatePassword($adminData, $adminParams)
{
    $adminModel = new AdminsModel();
    $admin = $adminModel;
    paramsVerify($adminParams, "Admin");

    #Admin
    $password = $adminData["password"];
    $cpassword  = password_hash($password, PASSWORD_BCRYPT, COST);
    $today = getSiku();

    #chargement Admin
    $admin->setPassword($cpassword);
    $admin->setUpdated_at($today);

    $adminID = $adminParams["id"];
    $AdminFound = $adminModel->find($adminID);



    if (empty($AdminFound)) {
        $message = "No Admin found";
        return error500($message);
    } else {

        if ($adminID == $AdminFound->id) {
            #Avant de Update on verifie si la table a subi de modification
            if (modAdmin($adminData) or isset($today)) {
                $adminModel->update($adminID, $admin);
            }

            # On modifie l'Adresse et personne  dans la BD
            createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_ADMIN);
            $message = "Admin Password updated successfully";
            return success200($message);
        } else {
            $message = "Admin Password not  Update ";
            return success205($message);
        }
    }
}