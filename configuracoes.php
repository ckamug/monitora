<?php

/*
ARQUIVO DE CONFIGURAÇÕES DA PLATAFORMA RECOMEÇO
DESENVOLVIDO POR CAIO EM 20/02/2014
*/

//phpinfo();

error_reporting(0);// E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED

// Load environment variables
require_once __DIR__ . '/loadenv.php';

// Define DIR and URL from environment or defaults
define("DIR", $_SERVER['DOCUMENT_ROOT']."/");
define("URL", env('APP_URL', "http://".$_SERVER["HTTP_HOST"]."/"));

// Define Timezone
//date_default_timezone_set("America/Araguaina");
date_default_timezone_set("America/Fortaleza");