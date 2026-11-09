<?php
/* Bloque reutilizable: formulario de búsqueda rápida (página principal pública y privada). */
?>
<section class="conBorde">
<h2>Búsqueda rápida</h2>
<form action="resultado-busqueda.php" method="get">
<p>
<label for="busqueda-rapida"><i class="fa-solid fa-location-dot" aria-hidden="true"></i>Ciudad o provincia</label><br>
<input type="text" id="busqueda-rapida" name="ciudad">
</p>
<p><button type="submit"><i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>Buscar</button></p>
</form>
</section>
