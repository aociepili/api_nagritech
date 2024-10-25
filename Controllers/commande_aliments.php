<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('../Autoloader.php');
include('controllers.php');


use App\Autoloader;
use App\Models\Commande_alimentsModel;
use App\Models\Commande_clientsModel;

Autoloader::register();

# Store
function storeCommandeAliments($commandeAlimentsData)
{
    $commandeAlimentsModel = new Commande_alimentsModel();
    $commandeAliments = $commandeAlimentsModel;
    chargementCommande($commandeAlimentsData);
    $montant = $commandeAlimentsData["montant"];
    chiffreVerify($montant, "montant");
    # Le status est encours par defaut
    if ($commandeAlimentsData["montant"] == null) {
        $commandeAlimentsData["montant"] = 0;
    }

    $commandeAlimentsData["statusCmd_id"] = createStatutCommande($montant);
    //debug400("test", $commandeAlimentsData);


    // $date = $commandeAlimentsData["date"];
    $natureID = $commandeAlimentsData["natures_idNature"];
    $clientID = $commandeAlimentsData["clients_idClient"];
    $quantite = $commandeAlimentsData["quantite"];
    $montant = $commandeAlimentsData["montant"];
    // $prixtotal = $commandeAlimentsData["prixtotal"];
    natureVerify($natureID, DESIGN_ALIMENT);
    $today = getSiku();
    $commandeAlimentsData["date"] =  $today;


    $testClient = testClientbyId($clientID);
    $testNature = testNaturebyId($natureID);
    $testStatusCmd = testStatusCmdbyId($commandeAlimentsData["statusCmd_id"]);

    if ($testClient && $testNature && $testStatusCmd) {

        # Creer la Commande Client
        createCommandeClient($commandeAlimentsData);
        $cmdClientID = getLastCommandeClient($commandeAlimentsData)->id;

        if (empty($cmdClientID)) {
            return success205("Pas d'enregistrement Commande Client");
        } else {
            $prixtotal = getPrixTotal($cmdClientID, $quantite);
            $commandeAliments->setQuantite($quantite);
            $commandeAliments->setCommandeClients_idCommande($cmdClientID);
            $commandeAliments->setMontant($montant);
            $commandeAliments->setPrixtotal($prixtotal);
            $commandeAliments->setCreated_at($today);

            $commandeAlimentsModel->create($commandeAliments);
            createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_CMD_ALIMENT);
            $message = "Commande Aliment  created successfully";
            return success201($message);
        }
    }
}

#Delete
function deleteCommandeAliments($commandeAlimentsParams)
{
    $commandeAlimentsModel = new Commande_alimentsModel();
    paramsVerify($commandeAlimentsParams, "Commande Aliment");

    $commandeAlimentsID = $commandeAlimentsParams['id'];
    $commandeAlimentsData = $commandeAlimentsModel->find($commandeAlimentsID);

    if ($commandeAlimentsID == $commandeAlimentsData->id) {

        try {
            $commandeAlimentsModel->delete($commandeAlimentsID);
            $test = deleteCmdClientData($commandeAlimentsData->commandeClients_idCommande);
            if ($test) {
                $message = "Commande Aliment deleted successfully";
                createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_CMD_ALIMENT);
                return success200($message);
            }
        } catch (\Throwable $th) {
            $message = "Erreur Systeme :" . $th;
            createActivity(TYPE_OP_DELETE, STATUS_OP_NOT, TABLE_CMD_ALIMENT);
            return error405($message);
        }
    } else {
        $message = "Commande Aliment not Delete  ";
        return error405($message);
    }
}

#Get
function getCommandeAlimentById($commandeAlimentsParams)
{
    $commandeAlimentsModel = new Commande_alimentsModel();
    paramsVerify($commandeAlimentsParams, "Commande Aliment");
    $commandeAlimentsFound = $commandeAlimentsModel->find($commandeAlimentsParams['id']);

    if (!empty($commandeAlimentsFound)) {
        $dataCA = getCommandeAlimentDataById($commandeAlimentsFound->id);
        $message = "Commande Aliment Fetched successfully";
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_CMD_ALIMENT);
        return datasuccess200($message, $dataCA);
    } else {
        $message = "No commande Aliment Found";
        return success205($message);
    }
}

function getListCommandeAliment()
{
    $commandeAlimentsModel = new Commande_alimentsModel();
    $commandeAliments = (array)$commandeAlimentsModel->findAll();

    if (!empty($commandeAliments)) {
        $dataListCA = getListCommandeAlimentData($commandeAliments);
        $message = "Liste des Commandes Aliment";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_CMD_ALIMENT);
        return dataTableSuccess200($message, $dataListCA);
    } else {
        $message = "Pas de Commandes Aliment";
        return success205($message);
    }
}

# Update
function updateCommandeAliment($commandeAlimentsData, $commandeAlimentsParams)
{
    $commandeAlimentsModel = new Commande_alimentsModel();
    $commandeAliments = $commandeAlimentsModel;
    $commandeClientsModel = new Commande_clientsModel();
    $commandeClients = $commandeClientsModel;
    paramsVerify($commandeAlimentsParams, "Commande Aliment");

    $date = $commandeAlimentsData["date"];
    $natureID = $commandeAlimentsData["natures_idNature"];
    $clientID = $commandeAlimentsData["clients_idClient"];
    $commandeAlimentsID = $commandeAlimentsParams['id'];
    $quantite = $commandeAlimentsData["quantite"];

    $montant = $commandeAlimentsData["montant"];
    $today = getSiku();
    $commandeAlimentsData["siku"] = $today;

    $testClient = testClientbyId($clientID);
    $testNature = testNaturebyId($natureID);


    if ($testNature  && $testClient) {
        $dataCmdAliment = $commandeAlimentsModel->find($commandeAlimentsID);
        $cmdClientID = $dataCmdAliment->commandeClients_idCommande;

        $commandeClients->setDate($date);
        $commandeClients->setNatures_idNature($natureID);
        $commandeClients->setClients_idClient($clientID);
        $commandeClients->setUpdated_at($today);

        $prixtotal = getPrixTotal($cmdClientID, $quantite);
        $commandeAliments->setQuantite($quantite);
        $commandeAliments->setCommandeClients_idCommande($cmdClientID);
        $commandeAliments->setMontant($montant);
        $commandeAliments->setPrixtotal($prixtotal);
        $commandeAliments->setUpdated_at($today);

        $commandeAlimentsFound = $commandeAlimentsModel->find($commandeAlimentsID);
        if ($commandeAlimentsID == $commandeAlimentsFound->id) {
            if (in_array($commandeAlimentsFound->statusCmd_id, STATUS_CMD_IRREVERSIBLE)) {
                $message = "Le status de cette Commande Aliment ne peut etre modifie ";
                return  success205($message);
            } else {
                $commandeAlimentsModel->update($commandeAlimentsID, $commandeAliments);

                if (modCmdClient($commandeAlimentsData) or isset($today)) {
                    $commandeClientsModel->update($cmdClientID, $commandeClients);
                }
                $message = "Commande Aliment updated successfully";
                createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_CMD_ALIMENT);
                return success200($message);
            }
        } else {
            $message = "Commande Aliment not update";
            return success205($message);
        }
    }
}

function changeStatutCmdAliment($commandeAlimentsData, $commandeAlimentsParams)
{
    require_once 'php-jwt/authentification.php';
    $commandeAlimentsModel = new Commande_alimentsModel();
    $commandeAliments = $commandeAlimentsModel;
    $commandeClientsModel = new Commande_clientsModel();
    $commandeClients = $commandeClientsModel;
    # test de chargement de parametre
    paramsVerify($commandeAlimentsParams, "Commande Aliment");


    $payload = authentification();
    $user = (array)json_decode($payload);

    $auteurID = $user["id"];
    $role = $user["role"];

    if ($role == IS_ADMIN) {
        $commandeAlimentsData["admins_id"] = $auteurID;
        $commandeAlimentsData["admins_idAdmin"] = $auteurID;
        $commandeAlimentsData["agents_id"] = ID_AGENT_SYSTEME;
        $commandeAlimentsData["agents_idAgent"] = ID_AGENT_SYSTEME;
        $commandeAlimentsData["role_id"] = IS_ADMIN_ID;
    } elseif ($role == IS_AGENT) {
        $commandeAlimentsData["agents_id"] = $auteurID;
        $commandeAlimentsData["agents_idAgent"] = $auteurID;
        $commandeAlimentsData["admins_id"] = ID_ADMIN_SYSTEME;
        $commandeAlimentsData["admins_idAdmin"] = ID_ADMIN_SYSTEME;
        $commandeAlimentsData["role_id"] = IS_AGENT_ID;
    }


    $newStatusCmdID = $commandeAlimentsData["statusCmd_id"];
    $commandeAlimentsID = $commandeAlimentsParams['id'];

    statusCmdVerify($newStatusCmdID);
    // debug400('test', $newStatusCmdID);
    $testStatusCmd = testStatusCmdbyId($newStatusCmdID);

    if ($testStatusCmd) {
        $dataCmdAlimentFound = $commandeAlimentsModel->find($commandeAlimentsID);

        if ($dataCmdAlimentFound->id == $commandeAlimentsID) {

            $cmdClientID = $dataCmdAlimentFound->commandeClients_idCommande;
            $dataCmdClientFound = $commandeClientsModel->find($cmdClientID);

            if ($dataCmdClientFound->id == $cmdClientID) {

                $natureID = $dataCmdClientFound->natures_idNature;
                $clientID = $dataCmdClientFound->clients_idClient;
                $statusCmdID = $dataCmdClientFound->statusCmd_id;
                $sortieID = $dataCmdClientFound->id_sortie;

                $quantite = $dataCmdAlimentFound->quantite;
                $montant = $dataCmdAlimentFound->montant;
                $prixtotal = $dataCmdAlimentFound->prixtotal;

                $commandeAlimentsData["commandeClients_idCommande"] = $cmdClientID;
                $today = getSiku();
                $commandeAlimentsData["date"] = $today;
                $commandeAlimentsData["siku"] = $today;
                $commandeAlimentsData["etat_rapportID"] = ETAT_BON;
                $commandeAlimentsData["natures_idNature"] = $natureID;
                $commandeAlimentsData["quantite"] = $quantite;
                $commandeAlimentsData["montant"] = $montant;
                $commandeAlimentsData["prixtotal"] = $prixtotal;
                $commandeAlimentsData["clients_idClient"] = $clientID;
                $commandeAlimentsData["statusCmd_id"] = $statusCmdID;


                natureVerify($natureID, DESIGN_ALIMENT);
                statusCmdVerify($statusCmdID);
                chiffreVerify($montant, "Montant");
                chiffreVerify($prixtotal, "Prix Total");

                if ($statusCmdID == STATUS_CMD_ANNULE) {
                    #Commande Deja regle Pas d'operation 
                    $message = "Cette Commande Aliment du client a déjà été annulé";
                    return success200($message);
                } elseif ($statusCmdID == STATUS_CMD_E_DETTE) {
                    #Commande Deja regle Pas d'operation 
                    $message = "Cette Commande Aliment du client a déjà été reglé à credit";
                    return success200($message);
                } elseif ($statusCmdID == STATUS_CMD_REGLE) {
                    #Commande Deja regle Pas d'operation 
                    $message = "Cette Commande Aliment du client a déjà été reglé";
                    return success200($message);
                } else {
                    #Status Modifiable facilement i.e Pas d'operation
                    $statutSimpleMod = array(STATUS_CMD_ETABLI, STATUS_CMD_RESERVE, STATUS_CMD_E_PAIEMENT);
                    #Status Difficilement a Modifier i.e Beaucoup d'operation
                    $statusDifficileMod = array(STATUS_CMD_REGLE, STATUS_CMD_E_DETTE);

                    if (in_array($newStatusCmdID, $statutSimpleMod)) {
                        #Pas beaucoup d'operation juste le changement du status
                        $commandeClients->setStatusCmd_id($newStatusCmdID);
                        $commandeClients->setUpdated_at($today);
                        $commandeClientsModel->update($cmdClientID, $commandeClients);
                        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_CMD_ALIMENT);
                        $message = "Le Statut de Commande Aliment du client a été modifié";
                        return success200($message);
                    } elseif (in_array($newStatusCmdID, $statusDifficileMod)) {
                        if (($montant < $prixtotal) && ($newStatusCmdID == STATUS_CMD_E_DETTE)) {
                            #BON CLIENT : Le client a fait une commande est il a paye le total en avance
                            #Pas de dette
                            $commandeAlimentsData['motifSorties_idMotif'] = MOTIF_SORTIE_CREDIT;
                            sortieAliment($commandeAlimentsData);

                            $commandeClients->setStatusCmd_id($newStatusCmdID);
                            $commandeClients->setUpdated_at($today);

                            $sortieID = getLastSortie($commandeAlimentsData)->id;
                            $commandeClients->setId_sortie($sortieID);

                            $commandeClientsModel->update($cmdClientID, $commandeClients);
                            createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_CMD_ALIMENT);
                            $message = "Le Statut de Commande Aliment du client a été reglé avec une dette";
                            // debug400('test', $message);
                            return success200($message);
                        } elseif (($prixtotal == $montant) && ($newStatusCmdID == STATUS_CMD_REGLE)) {
                            #BON CLIENT : Le client a fait une commande est il a paye le total en avance
                            #Pas de dette
                            $commandeAlimentsData['motifSorties_idMotif'] = MOTIF_SORTIE_CASH;
                            sortieAliment($commandeAlimentsData);

                            $commandeClients->setStatusCmd_id($newStatusCmdID);
                            $commandeClients->setUpdated_at($today);

                            $sortieID = getLastSortie($commandeAlimentsData)->id;
                            $commandeClients->setId_sortie($sortieID);

                            $commandeClientsModel->update($cmdClientID, $commandeClients);
                            createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_CMD_ALIMENT);
                            $message = "Le Statut de Commande Aliment du client a été reglé";
                            // debug400('test', $message);
                            return success200($message);
                        } else {
                            $message = "Veuillez payer d'abord votre Commande ";
                            return success205($message);
                        }
                    } elseif ($newStatusCmdID == STATUS_CMD_ANNULE) {
                        #Pas beaucoup d'operation juste le changement du status
                        $commandeClients->setStatusCmd_id($newStatusCmdID);
                        $commandeClients->setUpdated_at($today);
                        $commandeClientsModel->update($cmdClientID, $commandeClients);
                        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_CMD_ALIMENT);
                        $message = "Le Statut de Commande Aliment du client a été annulée";
                        return success200($message);
                    } else {
                        $message = "Veuillez verifier le Status de la Commande Aliment";
                        return success205($message);
                    }
                }
            } else {
                $message = "Cette Commande  Aliment n'est repertorié comme une commande client ";
                return success205($message);
            }
        } else {
            $message = "Commande  Aliment not Found ";
            return success205($message);
        }
    }
}

function updateMontantAliment($commandeAlimentsData, $commandeAlimentsParams)
{
    $commandeAlimentsModel = new Commande_alimentsModel();
    $commandeAliments = $commandeAlimentsModel;
    $commandeClientsModel = new Commande_clientsModel();
    $commandeClients = $commandeClientsModel;

    paramsVerify($commandeAlimentsParams, "Commande Aliment");

    $newMontant = $commandeAlimentsData["montant"];
    chiffreVerify($newMontant, "montant");
    $commandeAlimentsID = $commandeAlimentsParams['id'];
    $today = getSiku();
    $commandeAlimentsData["siku"] = $today;
    $commandeAlimentsFound = $commandeAlimentsModel->find($commandeAlimentsID);


    if ($commandeAlimentsID == $commandeAlimentsFound->id) {
        $cmdClientFound = $commandeClientsModel->find($commandeAlimentsFound->commandeClients_idCommande);
        $cmdClientID = $cmdClientFound->id;
        if (in_array($cmdClientFound->statusCmd_id, STATUS_CMD_IRREVERSIBLE)) {
            $message = "Les informations de cette Commande Aliment ne peuvent etre modifie ";
            return  success205($message);
        } else {
            $oldMontant = $commandeAlimentsFound->montant;
            $prixTotal = $commandeAlimentsFound->prixtotal;

            if ($oldMontant < $prixTotal) {
                $montant = cumulMontant($newMontant, $oldMontant, $prixTotal);
                $commandeAliments->setMontant($montant);
                $commandeAliments->setUpdated_at($today);

                #Si le client paie la totalite de sa dette, on modifie le montant et le statut de sa cmde change REGLE 
                if ($montant == $prixTotal) {
                    // statutCmdAliment($commandeAlimentsID);
                    if ((in_array($cmdClientFound->statusCmd_id, STATUS_CMD_NO_STOCK_IMPACT))) {
                        $commandeClients->setStatusCmd_id(STATUS_CMD_REGLE);
                        $commandeClients->setUpdated_at($today);

                        $commandeClientsModel->update($cmdClientID, $commandeClients);
                        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_CMD_CLIENT);

                        $commandeAlimentsModel->update($commandeAlimentsID, $commandeAliments);

                        $message = "Le montant de la Commande Aliment updated successfully";
                        createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_CMD_ALIMENT);
                        return success200($message);
                    } else {
                        // $commandeClients->setStatusCmd_id(STATUS_CMD_REGLE);
                        $commandeClients->setUpdated_at($today);

                        $commandeClientsModel->update($cmdClientID, $commandeClients);
                        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_CMD_CLIENT);

                        $commandeAlimentsModel->update($commandeAlimentsID, $commandeAliments);

                        $message = "Le montant de la Commande Aliment updated successfully";
                        createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_CMD_ALIMENT);
                        return success200($message);
                    }
                } elseif (($montant < $prixTotal) && (in_array($cmdClientFound->statusCmd_id, STATUS_CMD_PAYABLE))) {

                    $somme = sommeMontant($oldMontant, $newMontant);
                    if (($somme >= $prixTotal) && (in_array($cmdClientFound->statusCmd_id, STATUS_CMD_NO_STOCK_IMPACT))) {
                        // statutCmdAliment($commandeAlimentsID);
                        $commandeClients->setStatusCmd_id(STATUS_CMD_REGLE);
                        $commandeClients->setUpdated_at($today);
                        $commandeClientsModel->update($cmdClientID, $commandeClients);
                        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_CMD_CLIENT);

                        $commandeAlimentsModel->update($commandeAlimentsID, $commandeAliments);
                        $message = "Le montant de la Commande Aliment updated successfully";
                        createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_CMD_ALIMENT);
                        return success200($message);
                    } else {
                        // $commandeClients->setStatusCmd_id(STATUS_CMD_REGLE);
                        $commandeClients->setUpdated_at($today);
                        $commandeClientsModel->update($cmdClientID, $commandeClients);
                        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_CMD_CLIENT);

                        $commandeAlimentsModel->update($commandeAlimentsID, $commandeAliments);
                        $message = "Le montant de la Commande Aliment updated successfully";
                        createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_CMD_ALIMENT);
                        return success200($message);
                    }
                } else {
                    $commandeClients->setStatusCmd_id(STATUS_CMD_RESERVE);
                    $commandeClients->setUpdated_at($today);
                    $commandeClientsModel->update($cmdClientID, $commandeClients);
                    createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_CMD_CLIENT);

                    $commandeAlimentsModel->update($commandeAlimentsID, $commandeAliments);
                    $message = "Le montant de la Commande Aliment updated successfully";
                    createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_CMD_ALIMENT);
                    return success200($message);
                }
            } elseif ($oldMontant == $prixTotal) {
                $message = "le montant de cette commande est deja soldé";
                return success205($message);
            } else {
                $message = "Veuillez verifier le montant de la commande";
                return success205($message);
            }
        }
    } else {
        $message = "Commande Aliment not update";
        return success205($message);
    }
}

function statutCmdAliment($cmdAlimentID)
{
    require_once 'php-jwt/authentification.php';
    $commandeAlimentsModel = new Commande_alimentsModel();
    $commandeAliments = $commandeAlimentsModel;
    $commandeClientsModel = new Commande_clientsModel();
    $commandeClients = $commandeClientsModel;
    # test de chargement de parametre
    // paramsVerify($commandeAlimentsParams, "Commande Aliment");

    $payload = authentification();
    $user = (array)json_decode($payload);

    $auteurID = $user["id"];
    $role = $user["role"];
    $commandeAlimentsData = array();

    if ($role == IS_ADMIN) {
        $commandeAlimentsData["admins_id"] = $auteurID;
        $commandeAlimentsData["admins_idAdmin"] = $auteurID;
        $commandeAlimentsData["agents_id"] = ID_AGENT_SYSTEME;
        $commandeAlimentsData["agents_idAgent"] = ID_AGENT_SYSTEME;
        $commandeAlimentsData["role_id"] = IS_ADMIN_ID;
    } elseif ($role == IS_AGENT) {
        $commandeAlimentsData["agents_id"] = $auteurID;
        $commandeAlimentsData["agents_idAgent"] = $auteurID;
        $commandeAlimentsData["admins_id"] = ID_ADMIN_SYSTEME;
        $commandeAlimentsData["admins_idAdmin"] = ID_ADMIN_SYSTEME;
        $commandeAlimentsData["role_id"] = IS_AGENT_ID;
    }

    $commandeAlimentsID = $cmdAlimentID;
    $dataCmdAlimentFound = $commandeAlimentsModel->find($commandeAlimentsID);

    $cmdClientID = $dataCmdAlimentFound->commandeClients_idCommande;
    $dataCmdClientFound = $commandeClientsModel->find($cmdClientID);

    $natureID = $dataCmdClientFound->natures_idNature;
    $clientID = $dataCmdClientFound->clients_idClient;
    $statusCmdID = $dataCmdClientFound->statusCmd_id;
    $sortieID = $dataCmdClientFound->id_sortie;

    $quantite = $dataCmdAlimentFound->quantite;
    $montant = $dataCmdAlimentFound->montant;
    $prixtotal = $dataCmdAlimentFound->prixtotal;

    $commandeAlimentsData["commandeClients_idCommande"] = $cmdClientID;
    $today = getSiku();
    $commandeAlimentsData["date"] = $today;
    $commandeAlimentsData["siku"] = $today;
    $commandeAlimentsData["etat_rapportID"] = ETAT_BON;
    $commandeAlimentsData["natures_idNature"] = $natureID;
    $commandeAlimentsData["quantite"] = $quantite;
    $commandeAlimentsData["montant"] = $montant;
    $commandeAlimentsData["prixtotal"] = $prixtotal;
    $commandeAlimentsData["clients_idClient"] = $clientID;
    $commandeAlimentsData["statusCmd_id"] = $statusCmdID;

    $commandeAlimentsData['motifSorties_idMotif'] = MOTIF_SORTIE_CASH;
    sortieAliment($commandeAlimentsData);

    // $commandeClients->setStatusCmd_id($newStatusCmdID);
    // $commandeClients->setUpdated_at($today);

    $sortieID = getLastSortie($commandeAlimentsData)->id;
    $commandeClients->setId_sortie($sortieID);
    $commandeClientsModel->update($cmdClientID, $commandeClients);
    // createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_CMD_ALIMENT);
    // $message = "Le Statut de Commande Aliment du client a été reglé";
    // return success200($message);
}
