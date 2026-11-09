<?php

$projectRoot = realpath(__DIR__ . '/..');
$requestedPath = $_GET['path'] ?? 'index.php';

if (!is_string($requestedPath)) {
    http_response_code(404);
    exit('Not Found');
}

$requestedPath = rawurldecode(str_replace('\\', '/', $requestedPath));

if (
    $projectRoot === false ||
    $requestedPath === '' ||
    str_contains($requestedPath, "\0") ||
    str_starts_with($requestedPath, '/') ||
    preg_match('/(^|\/)\.\.?($|\/)/', $requestedPath)
) {
    http_response_code(404);
    exit('Not Found');
}

$allowedFiles = array_merge(
    glob($projectRoot . DIRECTORY_SEPARATOR . '*.php') ?: [],
    glob($projectRoot . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . '*.php') ?: []
);
$allowedPaths = array_map(
    static fn (string $file): string => str_replace(
        DIRECTORY_SEPARATOR,
        '/',
        substr($file, strlen($projectRoot) + 1)
    ),
    $allowedFiles
);

if (!in_array($requestedPath, $allowedPaths, true)) {
    http_response_code(404);
    exit('Not Found');
}

$scriptPath = realpath($projectRoot . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $requestedPath));

if ($scriptPath === false || !is_file($scriptPath)) {
    http_response_code(404);
    exit('Not Found');
}

$_SERVER['SCRIPT_FILENAME'] = $scriptPath;
$_SERVER['SCRIPT_NAME'] = '/' . $requestedPath;
$_SERVER['PHP_SELF'] = '/' . $requestedPath;

chdir(dirname($scriptPath));
require $scriptPath;
