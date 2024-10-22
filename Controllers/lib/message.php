<?php
function error401($message)
{
    $data = [
        'status' => 401,
        'message' => $message,
    ];
    header("HTTP/1.0 401 Unauthorized");
    echo json_encode($data);
    exit();
}
function error404($message)
{
    $data = [
        'status' => 404,
        'message' => $message,
    ];
    header("HTTP/1.0 404 Not Found");
    echo json_encode($data);
    exit();
}
function  error405($message): void
{
    $data = [
        'status' => 405,
        'message' => $message,
    ];
    header("HTTP/1.0 405 Method Not Allowed");
    echo json_encode($data);
    exit();
}
function  error406($message): void
{
    $data = [
        'status' => 406,
        'message' => $message,
    ];
    header("HTTP/1.0 406 Not Acceptable");
    echo json_encode($data);
    exit();
}

function  error415($message): void
{
    $data = [
        'status' => 415,
        'message' => $message,
    ];
    header("HTTP/1.0 415 Unsupported Media Type
    ");
    echo json_encode($data);
}

function  error422($message): void
{
    $data = [
        'status' => 422,
        'message' => $message,
    ];
    header("HTTP/1.0 422 Unprocessable Entity");
    echo json_encode($data);
    exit();
}

function error500($message)
{
    $data = [
        'status' => 500,
        'message' => $message
    ];
    header("HTTP/1.0 500 Internal Server Error");
    return json_encode($data);
}


#Message de Succees

function success200($message)
{
    $data = [
        'status' => 200,
        'message' => $message,

    ];
    header("HTTP/1.0 200 Okay");
    echo json_encode($data);
}

function datasuccess200($message, $donnees)
{
    $data = [
        'status' => 200,
        'message' => $message,
        'data' => $donnees

    ];
    header("HTTP/1.0 200 OK");
    return json_encode($data);
}
function dataTableSuccess200($message, $donnees)
{
    $data = [
        'status' => 200,
        'message' => $message,
        'size' => sizeof($donnees),
        'data' => $donnees

    ];
    header("HTTP/1.0 200 OK");
    return json_encode($data);
}
function datasuccess201($message, $token)
{
    $data = [
        'status' => 201,
        'message' => $message,
        'toke' => $token

    ];
    header("HTTP/1.0 201 Created");
    return json_encode($data);
}
function success201($message)
{
    $data = [
        'status' => 201,
        'message' => $message,

    ];
    header("HTTP/1.0 201 Created");
    return json_encode($data);
}


function success202($message)
{
    $data = [
        'status' => 202,
        'message' => $message,

    ];
    header("HTTP/1.0 202 Accepted");
    return json_encode($data);
}
function datasuccess202($message, $token, $user)
{
    $data = [
        'status' => 201,
        'message' => $message,
        'token' => $token,
        'user' => $user

    ];
    header("HTTP/1.0 202 Accepted");
    return json_encode($data);
}
function success203($message)
{

    $data = [
        'status' => 203,
        'message' => $message,

    ];
    header("HTTP/1.0 203 Non-Authoritative Information");
    echo json_encode($data);
    exit();
}
function success404($message)
{
    $data = [
        'status' => 204,
        'message' => $message,
    ];
    header("HTTP/1.0 404 No Content");
    echo json_encode($data);
    exit();
}
function success205($message)
{
    $data = [
        'status' => 205,
        'message' => $message,
        'data' => array(),

    ];
    header("HTTP/1.0 205 Reset Content");
    echo json_encode($data);
    exit();
}