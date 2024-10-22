<?php
const SECRET = '@n93361945ehula@Tech!';


function formatHeader(): array
{
    // On crée le header
    $header = [
        'typ' => 'JWT',
        'alg' => 'HS256'
    ];
    return $header;
}