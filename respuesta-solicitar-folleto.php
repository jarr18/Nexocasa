<?php
/*
 * Respuesta a la solicitud de un folleto. Recibe los datos por POST desde solicitar-folleto.php.
 * Muestra los datos recibidos y el coste final calculado con las tarifas (includes/tarifas.php):
 *   - Número de páginas y de fotos: valores FICTICIOS (todavía no hay base de datos de anuncios).
 *   - Color y resolución: los que ha elegido el usuario.
 *   - Coste total = coste de un folleto × número de copias.
 */
require_once 'includes/funciones.php';
require_once 'includes/listas.php';
require_once 'includes/anuncios.php';
require_once 'includes/tarifas.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirigir('solicitar-folleto.php');
}

// Valores ficticios del folleto (en una práctica posterior saldrán de la base de datos)
const PAGINAS_FICTICIAS = 5;
const FOTOS_FICTICIAS = 15;

// ---------- Datos recibidos ----------
$p = fn(string $campo) => leer($_POST, $campo);

$copias = filter_var($p('num-copias'), FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 99]]);
$copias = $copias === false ? 1 : $copias;

$dpi = filter_var($p('resolucion'), FILTER_VALIDATE_INT, ['options' => ['min_range' => 150, 'max_range' => 900]]);
$dpi = ($dpi === false || $dpi % 150 !== 0) ? 150 : $dpi;

$modo = $p('impresion-color');
$esColor = ($modo === 'color');

$colorPortada = preg_match('/^#[0-9a-fA-F]{6}$/', $p('color-portada')) ? $p('color-portada') : '#000000';

$anuncios = anunciosDelUsuario();
$idAnuncio = (int) $p('anuncio');
$anuncio = $anuncios[$idAnuncio] ?? null;

// ---------- Cálculo del coste ----------
$costeUnitario = costeFolleto(PAGINAS_FICTICIAS, FOTOS_FICTICIAS, $esColor, $dpi);
$costeTotal = $costeUnitario * $copias;

// ---------- Datos que se muestran ----------
$direccion = implode(', ', array_filter([
    trim($p('calle') . ' ' . $p('numero')),
    trim(($p('piso') !== '' ? $p('piso') . 'º ' : '') . $p('puerta')),
    $p('cp'),
    $p('localidad'),
    etiqueta(PROVINCIAS, $p('provincia'), ''),
    etiqueta(PAISES, $p('pais-envio'), ''),
], fn($parte) => $parte !== ''));

$datos = [
    'Texto adicional' => $p('texto-adicional'),
    'Nombre' => $p('nombre'),
    'Correo electrónico' => $p('email'),
    'Dirección' => $direccion,
    'Teléfono' => $p('telefono'),
    'Número de copias' => $copias,
    'Resolución de impresión' => "$dpi dpi",
    'Anuncio' => $anuncio ? "{$anuncio['titulo']} ({$anuncio['ciudad']})" : '',
    'Fecha de recepción' => $p('fecha-recepcion') !== '' ? formatearFecha($p('fecha-recepcion')) : '',
    'Impresión a color' => $modo !== '' ? etiqueta(MODOS_IMPRESION, $modo) : 'No indicado (se aplica blanco y negro)',
    'Impresión del precio' => etiqueta(SI_NO, $p('impresion-precio')),
];

$titulo = 'Solicitud de folleto registrada';
$zona = 'privada';
require_once 'includes/cabecera.php';
require_once 'includes/inicio.php';
?>
<main id="contenido">
    <h1>Solicitud de folleto publicitario registrada</h1>
    <p>Hemos recibido tu solicitud con los siguientes datos:</p>
    <dl class="conBorde">
        <?php foreach ($datos as $nombre => $valor): ?>
            <dt><?= e($nombre) ?></dt>
            <dd><?= $valor === '' ? '(no indicado)' : nl2br(e($valor)) ?></dd>
        <?php endforeach; ?>
        <dt>Color de la portada</dt>
        <dd><input type="color" value="<?= e($colorPortada) ?>" disabled aria-label="Color de la portada">
            (<?= e($colorPortada) ?>)</dd>
    </dl>

    <section class="conBorde">
        <h2>Coste del folleto</h2>
        <table>
            <caption>Desglose del coste</caption>
            <tr>
                <th scope="row">Número de páginas (valor de ejemplo)</th>
                <td><?= PAGINAS_FICTICIAS ?></td>
            </tr>
            <tr>
                <th scope="row">Número de fotos (valor de ejemplo)</th>
                <td><?= FOTOS_FICTICIAS ?></td>
            </tr>
            <tr>
                <th scope="row">Impresión</th>
                <td><?= $esColor ? 'Color' : 'Blanco y negro' ?>, <?= $dpi ?> dpi</td>
            </tr>
            <tr>
                <th scope="row">Coste de un folleto</th>
                <td><?= centimosAEuros($costeUnitario) ?></td>
            </tr>
            <tr>
                <th scope="row">Número de copias</th>
                <td>× <?= $copias ?></td>
            </tr>
            <tr>
                <th scope="row">Coste total</th>
                <td><strong><?= centimosAEuros($costeTotal) ?></strong></td>
            </tr>
        </table>
    </section>
</main>
<?php require_once 'includes/pie.php'; ?>