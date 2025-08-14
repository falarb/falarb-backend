<?php

// Gera um token de 4 dígitos
function geraToken()
{
    $token = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

    return $token;
}