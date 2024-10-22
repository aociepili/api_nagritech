<?php
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . "../");
include('../Autoloader.php');
include('controllers.php');


use App\Autoloader;
use App\Models\NotificationsModel;

Autoloader::register();
# notifications

# Store
function storeNotification($notificationData)
{
    require_once 'php-jwt/authentification.php';
    $payload = authentification();
    $user = (array)json_decode($payload);

    $notificationModel = new NotificationsModel();
    $notification = $notificationModel;

    chargementNotification($notificationData);
    $today = getSiku();
    $date = $today;
    $titre = $notificationData["titre"];
    $description = $notificationData["description"];
    $auteurID = $user["id"];
    $role = $user["role"];

    $notification->setDate($date);
    $notification->setAuteurID($auteurID);
    $notification->setDescription($description);
    $notification->setRole($role);
    $notification->setTitre($titre);
    $notification->setCreated_at($today);

    $notificationModel->create($notification);
    createActivity(TYPE_OP_CREATE, STATUS_OP_OK, TABLE_NOTIFICATION);
    $message = "notification created successfully";
    return success201($message);
}

#Delete
function deleteNotification($notificationParams)
{
    $notificationModel = new NotificationsModel();
    paramsVerify($notificationParams, "notification");

    # On recupere les informations venues de POST
    $notificationID = $notificationParams['id'];
    $notiFoundData = $notificationModel->find($notificationID);

    if ($notificationID == $notiFoundData->id) {
        $res = $notificationModel->delete($notificationID);
        $message = "notification deleted successfully";
        createActivity(TYPE_OP_DELETE, STATUS_OP_OK, TABLE_NOTIFICATION);
        return success200($message);
    } else {
        $message = "notification not delete ";
        createActivity(TYPE_OP_DELETE, STATUS_OP_NOT, TABLE_NOTIFICATION);
        return error405($message);
    }
}

#Get
function getNotificationbyId($notificationParams)
{
    $notificationModel = new NotificationsModel();
    paramsVerify($notificationParams, "notification");
    $res = $notificationModel->find($notificationParams['id']);

    if (!empty($res)) {
        $message = "notification Fetched successfully";
        createActivity(TYPE_OP_READBY, STATUS_OP_OK, TABLE_NOTIFICATION);
        return datasuccess200($message, $res);
    } else {
        $message = "notification not found";
        return success205($message);
    }
}

function getListNotification()
{
    $notificationModel = new NotificationsModel();
    $notifications = (array)$notificationModel->findAll();

    if (!empty($notifications)) {
        $message = "Liste des notifications ";
        createActivity(TYPE_OP_READ, STATUS_OP_OK, TABLE_NOTIFICATION);
        return dataTableSuccess200($message, $notifications);
    } else {
        $message = "Pas de Notification";
        return success205($message);
    }
}

# Update
function updateNotification($notificationData, $notificationParams)
{
    require_once 'php-jwt/authentification.php';
    $payload = authentification();
    $user = (array)json_decode($payload);
    $notificationModel = new NotificationsModel();
    $notification = $notificationModel;

    paramsVerify($notificationParams, "notification");

    $notificationID = $notificationParams['id'];

    $titre = $notificationData["titre"];
    $description = $notificationData["description"];
    $auteurID = $user["id"];
    $role = $user["role"];
    $today = getSiku();

    $notification->setAuteurID($auteurID);
    $notification->setDescription($description);
    $notification->setRole($role);
    $notification->setTitre($titre);
    $notification->setUpdated_at($today);
    $notiFoundData = $notificationModel->find($notificationID);

    if ($notificationID == $notiFoundData->id) {
        $notificationModel->update($notificationID, $notification);
        # On ajoute l'Adresse  dans la BD
        $message = "notification updated successfully";
        createActivity(TYPE_OP_UPDATE, STATUS_OP_OK, TABLE_NOTIFICATION);
        return success200($message);
    } else {
        $message = "notification not Found";
        return success205($message);
    }
}

function desactiverNotification($notificationParams)
{
    $notificationModel = new NotificationsModel();
    $notification = $notificationModel;

    paramsVerify($notificationParams, "notification");

    $notificationID = $notificationParams['id'];
    $today = getSiku();

    $notification->setActive(false);
    $notification->setUpdated_at($today);
    $notiFoundData = $notificationModel->find($notificationID);

    if ($notificationID == $notiFoundData->id) {
        $notificationModel->update($notificationID, $notification);
        # On ajoute l'Adresse  dans la BD
        $message = " notification desactived successfully";
        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_NOTIFICATION);
        return success200($message);
    } else {
        $message = "notification not Found";
        return success205($message);
    }
}
function activerNotification($notificationParams)
{
    $notificationModel = new NotificationsModel();
    $notification = $notificationModel;

    paramsVerify($notificationParams, "notification");

    $notificationID = $notificationParams['id'];
    $today = getSiku();

    $notification->setActive(true);
    $notification->setUpdated_at($today);
    $notiFoundData = $notificationModel->find($notificationID);

    if ($notificationID == $notiFoundData->id) {
        $notificationModel->update($notificationID, $notification);
        # On ajoute l'Adresse  dans la BD
        $message = " notification desactived successfully";
        createActivity(TYPE_OP_UPDATE_STATUS, STATUS_OP_OK, TABLE_NOTIFICATION);
        return success200($message);
    } else {
        $message = "notification not Found";
        return success205($message);
    }
}

function chargerImageNews($imgData, $notificationParams)
{
    $notificationModel = new NotificationsModel();
    $notification = $notificationModel;
    paramsVerify($notificationParams, "notification");
    $notificationID = $notificationParams["id"];
    $notificationFound = $notificationModel->find($notificationID);


    if (empty($notificationFound)) {
        $message = "No notification Found or Internal Server Error";
        createActivity(TYPE_OP_UP_IMAGE, STATUS_OP_NOT, TABLE_NOTIFICATION);
        return success205($message);
    } else {
        #Image
        $nbre = generateNumber();

        #Nouvelle denomination du fichier
        $title = "img_news" . $nbre;

        # Traitement de donnees du fichier
        $titreImg = $imgData['name'];
        $type = $imgData['type'];

        $directory = $imgData['tmp_name'];
        $extension = strrchr($titreImg, ".");
        $directorysend = "../public/img/notification/" . $title . "" . $extension;
        #Extension prise en charge
        $valideExtension = array('.jpg', '.png', '.jpeg');

        $test = in_array($extension, $valideExtension);

        if ($test) {

            if (move_uploaded_file($directory, $directorysend)) {
                # Nouveau chemin d'acces a l image de profil
                $pathLogo = "../public/img/notification/" . $title . "" . $extension;
                $notification->setImage_news($pathLogo);
                $notificationModel->update($notificationID, $notification);
                createActivity(TYPE_OP_UP_IMAGE, STATUS_OP_OK, TABLE_NOTIFICATION);

                $message = "Le logo a été chargé  avec succès";
                return success200($message);
            } else {

                $message = "Un Problème de télechargement du fichier";
                createActivity(TYPE_OP_UP_IMAGE, STATUS_OP_NOT, TABLE_NOTIFICATION);
                success205($message);
            }
        } else {
            $message = "Le fichier charge n'est pas prise en charge";
            createActivity(TYPE_OP_UP_IMAGE, STATUS_OP_NOT, TABLE_NOTIFICATION);
            success205($message);
        }
    }
}