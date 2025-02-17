<?php
header("Access-Control-Allow-Origin: *"); 
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");  
header("Access-Control-Allow-Headers: Content-Type, Authorization"); 

// Manejar solicitudes preflight de CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Incluir las rutas de la API
require_once __DIR__ . '/Routes/api.php';
