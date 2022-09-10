<?php

function CryptWare($input)
{

    $encrypted_string = openssl_encrypt(
        $input,
        "AES-128-CTR",
        "Yal&Titbay%anW-rak+are84$97618!62482",
        0,
        '0254975630725975'
    );

    return $encrypted_string;
}

function DeCryptWare($input)
{

    $decrypted_string = openssl_decrypt(
        $input,
        "AES-128-CTR",
        "Yal&Titbay%anW-rak+are84$97618!62482",
        0,
        '0254975630725975'
    );

    return $decrypted_string;
}

function CryptDecoder($input)
{
    return str_replace("+", "%2B", $input);
}
