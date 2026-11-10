<?php

$dbServer = getenv('DB_HOST');
$dbPort = getenv('DB_PORT');
$dbUser = getenv('DB_USER');
$dbPass = getenv('DB_PASSWORD');
$dbName = getenv('DB_NAME');

if (
    getenv('VERCEL') === '1' &&
    (
        $dbServer === false || $dbServer === '' ||
        $dbUser === false || $dbUser === '' ||
        $dbPass === false ||
        $dbName === false || $dbName === ''
    )
) {
    http_response_code(503);
    header('Content-Type: text/plain; charset=UTF-8');
    exit('Database configuration is missing. Set DB_HOST, DB_NAME, DB_USER, and DB_PASSWORD in Vercel. DB_PORT is optional (3306 by default).');
}

define('DB_SERVER', $dbServer !== false ? $dbServer : 'localhost');
define('DB_PORT', $dbPort !== false && $dbPort !== '' ? (int) $dbPort : 3306);
define('DB_USER', $dbUser !== false ? $dbUser : 'root');
define('DB_PASS', $dbPass !== false ? $dbPass : '');
define('DB_NAME', $dbName !== false ? $dbName : 'shopping');

mysqli_report(MYSQLI_REPORT_OFF);
$con = @mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME, DB_PORT);

if ($con === false) {
    error_log('MySQL connection failed: ' . mysqli_connect_error());
    http_response_code(503);
    header('Content-Type: text/plain; charset=UTF-8');
    exit('The store database is unavailable. Check the DB_* environment variables and database network access.');
}

define('PAYPAL_CLIENT_ID', getenv('PAYPAL_CLIENT_ID') ?: '');
define('PAYPAL_SECRET', getenv('PAYPAL_SECRET') ?: '');
define('PAYPAL_API_BASE', 'https://api-m.sandbox.paypal.com'); // Use live URL in production

?>