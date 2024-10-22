<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('../Autoloader.php');
include('controllers.php');


use App\Autoloader;
use App\Models\FournisseursModel;
use App\Models\PersonnesModel;
use App\Models\AdressesModel;

Autoloader::register();
# Store
function storeFournisseur($fournisseurData)
{
    #test de chargement 
    chargementFournisseur($fournisseurData);
    #Test validite de l'Email
    $isValidmail = isValidEmail($fournisseurData["email"]);
    $fournisseurData["services_id"] = FOUR_SERVICE;
    $catProduitID = $fournisseurData["cat_produit_id"];
    $testCatProduit = testCatProduitbyId($catProduitID);
    if ($isValidmail &&   $testCatProduit) {

        #Adresse Valide 
        #Creation de l'adresse
        createAdresse($fournisseurData);
        #rechercher de l'ID de l'adresse creee
        $idAdresse = getLastAdresse($fournisseurData)->id;

        if (empty($idAdresse)) {
            return success205("Pas d'enregistrement Adresse");
        } else {
            # Processus de Creation Personne
            createPersonne($fournisseurData, $idAdresse);
            #rechercher de l'ID de la Personne creee
            $idPersonne = getLastPersonne($fournisseurData, $idAdresse)->id;

            if (empty($idPersonne)) {
                return success205("Pas d'enregistrement Personne");
            } else {
                #Processus de Creation de l'administrateur
                createFournisseur($fournisseurData, $idPersonne);
                createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_FOURNISSEUR);
                $message = "fournisseur created successfully";
                return success201($message);
            }
        }
    } else {
        error406("Adresse Email Invalide");
    }
}

function storeFournisseurMoral($fournisseurData)
{
    #test de chargement 
    chargementFournisseurMoral($fournisseurData);


    $fournisseurData["services_id"] = FOUR_SERVICE;
    $catProduitID = $fournisseurData["cat_produit_id"];
    // $fournisseurData["email"] = $fournisseurData["nom"] . "@nagritech.com";
    $testService = testServicebyId(FOUR_SERVICE);


    #Test validite de l'Email
    $testEmail = filter_var($fournisseurData["email"], FILTER_VALIDATE_EMAIL);

    $testEmailExist = CompareEmailFournisseur($fournisseurData["email"]);

    #Test sur telephone
    $testTelephone = isValidTelephone($fournisseurData["telephone"]);
    $testTelephoneExist = isExistTelephoneFour($fournisseurData["telephone"]);
    // debug400('create', "Founrisseur marche");


    if (($testTelephone)) {
        if ($testTelephoneExist && $testService  &&  $testEmail && $testEmailExist) {
            #Adresse Valide 
            #Creation de l'adresse
            createAdresse($fournisseurData);
            #rechercher de l'ID de l'adresse creee
            $idAdresse = getLastAdresse($fournisseurData)->id;


            if (empty($idAdresse)) {
                return success205("Pas d'enregistrement A");
            } else {

                # Processus de Creation Personne
                createPersonneMorale($fournisseurData, $idAdresse);
                #rechercher de l'ID de la Personne creee
                $idPersonne = getLastPersonneMorale($fournisseurData, $idAdresse)->id;

                if (empty($idPersonne)) {
                    return success205("Pas d'enregistrement P");
                } else {

                    #Processus de Creation de l'administrateur

                    createFournisseurMoral($fournisseurData, $idPersonne);
                    createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_FOURNISSEUR);
                    $message = "Fournisseur created successfully";
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
function deleteFournisseur($fournisseurParams)
{
    $fournisseurModel = new FournisseursModel();
    paramsVerify($fournisseurParams, "Fournisseur");
    # On recupere les informations venues de POST

    $fournisseurID = $fournisseurParams['id'];

    $fournisseurFoundData = $fournisseurModel->find($fournisseurID);
    $personneID = $fournisseurFoundData->personnes_idPersonne;

    if ($fournisseurID == $fournisseurFoundData->id) {
        $fournisseurModel->delete($fournisseurFoundData->id);
        $test = deletePersonneData($personneID);

        if ($test) {
            createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_FOURNISSEUR);
            $message = "Fournisseur deleted successfully";
            return success200($message);
        }
    } else {
        createActivity(TYPE_OP_DELETE, STATUS_OP_NOT, TABLE_FOURNISSEUR);
        $message = "Fournisseur not delete ";
        return error405($message);
    }
}

#Get
function getFournisseurById($fournisseurParams)
{
    $fournisseurModel = new FournisseursModel();

    paramsVerify($fournisseurParams, "Fournisseur");
    $dataFoundClient = $fournisseurModel->find($fournisseurParams['id']);


    if (!empty($dataFoundClient)) {
        $dataClientAll = getFournisseurDataById($dataFoundClient->id);
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_FOURNISSEUR);
        $message = "fournisseur Fetched successfully";
        return datasuccess200($message, $dataClientAll);
    } else {
        $message = "No fournisseur Found";
        return success205($message);
    }
}

function getListFournisseur()
{
    $fournisseurModel = new FournisseursModel();

    $fournisseurs = (array)$fournisseurModel->findAll();

    if (!empty($fournisseurs)) {
        $dataFournisseur = getListFournisseurDataById($fournisseurs);
        $message = "Liste des fournisseurs";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_FOURNISSEUR);
        return dataTableSuccess200($message, $dataFournisseur);
    } else {
        $message = "Pas de fournisseur dans la BD";
        return success205($message);
    }
}


# Update
function updateFournisseur($fournisseurData, $fournisseurParams)
{
    $fournisseurModel = new FournisseursModel();
    $adresseModel = new AdressesModel();
    $personneModel = new PersonnesModel();
    $adresse = $adresseModel;
    $personne = $personneModel;
    $fournisseur = $fournisseurModel;

    paramsVerify($fournisseurParams, "Fournisseur");

    #fournisseur
    $email = $fournisseurData["email"];
    $telephone = $fournisseurData["telephone"];
    $logo = $fournisseurData["logo"];
    $today = getSiku();
    $catProduitID = $fournisseurData["cat_produit_id"];
    $testCatProduit = testCatProduitbyId($catProduitID);

    #chargement fournisseur
    $fournisseur->setEmail($email);
    $fournisseur->setTelephone($telephone);
    $fournisseur->setCatProduit($catProduitID);

    $fournisseur->setUpdated_at($today);

    #Personne
    $nom = $fournisseurData["nom"];
    $postnom = $fournisseurData["postnom"];
    $prenom = $fournisseurData["prenom"];
    $sexe = $fournisseurData["sexe"];
    $personne->setNom($nom);
    $personne->setPostnom($postnom);
    $personne->setPrenom($prenom);
    $personne->setSexe($sexe);

    #adresse
    $pays = $fournisseurData["pays"];
    $ville = $fournisseurData["ville"];
    $commune = $fournisseurData["commune"];
    $quartier = $fournisseurData["quartier"];
    $avenue = $fournisseurData["avenue"];
    $adresse->setPays($pays);
    $adresse->setVille($ville);
    $adresse->setCommune($commune);
    $adresse->setQuartier($quartier);
    $adresse->setAvenue($avenue);
    if ($testCatProduit) {
        $fournisseurID = $fournisseurParams["id"];
        $fournisseurFound = $fournisseurModel->find($fournisseurID);

        if (empty($fournisseurFound)) {
            $message = "No fournisseur Found ";
            return success205($message);
        } else {
            #Personne ID
            $personneID = $fournisseurFound->personnes_idPersonne;

            #Adresse ID
            $personData = $personneModel->find($personneID);
            $adresseID = $personData->adresses_idAdresse;

            $test = ($fournisseurID == $fournisseurFound->id);

            if ($test) {
                #Avant de Update on verifie si la table a subi de modification
                if (modFournisseur($fournisseurData)) {
                    $fournisseurModel->update($fournisseurID, $fournisseur);
                }
                if (modAdresse($fournisseurData)) {
                    $adresseModel->update($adresseID, $adresse);
                }
                if (modPersonne($fournisseurData)) {
                    $personneModel->update($personneID, $personne);
                }

                # On modifie l'Adresse et personne  dans la BD
                $message = "fournisseur updated successfully";
                createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_FOURNISSEUR);
                return success200($message);
            } else {
                $message = "No fournisseur Found";
                return success205($message);
            }
        }
    }
}

function chargerLogo($imgData, $fournisseurParams)
{
    $fournisseurModel = new FournisseursModel();
    $fournisseur = $fournisseurModel;
    paramsVerify($fournisseurParams, "Fournisseur");
    $fournisseurID = $fournisseurParams["id"];
    $fournisseurFound = $fournisseurModel->find($fournisseurID);


    if (empty($fournisseurFound)) {
        $message = "No fournisseur Found or Internal Server Error";
        createActivity(TYPE_OP_UP_IMAGE, STATUS_OP_NOT, TABLE_FOURNISSEUR);
        return success205($message);
    } else {
        #Image
        $nbre = generateNumber();

        #Nouvelle denomination du fichier
        $title = "Logo" . $nbre;

        # Traitement de donnees du fichier
        $titreImg = $imgData['name'];
        $type = $imgData['type'];

        $directory = $imgData['tmp_name'];
        $extension = strrchr($titreImg, ".");
        $directorysend = "../public/img/fournisseur/" . $title . "" . $extension;
        #Extension prise en charge
        $valideExtension = array('.jpg', '.png', '.jpeg');

        $test = in_array($extension, $valideExtension);

        if ($test) {

            if (move_uploaded_file($directory, $directorysend)) {
                # Nouveau chemin d'acces a l image de profil
                $pathLogo = "../public/img/fournisseur/" . $title . "" . $extension;
                $fournisseur->setLogo($pathLogo);
                $fournisseurModel->update($fournisseurID, $fournisseur);
                createActivity(TYPE_OP_UP_IMAGE, STATUS_OP_OK, TABLE_FOURNISSEUR);

                $message = "Le logo a été chargé  avec succès";
                return success200($message);
            } else {

                $message = "Un Problème de télechargement du fichier";
                createActivity(TYPE_OP_UP_IMAGE, STATUS_OP_NOT, TABLE_FOURNISSEUR);
                success205($message);
            }
        } else {
            $message = "Le fichier charge n'est pas prise en charge";
            createActivity(TYPE_OP_UP_IMAGE, STATUS_OP_NOT, TABLE_FOURNISSEUR);
            success205($message);
        }
    }
}

function createFournisseur($fournisseurData, $idPersonne)
{
    $fournisseurModel = new FournisseursModel();
    $fournisseur = $fournisseurModel;
    #fournisseur
    $email = $fournisseurData["email"];
    $telephone = $fournisseurData["telephone"];
    // $logo = PATH_IMG_DEFAUT;
    $personnes_idPersonne = $idPersonne;
    $services_id = $fournisseurData["services_id"];
    $catProduitID = $fournisseurData["cat_produit_id"];
    $today = getSiku();

    #chargement fournisseur
    $fournisseur->setEmail($email);
    $fournisseur->setCatProduit($catProduitID);
    $fournisseur->setTelephone($telephone);
    $fournisseur->setLogo(PATH_IMG_DEFAUT);
    $fournisseur->setPersonnes_idPersonne($personnes_idPersonne);
    $fournisseur->setServices_id($services_id);
    $fournisseur->setCreated_at($today);

    $fournisseurModel->create($fournisseur);
}

function createFournisseurMoral($fournisseurData, $idPersonne)
{
    $fournisseurModel = new FournisseursModel();
    $fournisseur = $fournisseurModel;
    #fournisseur
    $email = $fournisseurData["email"];
    $telephone = $fournisseurData["telephone"];
    $catProduitID = $fournisseurData["cat_produit_id"];
    // $password = $fournisseurData["password"];
    // $cpassword  = password_hash($password, PASSWORD_BCRYPT, COST);
    $status = true;
    // $token = "OFF";
    $personnes_idPersonne = $idPersonne;
    $services_id = $fournisseurData["services_id"];
    $tranche_age_id = 0;
    $is_legal_person = true;
    $today = getSiku();


    #chargement fournisseur
    $fournisseur->setEmail($email);
    $fournisseur->setTrancheAgeId($tranche_age_id);
    $fournisseur->setTelephone($telephone);
    $fournisseur->setCatProduit($catProduitID);
    $fournisseur->setStatus($status);
    // $fournisseur->setToken($token);
    $fournisseur->setPersonnes_idPersonne($personnes_idPersonne);
    $fournisseur->setServices_id($services_id);
    $fournisseur->setIs_legal_person($is_legal_person);
    $fournisseur->setCreated_at($today);
    $fournisseurModel->create($fournisseur);
}

function CompareEmailFournisseur($data)
{

    $test = false;
    $email = array(
        "email" => $data,
    );

    $fournisseurModel = new FournisseursModel();
    $userFournisseur = (object)$fournisseurModel->findBy($email);

    if (empty((array)$userFournisseur)) {
        # fournisseur n'existe pas
        $test = true;
        return $test;
    } else {
        success203(" Cette adresse mail existe deja");
    }
}
function isExistTelephoneFour($telephone)
{
    $test = false;
    $dataTelephone = array(
        "telephone" => $telephone,
    );

    $fournisseurModel = new FournisseursModel();
    $userClient = (object)$fournisseurModel->findBy($dataTelephone);

    if (empty((array)$userClient)) {
        # Le numero de telephone n'existe pas
        $test = true;
        return $test;
    } else {
        success203(" Ce numero de telephone existe deja");
    }
}

function updateFournisseurMoral($fournisseurData, $fournisseurParams)
{
    $fournisseurModel = new FournisseursModel();
    $adresseModel = new AdressesModel();
    $personneModel = new PersonnesModel();
    $adresse = $adresseModel;
    $personne = $personneModel;
    $fournisseur = $fournisseurModel;

    paramsVerify($fournisseurParams, "Fournisseur");

    #fournisseur
    $email = $fournisseurData["email"];
    $telephone = $fournisseurData["telephone"];
    $catProduitId = $fournisseurData["cat_produit_id"];
    // $password = $fournisseurData["password"];
    // $cpassword  = password_hash($password, PASSWORD_BCRYPT, COST);
    $today = getSiku();

    #chargement fournisseur
    $fournisseur->setEmail($email);
    $fournisseur->setTelephone($telephone);
    $fournisseur->setCatProduit($catProduitId);
    $fournisseur->setUpdated_at($today);

    #Personne
    $nom_entreprise = $fournisseurData["nom_entreprise"];
    $nom = $fournisseurData["nom"];
    $titre = $fournisseurData["titre"];
    $annee_existence = $fournisseurData["annee_existence"];

    $sexe = $fournisseurData["sexe"];
    $personne->setNom_entreprise($nom_entreprise);
    $personne->setNom($nom);
    $personne->setTitre($titre);
    $personne->setAnnee_existence($annee_existence);
    $personne->setSexe($sexe);

    #adresse
    $pays = $fournisseurData["pays"];
    $ville = $fournisseurData["ville"];
    $commune = $fournisseurData["commune"];
    $quartier = $fournisseurData["quartier"];
    $avenue = $fournisseurData["avenue"];
    $adresse->setPays($pays);
    $adresse->setVille($ville);
    $adresse->setCommune($commune);
    $adresse->setQuartier($quartier);
    $adresse->setAvenue($avenue);

    $fournisseurID = $fournisseurParams["id"];
    $fournisseurFound = $fournisseurModel->find($fournisseurID);

    if (empty($fournisseurFound)) {
        $message = "No fournisseur Found ";
        return success205($message);
    } else {
        #Personne ID
        $personneID = $fournisseurFound->personnes_idPersonne;

        #Adresse ID
        $personData = $personneModel->find($personneID);
        $adresseID = $personData->adresses_idAdresse;

        $test = ($fournisseurID == $fournisseurFound->id);

        if ($test) {
            #Avant de Update on verifie si la table a subi de modification
            if (modFournisseur($fournisseurData) and isset($today)) {
                $fournisseurModel->update($fournisseurID, $fournisseur);
            }
            if (modAdresse($fournisseurData)) {
                $adresseModel->update($adresseID, $adresse);
            }
            if (modPersonne($fournisseurData)) {
                $personneModel->update($personneID, $personne);
            }

            # On modifie l'Adresse et personne  dans la BD
            $message = "fournisseur updated successfully";
            createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_FOURNISSEUR);
            return success200($message);
        } else {
            $message = "No fournisseur Found ";
            return success205($message);
        }
    }
}