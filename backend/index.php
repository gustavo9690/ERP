<?php

require_once __DIR__ . '/app/core/Autoload.php';

// registrar autoload
Autoload::register();

// iniciar aplicación
$app = new App();

$app->run();
