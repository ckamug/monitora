<?php 
require_once __DIR__ . '/loadenv.php';
$url = env('APP_URL', 'http://localhost/') . 'login';
header('location:' . $url); 
?>