<?php
/*
 * "Ver anuncio" (parte privada): toda la información de un anuncio del usuario y un enlace
 * a anadir-foto.php?anuncio=N para añadirle una foto. Recibe el identificador por la URL: ?id=N
 */
require_once 'includes/funciones.php';
require_once 'includes/anuncios.php';

$anuncio = obtenerAnuncio(leerId('id'));
if ($anuncio === null) {
    http_response_code(404);
}

$titulo = $anuncio['titulo'] ?? 'Anuncio no encontrado';
$zona = 'privada';
require_once 'includes/cabecera.php';
require_once 'includes/inicio.php';
?>
<main id="contenido" class="columnas">
    <?php if ($anuncio): ?>
        <?php fichaAnuncio($anuncio, true); ?>
    <?php else: ?>
        <h1>Anuncio no encontrado</h1>
        <p>El anuncio que buscas no existe o el enlace no es correcto.</p>
        <p><a href="mis-anuncios.php">Volver a mis anuncios</a></p>
    <?php endif; ?>
</main>
<?php require_once 'includes/pie.php'; ?>