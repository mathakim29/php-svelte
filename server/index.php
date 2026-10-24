<?php

require '/app/vendor/autoload.php';
require_once '/app/server/bootstrap.php';

foreach (glob('/app/server/routes/*.php') as $script) {
    require_once $script;
}

Flight::route('GET /', function () {
    header('Location: /dist/index.html', true, 302);
    exit;
});

Flight::route('GET /__reload', function () {
    header('Content-Type: application/json');
    echo json_encode(['version' => (string) time(), 'error' => false]);
});

Flight::start();
