<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


$requestMethod = $_SERVER["REQUEST_METHOD"];
if ($_SERVER["REQUEST_METHOD"] === 'OPTIONS') {
    header('HTTP/1.1 200 OK');
    exit();
}

$data = [
    'status' => 200,
    'message' =>  "Bienvenue sur API Nagritech-v3... ",
];
header("HTTP/1.0 200 OK");
echo json_encode($data);