<?php

if (parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) === '/') {
    header('Location: /dist/index.html', true, 302);
    exit;
}

require '/app/server/index.php';
