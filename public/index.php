<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Folder public di server (bisa mengandung spasi, mis. "/acara 12/public")
$scriptDir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');

// BASE_URL di-encode per segmen supaya spasi menjadi %20 (aman untuk link & redirect)
define('BASE_URL', implode('/', array_map('rawurlencode', explode('/', $scriptDir))));


require_once __DIR__ . '/../app/Controllers/BaseController.php';
require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/DashboardController.php';
require_once __DIR__ . '/../app/Controllers/MahasiswaController.php';
require_once __DIR__ . '/../app/Controllers/DosenController.php';

// Load routes
require_once __DIR__ . '/../routes/web.php';

// Ambil URL lalu decode (%20 -> spasi) supaya cocok dengan $scriptDir
$requestUri = rawurldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Hilangkan base path (folder public)
if ($scriptDir !== '' && strpos($requestUri, $scriptDir) === 0) {
    $requestUri = substr($requestUri, strlen($scriptDir));
}

// Pastikan diawali / dan tanpa slash di akhir
$requestUri = '/' . trim($requestUri, '/');

// Routing
if (isset($routes[$requestUri])) {
 
    $controllerName = $routes[$requestUri][0];
    $methodName = $routes[$requestUri][1];

    $controller = new $controllerName();

    if (method_exists($controller, $methodName)) {
        $controller->$methodName();
    } else {
        http_response_code(404);
        echo "Method tidak ditemukan.";
    }

} else {

    http_response_code(404);

    echo "<h1>404 - Halaman Tidak Ditemukan</h1>";
    echo "<p>Route <b>" . htmlspecialchars($requestUri) . "</b> tidak tersedia.</p>";
}