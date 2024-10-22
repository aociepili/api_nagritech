<?php

use App\Models\AdminsModel;
use App\Models\AgentsModel;
use App\Models\ClientsModel;

require 'php-jwt/authentification.php';
require_once 'php-jwt/classes/JWT.php';

function isOnline()
{
    $jwt = new JWT();

    $payload = authentification();
    $user = (array)json_decode($payload);
    $id = $user['id'];
    $role = $user['role'];
    $online = false;

    #Admin
    if ($role === "isAdmin") {
        $adminModel = new AdminsModel();
        $adminData = $adminModel->find($id);

        // On décode le payload
        $payloadBD = $jwt->getPayload($adminData->token);
        $userAdmin = (array) $payloadBD;

        if (($user['isOnline'] == $userAdmin['isOnline'])) {
            $online = $user['isOnline'];
        } else {
            error405("Admin s'est deja  deconnecté");
        }
    }
    #Agent
    if ($role === "isAgent") {
        $agentModel = new AgentsModel();
        $agentData = $agentModel->find($id);

        // On décode le payload
        $payloadBD = $jwt->getPayload($agentData->token);
        $userAgent = (array) $payloadBD;

        if (($user['isOnline'] == $userAgent['isOnline'])) {
            $online = $user['isOnline'];
        } else {
            error405("Agent s'est deja  deconnecté");
        }
    }
    #Client
    if ($role === "isClient") {
        $clientModel = new ClientsModel();
        $clientData = $clientModel->find($id);

        // On décode le payload
        $payloadBD = $jwt->getPayload($clientData->token);
        $userClient = (array) $payloadBD;

        if (($user['isOnline'] == $userClient['isOnline'])) {
            $online = $user['isOnline'];
        } else {
            error405("Client s'est deja  deconnecté");
        }
    }

    return $online;
}

function accesAdmin()
{
    $payload = authentification();
    $user = (array)json_decode($payload);
    $id = $user['id'];
    $role = $user['role'];
    $online = false;

    #Admin
    if (in_array($role, ADMIN_USER)) {
        $online = true;
        return $online;
    } else {
        $data = [
            'status' => 203,
            'message' => " Accès uniquement réserver à l'Administrateur",
        ];
        header("HTTP/1.0 203 Non-Authoritative Information");
        echo json_encode($data);
        exit();
    }
}
function accesAgent()
{
    $payload = authentification();
    $user = (array)json_decode($payload);
    $id = $user['id'];
    $role = $user['role'];
    $online = false;

    #Agent
    if (in_array($role, AGENT_USER)) {
        $online = true;
        return $online;
    } else {
        $data = [
            'status' => 203,
            'message' => " Accès uniquement réserver à l'Agent",
        ];
        header("HTTP/1.0 203 Non-Authoritative Information");
        echo json_encode($data);
        exit();
    }
}
function accesClient()
{
    $payload = authentification();
    $user = (array)json_decode($payload);
    $id = $user['id'];
    $role = $user['role'];
    $online = false;

    #Client
    if (in_array($role, CLIENT_USER)) {
        $online = true;
        return $online;
    } else {
        $data = [
            'status' => 203,
            'message' => " Accès uniquement réserver au Client",
        ];
        header("HTTP/1.0 203 Non-Authoritative Information");
        echo json_encode($data);
        exit();
    }
}