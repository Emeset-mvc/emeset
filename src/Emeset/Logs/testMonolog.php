<?php

/**
 * Front controler
 * Exemple de MVC per a M613 Desenvolupament d'aplicacions web en entorn de servidor.
 * Aquest Framework implementa el mínim per tenir un MVC per fer pràctiques
 * de M613.
 * @author: Dani Prados dprados@cendrassos.net
 * @version 0.5.0
 *
 * Punt d'netrada de l'aplicació exemple del Framework Emeset.
 * Per provar com funciona es pot executer php -S localhost:8000 a la carpeta public.
 * I amb el navegador visitar la url http://localhost:8000/
 *
 */

include "../vendor/autoload.php";
include "../App/Controllers/error.php";
include "../App/Middleware/auth.php";

/* Creem els container */
$container = new \App\Container(__DIR__ . "/../App/config.php");
$_SESSION['user'] = [
    'username_user' => 'test',
    'role_user' => 'test'
];
$container->get('Log')->doLog();