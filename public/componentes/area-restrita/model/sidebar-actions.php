<?php 

// INATIVA TODOS OS GRUPOS DO NAV
$nav_group_collapse_dashboard = 'collapsed';
$nav_group_collapse_cadastros = 'collapsed';
$nav_group_collapse_prontuarios = 'collapsed';
$nav_group_collapse_vagas = 'collapsed';
$nav_group_collapse_prestacao = 'collapsed';
$nav_group_collapse_relatorios = 'collapsed';
$nav_group_collapse_sair = 'collapsed';

$nav = explode('/',$_GET['view']);


// TRATA A REQUISIÇÃO VIEW    
switch($nav[0]){
    case 'area-restrita':
        $nav_group_collapse_dashboard = '';
    break;
    case 'acolhidos':
        $nav_group_collapse_cadastros = '';
        $nav_group_cadastros = 'show';
        $nav_item_acolhido = 'active';
    break;
    case 'celebrante':
    case 'cadastro-celebrante':
        $nav_group_collapse_cadastros = '';
        $nav_group_cadastros = 'show';
        $nav_item_celebrante = 'active';
    break;
    case 'executora':
    case 'cadastro-executora':
        $nav_group_collapse_cadastros = '';
        $nav_group_cadastros = 'show';
        $nav_item_executora = 'active';
    break;
    case 'usuarios':
    case 'cadastro-usuario':
        $nav_group_collapse_cadastros = '';
        $nav_group_cadastros = 'show';
        $nav_item_usuarios = 'active';
    break;  
    case 'municipio':
    case 'cadastro-municipio':
        $nav_group_collapse_cadastros = '';
        $nav_group_cadastros = 'show';
        $nav_item_municipio = 'active';
    break;
    case 'prestacoes':
        $nav_group_collapse_prestacoes = '';
        $nav_group_prestacoes = 'show';
        $nav_item_prestacoes = 'active';
    break;
    case 'cabecalho':
        $nav_group_collapse_prestacoes = '';
        $nav_group_prestacoes = 'show';
        $nav_item_cabecalho = 'active';
    break;
 }
