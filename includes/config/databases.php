<?php

function conectarDB() : mysqli {
    $db = new mysqli('localhost', 'root' , '51573M45', 'bienesraices_crud');

    if (!$db) {
        echo "Error no se pudo conectar";
        exit;
    } 

    return $db;
}
