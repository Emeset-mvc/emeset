<?php

declare(strict_types=1);

// Autoload de Composer
require __DIR__ . '/../vendor/autoload.php';

// Opcional: marcar entorn de test via env (el Container ja detecta PHPUnit)
$_ENV['PHPUNIT_RUNNING'] = '1';
