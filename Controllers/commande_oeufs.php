<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('../Autoloader.php');
include('controllers.php');


use App\Autoloader;
use App\Models\Commande_oeufsModel;
use App\Models\Commande_clientsModel;

Autoloader::register();

# Store
function storeCommandeOeuf($commandeOeufsData)
{
    $commandeOeufsModel = new Commande_oeufsModel();
    $commandeOeufs = $commandeOeufsModel;
    chargementCommande($commandeOeufsData);

    $montant = $commandeOeufsData["montant"];
    chiffreVerify($montant, "montant");



    if ($commandeOeufsData["montant"] == null) {
        $commandeOeufsData["montant"] = 0;
    }
    $commandeOeufsData["statusCmd_id"] = createStatutCommande($montant);

    // $date = $commandeOeufsData["date"];
    $natureID = $commandeOeufsData["natures_idNature"];
    $clientID = $commandeOeufsData["clients_idClient"];
    $quantite = $commandeOeufsData["quantite"];
    $montant = $commandeOeufsData["montant"];
    // $prixtotal = $commandeOeufsData["prixtotal"];


    natureVerify($natureID, DESIGN_OEUF);
    chiffreVerify($quantite, "Quantite");
    $today = getSiku();
    $commandeOeufsData["date"] =  $today;


    $testClient = testClientbyId($clientID);
    $testNature = testNaturebyId($natureID);
    $testStatusCmd = testStatusCmdbyId($commandeOeufsData["statusCmd_id"]);
    if ($testClient && $testNature && $testStatusCmd) {

        # Creer la Commande Client
        createCommandeClient($commandeOeufsData);
        $cmdClientID = getLastCommandeClient($commandeOeufsData)->id;

        if (empty($cmdClientID)) {
            return success205("Pas d'enregistrement Commande Client");
        } else {
            $prixtotal = getPrixTotal($cmdClientID, $quantite);

            $commandeOeufs->setQuantite($quantite);
            $commandeOeufs->setCommandeClients_idCommande($cmdClientID);
            $commandeOeufs->setMontant($montant);
            $commandeOeufs->setPrixtotal($prixtotal);
            $commandeOeufs->setCreated_at($today);

            $commandeOeufsModel->create($commandeOeufs);
            createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_CMD_OEUF);
            $message = "Commande Oeufs  created successfully";
            return success201($message);
        }
    }
}

#Delete
function deleteCommandeOeuf($commandeOeufsParams)
{
    $commandeOeufsModel = new Commande_oeufsModel();
    paramsVerify($commandeOeufsParams, "Commande Oeuf");

    $commandeOeufsID = $commandeOeufsParams['id'];
    $commandeOeufsData = $commandeOeufsModel->find($commandeOeufsID);

    if ($commandeOeufsID == $commandeOeufsData->id) {
        try {
            $commandeOeufsModel->delete($commandeOeufsID);
            $test = deleteCmdClientData($commandeOeufsData->commandeClients_idCommande);
            if ($test) {
                createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_CMD_OEUF);
                $message = "Commande Oeuf deleted successfully";
                return success200($message);
            }
        } catch (\Throwable $th) {
            $message = "Erreur Systeme :" . $th;
            createActivity(TYPE_OP_DELETE, STATUS_OP_NOT, TABLE_CMD_OEUF);
            return error405($message);
        }
    } else {
        $message = "Commande Oeuf not Found  ";
        createActivity(TYPE_OP_DELETE, STATUS_OP_NOT, TABLE_CMD_OEUF);
        return error405($message);
    }
}

#Get
function getCommandeOeufById($commandeOeufsParams)
{
    $commandeOeufsModel = new Commande_oeufsModel();
    paramsVerify($commandeOeufsParams, "Commande Oeuf");
    $commandeOeufsFound = $commandeOeufsModel->find($commandeOeufsParams['id']);

    if (!empty($commandeOeufsFound)) {
        $datacmdOeuf = getCommandeOeufDataById($commandeOeufsFound->id);
        $message = "Commande Oeufs Fetched successfully";
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_CMD_OEUF);
        return datasuccess200($message, $datacmdOeuf);
    } else {
        $message = "No commande Oeufs Found";
        return success205($message);
    }
}

function getListCommandeOeuf()
{
    $commandeOeufsModel = new Commande_oeufsModel();
    $commandeOeufs = (array)$commandeOeufsModel->findAll();

    if (!empty($commandeOeufs)) {
        $dataListCO = getListCommandeOeufData($commandeOeufs);
        $message = "Liste des Commandes Oeufs";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_CMD_OEUF);
        return dataTableSuccess200($message, $dataListCO);
    } else {
        $message = "Pas de Commandes Oeufs";
        return success205($message);
    }
}

# Update
function updateCommandeOeuf($commandeOeufsData, $commandeOeufsParams)
{
    $commandeOeufsModel = new Commande_oeufsModel();
    $commandeOeufs = $commandeOeufsModel;

    $commandeClientsModel = new Commande_clientsModel();
    $commandeClients = $commandeClientsModel;
    paramsVerify($commandeOeufsParams, "Commande Oeuf");

    $date = $commandeOeufsData["date"];
    $natureID = $commandeOeufsData["natures_idNature"];
    $clientID = $commandeOeufsData["clients_idClient"];

    $quantite = $commandeOeufsData["quantite"];
    $montant = $commandeOeufsData["montant"];
    $today = getSiku();

    $commandeOeufsID = $commandeOeufsParams['id'];


    $testClient = testClientbyId($clientID);
    $testNature = testNaturebyId($natureID);



    if ($testNature  && $testClient) {
        $dataCmdOeuf = $commandeOeufsModel->find($commandeOeufsID);
        $cmdClientID = $dataCmdOeuf->commandeClients_idCommande;

        $commandeClients->setDate($date);
        $commandeClients->setNatures_idNature($natureID);
        $commandeClients->setClients_idClient($clientID);
        $commandeClients->setUpdated_at($today);

        $prixtotal = getPrixTotal($cmdClientID, $quantite);
        $commandeOeufs->setQuantite($quantite);
        $commandeOeufs->setCommandeClients_idCommande($cmdClientID);
        $commandeOeufs->setMontant($montant);
        $commandeOeufs->setPrixtotal($prixtotal);
        $commandeOeufs->setUpdated_at($today);

        $commandeOeufsFound = $commandeOeufsModel->find($commandeOeufsID);
        if ($commandeOeufsID == $commandeOeufsFound->id) {
            if (in_array($commandeOeufsFound->statusCmd_id, STATUS_CMD_IRREVERSIBLE)) {
                $message = "Le status de cette Commande Oeuf ne peut etre modifie ";
                return  success205($message);
            } else {
                $commandeOeufsModel->update($commandeOeufsID, $commandeOeufs);
                if (modCmdClient($commandeOeufsParams) or isset($today)) {
                    $commandeClientsModel->update($cmdClientID, $commandeClients);
                }

                $message = "Commande Oeufs updated successfully";
                createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_CMD_OEUF);
                return success200($message);
            }
        } else {
            $message = "No Commande Oeufs  Found ";
            createActivity(TYPE_OP_UPDATE, STATUS_OP_NOT, TABLE_CMD_OEUF);
            return success205($message);
        }
    }
}

function changeStatutCmdOeuf($commandeOeufsData, $commandeOeufsParams)
{
    require_once 'php-jwt/authentification.php';
    $commandeOeufsModel = new Commande_oeufsModel();
    $commandeOeufs = $commandeOeufsModel;
    $commandeClientsModel = new Commande_clientsModel();
    $commandeClients = $commandeClientsModel;
    paramsVerify($commandeOeufsParams, "Commande Oeuf");

    $payload = authentification();
    $user = (array)json_decode($payload);

    $auteurID = $user["id"];
    $role = $user["role"];

    if ($role == IS_ADMIN) {
        $commandeOeufsData["admins_id"] = $auteurID;
        $commandeOeufsData["admins_idAdmin"] = $auteurID;
        $commandeOeufsData["agents_id"] = ID_AGENT_SYSTEME;
        $commandeOeufsData["agents_idAgent"] = ID_AGENT_SYSTEME;
        $commandeOeufsData["role_id"] = IS_ADMIN_ID;
    } elseif ($role == IS_AGENT) {
        $commandeOeufsData["agents_id"] = $auteurID;
        $commandeOeufsData["agents_idAgent"] = $auteurID;
        $commandeOeufsData["admins_id"] = ID_ADMIN_SYSTEME;
        $commandeOeufsData["admins_idAdmin"] = ID_ADMIN_SYSTEME;
        $commandeOeufsData["role_id"] = IS_AGENT_ID;
    }

    $newStatusCmdID = $commandeOeufsData["statusCmd_id"];
    $commandeOeufsID = $commandeOeufsParams['id'];
    statusCmdVerify($newStatusCmdID);

    $testStatusCmd = testStatusCmdbyId($newStatusCmdID);


    if ($testStatusCmd) {
        $dataCmdOeufFound = $commandeOeufsModel->find($commandeOeufsID);
        if ($dataCmdOeufFound->id == $commandeOeufsID) {
            $cmdClientID = $dataCmdOeufFound->commandeClients_idCommande;
            $dataCmdClientFound = $commandeClientsModel->find($cmdClientID);
            if ($dataCmdClientFound->id == $cmdClientID) {
                $natureID = $dataCmdClientFound->natures_idNature;
                $clientID = $dataCmdClientFound->clients_idClient;
                $statusCmdID = $dataCmdClientFound->statusCmd_id;
                $sortieID = $dataCmdClientFound->id_sortie;

                $quantite = $dataCmdOeufFound->quantite;
                $montant = $dataCmdOeufFound->montant;
                $prixtotal = $dataCmdOeufFound->prixtotal;

                $commandeOeufsData["commandeClients_idCommande"] = $cmdClientID;
                $today = getSiku();
                $commandeOeufsData["date"] = $today;
                $commandeOeufsData["siku"] = $today;
                $commandeOeufsData["etat_rapportID"] = ETAT_BON;
                $commandeOeufsData["natures_idNature"] = $natureID;
                $commandeOeufsData["quantite"] = $quantite;
                $commandeOeufsData["montant"] = $montant;
                $commandeOeufsData["prixtotal"] = $prixtotal;
                $commandeOeufsData["clients_idClient"] = $clientID;
                $commandeOeufsData["statusCmd_id"] = $statusCmdID;


                natureVerify($natureID, DESIGN_OEUF);
                statusCmdVerify($statusCmdID);
                chiffreVerify($montant, "Montant");
                chiffreVerify($prixtotal, "Prix Total");


                if ($statusCmdID == STATUS_CMD_ANNULE) {
                    #Commande Deja regle Pas d'operation 
                    $message = "Cette Commande Oeuf du client a déjà été annulé";
                    return success200($message);
                } elseif ($statusCmdID == STATUS_CMD_E_DETTE) {
                    #Commande Deja regle Pas d'operation 
                    $message = "Cette Commande Oeuf du client a déjà été reglé à credit";
                    return success200($message);
                } elseif ($statusCmdID == STATUS_CMD_REGLE) {
                    #Commande Deja regle Pas d'operation 
                    $message = "Cette Commande Oeuf du client a déjà été reglé";
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
                        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_CMD_OEUF);
                        $message = "Le Statut de Commande Oeuf du client a été modifié";
                        return success200($message);
                    } elseif (in_array($newStatusCmdID, $statusDifficileMod)) {
                        if (($montant < $prixtotal) && ($newStatusCmdID == STATUS_CMD_E_DETTE)) {
                            #BON CLIENT : Le client a fait une commande est il a paye le total en avance
                            #Pas de dette
                            $commandeOeufsData['motifSorties_idMotif'] = MOTIF_SORTIE_CREDIT;
                            sortieOeuf($commandeOeufsData);
                            $commandeClients->setStatusCmd_id($newStatusCmdID);
                            $commandeClients->setUpdated_at($today);

                            $sortieID = getLastSortie($commandeOeufsData)->id;
                            $commandeClients->setId_sortie($sortieID);

                            $commandeClientsModel->update($cmdClientID, $commandeClients);
                            createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_CMD_OEUF);
                            $message = "Le Statut de Commande Oeuf du client a été reglé avec une dette";
                            return success200($message);
                        } elseif (($prixtotal == $montant) && ($newStatusCmdID == STATUS_CMD_REGLE)) {
                            #BON CLIENT : Le client a fait une commande est il a paye le total en avance
                            #Pas de dette
                            $commandeOeufsData['motifSorties_idMotif'] = MOTIF_SORTIE_CASH;
                            sortieOeuf($commandeOeufsData);
                            $commandeClients->setStatusCmd_id($newStatusCmdID);
                            $commandeClients->setUpdated_at($today);

                            $sortieID = getLastSortie($commandeOeufsData)->id;
                            $commandeClients->setId_sortie($sortieID);

                            $commandeClientsModel->update($cmdClientID, $commandeClients);
                            createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_CMD_OEUF);
                            $message = "Le Statut de Commande Oeuf du client a été reglé";
                            return success200($message);
                        } else {
                            $message = "Veuillez payer d'abord votre Commande ";
                            return success205($message);
                        }
                    } elseif ($newStatusCmdID == STATUS_CMD_ANNULE) {

                        $commandeClients->setStatusCmd_id($newStatusCmdID);
                        $commandeClients->setUpdated_at($today);
                        $commandeClientsModel->update($cmdClientID, $commandeClients);
                        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_CMD_OEUF);
                        $message = "Le Statut de Commande Oeuf du client  a été annulée";
                        return success200($message);
                    } else {
                        $message = "Veuillez verifier le Status de la Commande Oeuf";
                        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_NOT, TABLE_CMD_OEUF);
                        return success205($message);
                    }
                }
            } else {
                $message = "Cette Commande  Oeuf n'est repertorié comme une commande client ";
                createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_NOT, TABLE_CMD_OEUF);
                return success205($message);
            }
        } else {
            $message = "Commande  Oeuf not Found ";
            createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_NOT, TABLE_CMD_OEUF);
            return success205($message);
        }
    }
}

function updateMontantOeuf($commandeOeufsData, $commandeOeufsParams)
{
    $commandeOeufsModel = new Commande_oeufsModel();
    $commandeOeufs = $commandeOeufsModel;
    $commandeClientsModel = new Commande_clientsModel();
    $commandeClients = $commandeClientsModel;
    paramsVerify($commandeOeufsParams, "Commande Oeuf");

    $newMontant = $commandeOeufsData["montant"];
    chiffreVerify($newMontant, "montant");
    $commandeOeufID = $commandeOeufsParams['id'];
    $today = getSiku();
    $commandeOeufsData["siku"] = $today;

    $commandeOeufsFound = $commandeOeufsModel->find($commandeOeufID);
    if ($commandeOeufID == $commandeOeufsFound->id) {
        $cmdClientFound = $commandeClientsModel->find($commandeOeufsFound->commandeClients_idCommande);
        $cmdClientID = $cmdClientFound->id;
        if (in_array($cmdClientFound->statusCmd_id, STATUS_CMD_IRREVERSIBLE)) {
            $message = "Les informations de cette Commande Oeuf ne peuvent etre modifie ";
            return  success205($message);
        } else {
            $oldMontant = $commandeOeufsFound->montant;
            $prixTotal = $commandeOeufsFound->prixtotal;

            if ($oldMontant < $prixTotal) {
                $montant = cumulMontant($newMontant, $oldMontant, $prixTotal);
                $commandeOeufs->setMontant($montant);
                $commandeOeufs->setUpdated_at($today);

                #Si le client paie la totalite de sa dette, on modifie le montant et le statut de sa cmde change REGLE 
                if (($montant == $prixTotal)) {
                    if ((in_array($cmdClientFound->statusCmd_id, STATUS_CMD_NO_STOCK_IMPACT))) {
                        $commandeClients->setStatusCmd_id(STATUS_CMD_REGLE);
                        $commandeClients->setUpdated_at($today);

                        $commandeClientsModel->update($cmdClientID, $commandeClients);
                        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_CMD_CLIENT);
                        $commandeOeufsModel->update($commandeOeufID, $commandeOeufs);
                        $message = "Le montant de la Commande Oeuf updated successfully";
                        createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_CMD_OEUF);
                        return success200($message);
                    } else {
                        // $commandeClients->setStatusCmd_id(STATUS_CMD_REGLE);
                        $commandeClients->setUpdated_at($today);

                        $commandeClientsModel->update($cmdClientID, $commandeClients);
                        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_CMD_CLIENT);
                        $commandeOeufsModel->update($commandeOeufID, $commandeOeufs);
                        $message = "Le montant de la Commande Oeuf updated successfully";
                        createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_CMD_OEUF);
                        return success200($message);
                    }
                } elseif (($montant < $prixTotal) && (in_array($cmdClientFound->statusCmd_id, STATUS_CMD_PAYABLE))) {
                    $somme = sommeMontant($oldMontant, $newMontant);
                    if (($somme >= $prixTotal) && (in_array($cmdClientFound->statusCmd_id, STATUS_CMD_NO_STOCK_IMPACT))) {
                        $commandeClients->setStatusCmd_id(STATUS_CMD_REGLE);
                        $commandeClients->setUpdated_at($today);
                        $commandeClientsModel->update($cmdClientID, $commandeClients);
                        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_CMD_CLIENT);
                        $commandeOeufsModel->update($commandeOeufID, $commandeOeufs);
                        $message = "Le montant de la Commande Oeuf updated successfully";
                        createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_CMD_OEUF);
                        return success200($message);
                    } else {
                        // $commandeClients->setStatusCmd_id(STATUS_CMD_RESERVE);
                        $commandeClients->setUpdated_at($today);
                        $commandeClientsModel->update($cmdClientID, $commandeClients);
                        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_CMD_CLIENT);
                        $commandeOeufsModel->update($commandeOeufID, $commandeOeufs);
                        $message = "Le montant de la Commande Oeuf updated successfully";
                        createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_CMD_OEUF);
                        return success200($message);
                    }
                } else {
                    $commandeClients->setStatusCmd_id(STATUS_CMD_RESERVE);
                    $commandeClients->setUpdated_at($today);
                    $commandeClientsModel->update($cmdClientID, $commandeClients);
                    createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_CMD_CLIENT);
                    $commandeOeufsModel->update($commandeOeufID, $commandeOeufs);
                    $message = "Le montant de la Commande Oeuf updated successfully";
                    createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_CMD_OEUF);
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
        $message = "Commande Oeuf not update";
        return success205($message);
    }
}

function statutCmdOeuf($cmdOuefID)
{
    require_once 'php-jwt/authentification.php';
    $commandeOeufsModel = new Commande_oeufsModel();
    $commandeAliments = $commandeOeufsModel;
    $commandeClientsModel = new Commande_clientsModel();
    $commandeClients = $commandeClientsModel;
    # test de chargement de parametre
    // paramsVerify($commandeAlimentsParams, "Commande Aliment");

    $payload = authentification();
    $user = (array)json_decode($payload);

    $auteurID = $user["id"];
    $role = $user["role"];
    $cmdOeufsData = array();

    if ($role == IS_ADMIN) {
        $cmdOeufsData["admins_id"] = $auteurID;
        $cmdOeufsData["admins_idAdmin"] = $auteurID;
        $cmdOeufsData["agents_id"] = ID_AGENT_SYSTEME;
        $cmdOeufsData["agents_idAgent"] = ID_AGENT_SYSTEME;
        $cmdOeufsData["role_id"] = IS_ADMIN_ID;
    } elseif ($role == IS_AGENT) {
        $cmdOeufsData["agents_id"] = $auteurID;
        $cmdOeufsData["agents_idAgent"] = $auteurID;
        $cmdOeufsData["admins_id"] = ID_ADMIN_SYSTEME;
        $cmdOeufsData["admins_idAdmin"] = ID_ADMIN_SYSTEME;
        $cmdOeufsData["role_id"] = IS_AGENT_ID;
    }

    $commandeOeufsID = $cmdOuefID;
    $dataCmdOeufFound = $commandeOeufsModel->find($commandeOeufsID);

    $cmdClientID = $dataCmdOeufFound->commandeClients_idCommande;
    $dataCmdClientFound = $commandeClientsModel->find($cmdClientID);

    $natureID = $dataCmdClientFound->natures_idNature;
    $clientID = $dataCmdClientFound->clients_idClient;
    $statusCmdID = $dataCmdClientFound->statusCmd_id;
    $sortieID = $dataCmdClientFound->id_sortie;

    $quantite = $dataCmdOeufFound->quantite;
    $montant = $dataCmdOeufFound->montant;
    $prixtotal = $dataCmdOeufFound->prixtotal;

    $cmdOeufsData["commandeClients_idCommande"] = $cmdClientID;
    $today = getSiku();
    $cmdOeufsData["date"] = $today;
    $cmdOeufsData["siku"] = $today;
    $cmdOeufsData["etat_rapportID"] = ETAT_BON;
    $cmdOeufsData["natures_idNature"] = $natureID;
    $cmdOeufsData["quantite"] = $quantite;
    $cmdOeufsData["montant"] = $montant;
    $cmdOeufsData["prixtotal"] = $prixtotal;
    $cmdOeufsData["clients_idClient"] = $clientID;
    $cmdOeufsData["statusCmd_id"] = $statusCmdID;

    $cmdOeufsData['motifSorties_idMotif'] = MOTIF_SORTIE_CASH;
    sortieOeuf($cmdOeufsData);

    // $commandeClients->setStatusCmd_id($newStatusCmdID);
    // $commandeClients->setUpdated_at($today);

    $sortieID = getLastSortie($cmdOeufsData)->id;
    $commandeClients->setId_sortie($sortieID);
    $commandeClientsModel->update($cmdClientID, $commandeClients);
    // createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_CMD_ALIMENT);
    // $message = "Le Statut de Commande Aliment du client a été reglé";
    // return success200($message);
}
