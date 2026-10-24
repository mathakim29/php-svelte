<?php

class_alias('RedBeanPHP\\R', 'R');
if (getenv('DB_DRIVER') === 'mysql') {
    R::setup(
        'mysql:host=' . (getenv('DB_HOST') ?: 'mysql') . ';dbname=' . (getenv('DB_NAME') ?: 'app'),
        getenv('DB_USER') ?: 'app',
        getenv('DB_PASSWORD') ?: 'app'
    );
} else {
    R::setup('sqlite:/app/data.db');
}

<?php

require '/app/vendor/autoload.php';

foreach (glob(dirname(__DIR__) . '/api/*.php') as $script) {
    require_once $script;
}

Flight::route('GET /', function () {
    echo '<meta charset="utf-8"><link rel="stylesheet" href="/style.css"><script type="module" src="/bundle.js"></script>';
});

Flight::route('GET /__reload', function () {
    $log = is_readable('/tmp/php-svelte-reload.log')
        ? file_get_contents('/tmp/php-svelte-reload.log')
        : '';
    $latestRun = strrpos($log, '[Running:');
    $latestOutput = $latestRun === false ? $log : substr($log, $latestRun);

    header('Content-Type: application/json');
    echo json_encode([
        'version' => filemtime('/app/public/bundle.js') . ':' . filemtime('/app/public/style.css'),
        'error' => (bool) preg_match('/(?:error|failed|permission denied|exit code)/i', $latestOutput),
    ]);
});

Flight::route('GET /_err', function () {
    $log = is_readable('/tmp/php-svelte-reload.log')
        ? file_get_contents('/tmp/php-svelte-reload.log')
        : 'No reload log found.';
    $lines = preg_split('/\R/', $log);
    $errors = array_values(array_filter($lines, fn($line) => preg_match(
        '/(?:error|failed|permission denied|exit code|✘)/i',
        $line
    )));
    if (!$errors) {
        header('Location: /');
        exit;
    }
    $message = $errors ? implode("\n", $errors) : 'No compile errors found.';

    header('Content-Type: text/html; charset=utf-8');
    echo '<!doctype html><meta charset="utf-8"><title>Svelte build errors</title>';
    echo '<style>body{margin:2rem;background:#0f172a;color:#e2e8f0;font:14px/1.5 monospace}pre{white-space:pre-wrap}</style>';
    echo '<script src="https://cdn.jsdelivr.net/npm/ansi_up@6.0.2/ansi_up.min.js"></script>';
    echo '<h1>Svelte compile errors</h1><pre id="errors">' . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '</pre>';
    echo '<script>const el=document.getElementById("errors");const ansiUp=new AnsiUp();el.innerHTML=ansiUp.ansi_to_html(el.textContent);</script>';
});

Flight::start();


