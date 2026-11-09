<?php
/*
 * "Añadir foto a anuncio" (parte privada): formulario con la foto, el texto alternativo, el título
 * y el anuncio al que se añade.
 *   - Desde el menú (anadir-foto.php): lista desplegable sin ningún anuncio seleccionado.
 *   - Desde "Mis anuncios" o "Ver anuncio" (anadir-foto.php?anuncio=N): el anuncio aparece
 *     seleccionado y la lista está desactivada (no se puede cambiar). Como un <select disabled>
 *     no se envía, el valor viaja en un campo oculto.
 * La subida de la foto y la página de respuesta se harán en una práctica posterior.
 */
$titulo = 'Añadir foto a anuncio';
$zona = 'privada';
require_once 'includes/cabecera.php';
require_once 'includes/inicio.php';
require_once 'includes/anuncios.php';

$anuncioFijo = obtenerAnuncio(leerId('anuncio'));        // null si se llega desde el menú
$seleccionado = $anuncioFijo ? (string) $anuncioFijo['id'] : '';
?>
<main id="contenido">
    <h1>Añadir una foto a un anuncio</h1>
    <p>Los campos marcados con un asterisco (*) son obligatorios.</p>
    <form class="conBorde" action="error-404.php" method="post" enctype="multipart/form-data">
        <p>
            <label for="anuncio">Anuncio *</label><br>
            <?php if ($anuncioFijo): ?>
                <select id="anuncio" disabled>
                    <?= opciones(opcionesAnuncios(), $seleccionado) ?>
                </select>
                <input type="hidden" name="anuncio" value="<?= e($seleccionado) ?>">
                <small class="ayuda">La foto se añadirá a este anuncio.</small>
            <?php else: ?>
                <select id="anuncio" name="anuncio" required>
                    <?= opciones(opcionesAnuncios(), '', '(Elige un anuncio)') ?>
                </select>
            <?php endif; ?>
        </p>
        <p>
            <label for="foto">Foto *</label><br>
            <input type="file" id="foto" name="foto" accept="image/*" required>
        </p>
        <p>
            <label for="texto-alternativo">Texto alternativo *</label><br>
            <input type="text" id="texto-alternativo" name="texto-alternativo" minlength="10" maxlength="255" required>
            <small class="ayuda">Describe lo que se ve en la foto (mínimo 10 caracteres). Ejemplo: Salón con chimenea y
                dos sofás</small>
        </p>
        <p>
            <label for="titulo-foto">Título *</label><br>
            <input type="text" id="titulo-foto" name="titulo" maxlength="100" required>
        </p>
        <p><button type="submit"><i class="fa-solid fa-camera" aria-hidden="true"></i>Añadir foto</button></p>
    </form>
</main>
<?php require_once 'includes/pie.php'; ?>