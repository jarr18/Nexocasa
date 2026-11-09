<?php
/* Formulario de búsqueda. Envía los datos por GET a resultado-busqueda.php (la búsqueda se puede guardar en favoritos). */
$titulo = 'Buscar inmuebles';
require_once 'includes/cabecera.php';
require_once 'includes/inicio.php';
?>
<main id="contenido">
<h1>Buscar inmuebles</h1>
<form class="conBorde" action="resultado-busqueda.php" method="get">
<fieldset>
<legend>Tipo de anuncio</legend>
<?= radios('tipo-anuncio', TIPOS_ANUNCIO) ?>
</fieldset>
<p>
<label for="tipo-vivienda">Tipo de vivienda</label><br>
<select id="tipo-vivienda" name="tipo-vivienda">
<?= opciones(TIPOS_VIVIENDA, '', '(Cualquiera)') ?>
</select>
</p>
<p>
<label for="ciudad">Ciudad</label><br>
<input type="text" id="ciudad" name="ciudad">
</p>
<p>
<label for="pais">País</label><br>
<select id="pais" name="pais">
<?= opciones(PAISES, 'espana', '(Cualquiera)') ?>
</select>
</p>
<fieldset>
<legend>Precio entre</legend>
<p><label for="precio-desde">Desde (€)</label>
<input type="number" id="precio-desde" name="precio-desde" min="0" step="1000"></p>
<p><label for="precio-hasta">Hasta (€)</label>
<input type="number" id="precio-hasta" name="precio-hasta" min="0" step="1000"></p>
</fieldset>
<fieldset>
<legend>Fecha de publicación entre</legend>
<p><label for="fecha-desde">Desde</label>
<input type="date" id="fecha-desde" name="fecha-desde"></p>
<p><label for="fecha-hasta">Hasta</label>
<input type="date" id="fecha-hasta" name="fecha-hasta"></p>
</fieldset>
<p><button type="submit"><i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>Buscar</button></p>
</form>
</main>
<?php require_once 'includes/pie.php'; ?>
