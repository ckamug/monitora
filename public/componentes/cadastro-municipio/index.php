<?php
$view = new requestViews();//CRIA UM OBJETO DA CLASSE requestViews NA MEMÓRIA 
$view->addCss("index.css");//ADCIONA OS ARQUIVOS CSS NA PÁGINA
$view->addJavaScript("index.js");//ADCIONA OS ARQUIVOS JAVASCRIPT NA PÁGINA
$view->getView();//CARREGA A VIEW(HTML)