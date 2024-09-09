<?php
$view = new requestViews();//CRIA UM OBJETO DA CLASSE requestViews NA MEM�RIA 
$view->addCss("index.css");//ADCIONA OS ARQUIVOS CSS NA P�GINA
$view->addJavaScript("index.js");//ADCIONA OS ARQUIVOS JAVASCRIPT NA P�GINA
$view->getView();//CARREGA A VIEW(HTML)