<?php
/*
 * PLANTILLA 1 - Cabecera del documento: DOCTYPE, <html>, <head> y hojas de estilo.
 */
require_once __DIR__ . '/funciones.php';
require_once __DIR__ . '/listas.php';

$titulo = $titulo ?? 'Nexocasa';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexocasa | <?= e($titulo) ?></title>
    <link rel="icon" href="img/Favicon Nexocasa.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="css/estilos.css" title="Estilo principal" media="screen">
    <link rel="alternate stylesheet" href="css/estilos.css" title="Modo noche" media="screen">
    <link rel="alternate stylesheet" href="css/noche.css" title="Modo noche" media="screen">
    <link rel="alternate stylesheet" href="css/estilos.css" title="Alto contraste" media="screen">
    <link rel="alternate stylesheet" href="css/contraste.css" title="Alto contraste" media="screen">
    <link rel="alternate stylesheet" href="css/estilos.css" title="Letra grande" media="screen">
    <link rel="alternate stylesheet" href="css/letra-grande.css" title="Letra grande" media="screen">
    <link rel="alternate stylesheet" href="css/estilos.css" title="Alto contraste y letra grande" media="screen">
    <link rel="alternate stylesheet" href="css/contraste.css" title="Alto contraste y letra grande" media="screen">
    <link rel="alternate stylesheet" href="css/letra-grande.css" title="Alto contraste y letra grande" media="screen">
    <link rel="stylesheet" href="css/impresion.css" media="print">
</head>