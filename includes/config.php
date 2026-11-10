<?php

$databaseUrl = getenv('SUPABASE_DB_URL') ?: getenv('DATABASE_URL') ?: getenv('POSTGRES_URL');
$sslMode = getenv('DB_SSLMODE') ?: 'require';

if ($databaseUrl !== false && $databaseUrl !== '') {
    $connection = parse_url($databaseUrl);

    if (
        $connection === false ||
        empty($connection['scheme']) ||
        !in_array($connection['scheme'], ['postgres', 'postgresql'], true) ||
        empty($connection['host']) ||
        empty($connection['user']) ||
        !isset($connection['pass']) ||
        empty($connection['path'])
    ) {
        http_response_code(503);
        header('Content-Type: text/plain; charset=UTF-8');
        exit('The Supabase database URL is invalid. Use a PostgreSQL connection string from Supabase.');
    }

    parse_str($connection['query'] ?? '', $connectionOptions);
    $dbServer = $connection['host'];
    $dbPort = $connection['port'] ?? 5432;
    $dbUser = rawurldecode($connection['user']);
    $dbPass = rawurldecode($connection['pass']);
    $dbName = ltrim(rawurldecode($connection['path']), '/');
    $sslMode = $connectionOptions['sslmode'] ?? $sslMode;
} else {
    $dbServer = getenv('DB_HOST');
    $dbPort = getenv('DB_PORT') ?: 5432;
    $dbUser = getenv('DB_USER');
    $dbPass = getenv('DB_PASSWORD');
    $dbName = getenv('DB_NAME');
}

if (!in_array($sslMode, ['disable', 'allow', 'prefer', 'require', 'verify-ca', 'verify-full'], true)) {
    http_response_code(503);
    header('Content-Type: text/plain; charset=UTF-8');
    exit('The PostgreSQL SSL mode is invalid.');
}

if (
    filter_var($dbPort, FILTER_VALIDATE_INT) === false ||
    (int) $dbPort < 1 ||
    (int) $dbPort > 65535
) {
    http_response_code(503);
    header('Content-Type: text/plain; charset=UTF-8');
    exit('The PostgreSQL port is invalid.');
}

if (
    $dbServer === false || $dbServer === '' ||
    $dbUser === false || $dbUser === '' ||
    $dbPass === false || $dbPass === '' ||
    $dbName === false || $dbName === ''
) {
    http_response_code(503);
    header('Content-Type: text/plain; charset=UTF-8');
    exit('PostgreSQL configuration is missing. Set SUPABASE_DB_URL or DB_HOST, DB_PORT, DB_NAME, DB_USER, and DB_PASSWORD.');
}

define('DB_SERVER', $dbServer);
define('DB_PORT', (int) $dbPort);
define('DB_USER', $dbUser);
define('DB_PASS', $dbPass);
define('DB_NAME', $dbName);
$dsnValue = static fn (string $value): string => str_replace(
    ['\\', ';'],
    ['\\\\', '\\;'],
    $value
);

$dsn = sprintf(
    'pgsql:host=%s;port=%d;dbname=%s;sslmode=%s',
    $dsnValue(DB_SERVER),
    DB_PORT,
    $dsnValue(DB_NAME),
    $sslMode
);

try {
    $con = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $exception) {
    error_log('PostgreSQL connection failed: ' . $exception->getMessage());
    http_response_code(503);
    header('Content-Type: text/plain; charset=UTF-8');
    exit('The Supabase PostgreSQL database is unavailable. Check its connection string, credentials, and network access.');
}

require_once __DIR__ . '/database.php';

define('PAYPAL_CLIENT_ID', getenv('PAYPAL_CLIENT_ID') ?: '');
define('PAYPAL_SECRET', getenv('PAYPAL_SECRET') ?: '');
define('PAYPAL_API_BASE', 'https://api-m.sandbox.paypal.com'); // Use live URL in production

?>