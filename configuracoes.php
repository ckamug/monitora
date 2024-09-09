<?php

/*
ARQUIVO DE CONFIGURAÇÕES DA PLATAFORMA RECOMEÇO
DESENVOLVIDO POR CAIO EM 20/02/2014
*/

//phpinfo();

error_reporting(0);// E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED

/* PROD */
define("DIR",$_SERVER['DOCUMENT_ROOT']."/coed/");
define("URL", "https://portal.seds.sp.gov.br/coed/");
//define("DIR",$_SERVER['DOCUMENT_ROOT']."/");
//define("URL", "http://".$_SERVER["HTTP_HOST"]."/");

/* DEV */
//define("DIR",$_SERVER['DOCUMENT_ROOT']."/coed/");
//define("URL", "http://".$_SERVER["HTTP_HOST"]."/coed/");

// Define Timezone
//date_default_timezone_set("America/Araguaina");
date_default_timezone_set("America/Fortaleza");