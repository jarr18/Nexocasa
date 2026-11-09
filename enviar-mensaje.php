<?php
/* Formulario para enviar un mensaje al anunciante. Envía los datos por POST a respuesta-enviar-mensaje.php. */
$titulo = 'Enviar mensaje';
require_once 'includes/cabecera.php';
require_once 'includes/inicio.php';
$idAnuncio = leerId('anuncio');
?>
<main id="contenido">
<h1>Enviar mensaje al anunciante</h1>
<p>Los campos marcados con un asterisco (*) son obligatorios.</p>
<form class="conBorde" action="respuesta-enviar-mensaje.php" method="post"<?= novalidate() ?>>
<?php if ($idAnuncio): ?>
<input type="hidden" name="anuncio" value="<?= $idAnuncio ?>">
<?php endif; ?>
<fieldset>
<legend>Tipo de mensaje *</legend>
<?= radios('tipo-mensaje', TIPOS_MENSAJE) ?>
</fieldset>
<p>
<label for="texto-mensaje">Texto *</label><br>
<textarea id="texto-mensaje" name="texto-mensaje" rows="6" cols="50" required></textarea>
</p>
<p><button type="submit"><i class="fa-solid fa-paper-plane" aria-hidden="true"></i>Enviar mensaje</button></p>
</form>
</main>
<?php require_once 'includes/pie.php'; ?>
