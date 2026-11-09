<?php
/*
 * "Crear anuncio" (parte privada): formulario con los datos necesarios para crear un anuncio.
 * La página de respuesta se hará en una práctica posterior (de momento el formulario va a error-404.php).
 */
$titulo = 'Crear anuncio';
$zona = 'privada';
require_once 'includes/cabecera.php';
require_once 'includes/inicio.php';
?>
<main id="contenido">
    <h1>Crear un anuncio nuevo</h1>
    <p>Los campos marcados con un asterisco (*) son obligatorios.</p>
    <form class="conBorde" action="error-404.php" method="post">
        <fieldset>
            <legend>Tipo de anuncio *</legend>
            <?= radios('tipo-anuncio', TIPOS_ANUNCIO) ?>
        </fieldset>
        <p>
            <label for="tipo-vivienda">Tipo de vivienda *</label><br>
            <select id="tipo-vivienda" name="tipo-vivienda" required>
                <?= opciones(TIPOS_VIVIENDA, '', '(Elige un tipo)') ?>
            </select>
        </p>
        <p>
            <label for="titulo-anuncio">Título *</label><br>
            <input type="text" id="titulo-anuncio" name="titulo" maxlength="100" required>
        </p>
        <p>
            <label for="precio">Precio (€) *</label><br>
            <input type="number" id="precio" name="precio" min="0" step="1" required>
        </p>
        <p>
            <label for="texto">Texto del anuncio *</label><br>
            <textarea id="texto" name="texto" rows="6" cols="50" maxlength="4000" required></textarea>
        </p>
        <p>
            <label for="ciudad">Ciudad *</label><br>
            <input type="text" id="ciudad" name="ciudad" required>
        </p>
        <p>
            <label for="pais">País *</label><br>
            <select id="pais" name="pais" required>
                <?= opciones(PAISES, 'espana') ?>
            </select>
        </p>
        <fieldset>
            <legend>Características</legend>
            <p><label for="superficie">Superficie (m²)</label>
                <input type="number" id="superficie" name="superficie" min="1" step="1">
            </p>
            <p><label for="habitaciones">Número de habitaciones</label>
                <input type="number" id="habitaciones" name="habitaciones" min="0" step="1">
            </p>
            <p><label for="banos">Número de baños</label>
                <input type="number" id="banos" name="banos" min="0" step="1">
            </p>
            <p><label for="planta">Planta</label>
                <input type="number" id="planta" name="planta" step="1">
            </p>
            <p><label for="anio">Año de construcción</label>
                <input type="number" id="anio" name="anio" min="1800" max="2100" step="1">
            </p>
        </fieldset>
        <p><button type="submit"><i class="fa-solid fa-circle-plus" aria-hidden="true"></i>Crear anuncio</button></p>
    </form>
</main>
<?php require_once 'includes/pie.php'; ?>