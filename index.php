<?php
/* Página principal (parte pública): búsqueda rápida, acceso de usuarios registrados y últimos anuncios. */
$titulo = 'Inicio';
require_once 'includes/cabecera.php';
require_once 'includes/inicio.php';
require_once 'includes/anuncios.php';
$scriptsValidacion = ['js/validaciones.js', 'js/interfaz.js', 'js/login.js'];
?>
<main id="contenido" class="columnas">
<h1>Encuentra tu próximo hogar en España</h1>
<?php require 'includes/busqueda-rapida.php'; ?>
<?php require 'includes/acceso.php'; ?>
<section>
<h2>Últimos anuncios publicados</h2>
<?php listaAnuncios(require 'datos/ultimos-anuncios.php', 'detalle-anuncio.php', 'h3'); ?>
</section>
</main>
<?php require_once 'includes/pie.php'; ?>
