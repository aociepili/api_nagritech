<?php
function authentification()
{
    require_once 'includes/config.php';
    require_once 'classes/JWT.php';

    $jwt = new JWT();
    $token = getToken();

    // On vérifie la validité
    if (!$jwt->isValid($token)) {
        http_response_code(400);
        echo json_encode(['message' => 'Votre token est invalide']);
        exit;
    }

    // On vérifie la signature
    if (!$jwt->check($token, SECRET)) {
        http_response_code(403);
        echo json_encode(['message' => 'Le token est invalide']);
        exit;
    }

    // On vérifie l'expiration
    if ($jwt->isExpired($token)) {
        http_response_code(403);
        echo json_encode(['message' => 'votre session  a expirée']);
        exit;
    }
    return json_encode($jwt->getPayload($token));
}
function getToken()
{
    // On vérifie si on reçoit un token
    if (isset($_SERVER['Authorization'])) {
        $token = trim($_SERVER['Authorization']);
    } elseif (isset($_SERVER['HTTP_AUTHORIZATION'])) {
        $token = trim($_SERVER['HTTP_AUTHORIZATION']);
    } elseif (function_exists('apache_request_headers')) {
        $requestHeaders = apache_request_headers();
        if (isset($requestHeaders['Authorization'])) {
            $token = trim($requestHeaders['Authorization']);
        }
    }
    // var_dump("test");
    // debug400("test", apache_request_headers());
    // On vérifie si la chaine commence par "Bearer "
    if (!isset($token) || !preg_match('/Bearer\s(\S+)/', $token, $matches)) {
        http_response_code(400);
        // echo json_encode(['message' => 'Votre autorisation (Token) introuvable']);
        echo json_encode(['message' => 'Veuillez vous connecter pour acceder aux informations']);
        exit;
    }

    // On extrait le token
    $token = str_replace('Bearer ', '', $token);
    return $token;
}