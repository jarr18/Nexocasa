<?php
/*
 * Detalle de un anuncio (parte pública). Recibe el identificador por la URL: detalle-anuncio.php?id=N
 * Si N es par se muestra un anuncio y si es impar el otro (datos/anuncios.php).
 */
require_once 'includes/funciones.php';
require_once 'includes/anuncios.php';

$anuncio = obtenerAnuncio(leerId('id'));
if ($anuncio === null) {
    // Falta el identificador o no es un número positivo
    http_response_code(404);
}

$titulo = $anuncio['titulo'] ?? 'Anuncio no encontrado';
require_once 'includes/cabecera.php';
require_once 'includes/inicio.php';
?>
<main id="contenido" class="columnas">
    <?php if ($anuncio): ?>
        <?php fichaAnuncio($anuncio, false); ?>
    <?php else: ?>
        <h1>Anuncio no encontrado</h1>
        <p>El anuncio que buscas no existe o el enlace no es correcto.</p>
        <p><a href="index.php">Volver a la página principal</a></p>
    <?php endif; ?>
</main>
<?php require_once 'includes/pie.php'; ?>