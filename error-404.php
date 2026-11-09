<?php
/* Página de error: se usa para las opciones que todavía no están hechas. */
http_response_code(404);
$titulo = 'Página no encontrada';
require_once 'includes/cabecera.php';
require_once 'includes/inicio.php';
?>
<main id="contenido">
<h1>Error 404: página no encontrada</h1>
<p>Esta página todavía no está disponible en esta versión del sitio web.</p>
<p><a href="index.php">Volver a la página principal</a></p>
</main>
<?php require_once 'includes/pie.php'; ?>
