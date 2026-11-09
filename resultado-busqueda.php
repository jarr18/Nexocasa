<?php
/*
 * Resultado de una búsqueda. Se llega desde la búsqueda rápida (solo envía "ciudad")
 * y desde busqueda.php (envía todos los campos). Los datos llegan por GET.
 * Muestra los criterios recibidos y, de momento, un listado de ejemplo (datos estáticos).
 */
$titulo = 'Resultado de la búsqueda';
require_once 'includes/cabecera.php';
require_once 'includes/inicio.php';
require_once 'includes/anuncios.php';

// Criterio => texto que se muestra. Solo se añaden los criterios que el usuario ha rellenado.
$criterios = [];
$tipoAnuncio = leer($_GET, 'tipo-anuncio');
$tipoVivienda = leer($_GET, 'tipo-vivienda');
$ciudad = leer($_GET, 'ciudad');
$pais = leer($_GET, 'pais');
$precioDesde = leer($_GET, 'precio-desde');
$precioHasta = leer($_GET, 'precio-hasta');
$fechaDesde = leer($_GET, 'fecha-desde');
$fechaHasta = leer($_GET, 'fecha-hasta');

if ($tipoAnuncio !== '')
    $criterios['Tipo de anuncio'] = etiqueta(TIPOS_ANUNCIO, $tipoAnuncio);
if ($tipoVivienda !== '')
    $criterios['Tipo de vivienda'] = etiqueta(TIPOS_VIVIENDA, $tipoVivienda);
if ($ciudad !== '')
    $criterios['Ciudad'] = $ciudad;
if ($pais !== '')
    $criterios['País'] = etiqueta(PAISES, $pais);
if ($precioDesde !== '' || $precioHasta !== '') {
    $criterios['Precio'] = ($precioDesde !== '' ? "desde $precioDesde €" : '')
        . ($precioDesde !== '' && $precioHasta !== '' ? ' ' : '')
        . ($precioHasta !== '' ? "hasta $precioHasta €" : '');
}
if ($fechaDesde !== '' || $fechaHasta !== '') {
    $criterios['Fecha de publicación'] = ($fechaDesde !== '' ? 'desde el ' . formatearFecha($fechaDesde) : '')
        . ($fechaDesde !== '' && $fechaHasta !== '' ? ' ' : '')
        . ($fechaHasta !== '' ? 'hasta el ' . formatearFecha($fechaHasta) : '');
}

// Resultados de ejemplo (datos estáticos hasta que haya base de datos)
$resultados = array_slice(require 'datos/ultimos-anuncios.php', 0, 4);
?>
<main id="contenido">
    <h1>Resultado de la búsqueda</h1>
    <section class="conBorde">
        <h2>Criterios de búsqueda</h2>
        <?php if ($criterios): ?>
            <dl>
                <?php foreach ($criterios as $nombre => $valor): ?>
                    <dt><?= e($nombre) ?></dt>
                    <dd><?= e($valor) ?></dd>
                <?php endforeach; ?>
            </dl>
        <?php else: ?>
            <p>No has indicado ningún criterio: se muestran todos los anuncios.</p>
        <?php endif; ?>
        <p><a href="busqueda.php"><i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>Hacer otra búsqueda</a>
        </p>
    </section>
    <h2>Anuncios encontrados (ejemplo)</h2>
    <?php listaAnuncios($resultados, 'detalle-anuncio.php', 'h3'); ?>
</main>
<?php require_once 'includes/pie.php'; ?>