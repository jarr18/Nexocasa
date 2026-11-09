<?php
/*
 * Respuesta al envío de un mensaje. Recibe los datos por POST desde enviar-mensaje.php.
 * Comprueba que el tipo de mensaje es uno de los permitidos y que el texto no está vacío.
 */
require_once 'includes/funciones.php';
require_once 'includes/listas.php';

// Si se abre la página directamente (sin enviar el formulario), se vuelve al formulario
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirigir('enviar-mensaje.php');
}

$tipo  = leer($_POST, 'tipo-mensaje');
$texto = leer($_POST, 'texto-mensaje');
$idAnuncio = filter_var($_POST['anuncio'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

$errores = [];
if (!array_key_exists($tipo, TIPOS_MENSAJE)) {
    $errores[] = 'Debes elegir un tipo de mensaje válido.';
}
if ($texto === '') {
    $errores[] = 'Debes escribir el texto del mensaje.';
}

$titulo = $errores ? 'Error al enviar el mensaje' : 'Mensaje enviado';
require_once 'includes/cabecera.php';
require_once 'includes/inicio.php';
$volver = 'enviar-mensaje.php' . ($idAnuncio ? "?anuncio=$idAnuncio" : '');
?>
<main id="contenido">
<?php if ($errores): ?>
<h1>No se ha podido enviar el mensaje</h1>
<?php mostrarErrores($errores, 'Revisa estos datos'); ?>
<p><a href="<?= e($volver) ?>"><i class="fa-solid fa-arrow-left" aria-hidden="true"></i>Volver al formulario</a></p>
<?php else: ?>
<h1>Mensaje enviado con éxito</h1>
<p>Tipo de mensaje: <?= e(TIPOS_MENSAJE[$tipo]) ?></p>
<p>Texto: <?= nl2br(e($texto)) ?></p>
<?php if ($idAnuncio): ?>
<p><a href="detalle-anuncio.php?id=<?= $idAnuncio ?>">Volver al anuncio</a></p>
<?php endif; ?>
<?php endif; ?>
</main>
<?php require_once 'includes/pie.php'; ?>
