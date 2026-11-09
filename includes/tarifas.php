<?php
/*
 * Tarifas del folleto publicitario y cálculo de su coste (misma lógica que js/folleto.js).
 * Lo usan solicitar-folleto.php (tabla de posibles costes) y respuesta-solicitar-folleto.php (coste final).
 * Los importes se guardan en CÉNTIMOS (números enteros) para evitar errores de redondeo.
 */

const TARIFAS = [
    'procesamientoEnvio'    => 1000,   // 10 € por folleto
    // Sistema de bloques: cada página se cobra según el tramo en el que cae
    'tramos' => [
        ['hasta' => 4,         'porPagina' => 200],   // < 5 páginas: 2 €/pág.
        ['hasta' => 10,        'porPagina' => 180],   // de 5 a 10 páginas: 1,8 €/pág.
        ['hasta' => PHP_INT_MAX, 'porPagina' => 160], // > 10 páginas: 1,6 €/pág.
    ],
    'colorPorFoto'          => 50,     // 0,5 € por foto si es a color
    'resolucionAltaPorFoto' => 20,     // 0,2 € por foto si la resolución es > 300 dpi
    'limiteResolucionBaja'  => 300,    // hasta 300 dpi no hay recargo
];

/** Coste (en céntimos) de las páginas aplicando el sistema de bloques. */
function costePaginas(int $paginas): int
{
    $total = 0;
    $anterior = 0;
    foreach (TARIFAS['tramos'] as $tramo) {
        $enTramo = max(0, min($paginas, $tramo['hasta']) - $anterior);
        $total += $enTramo * $tramo['porPagina'];
        $anterior = $tramo['hasta'];
    }
    return $total;
}

/** Coste (en céntimos) de UN folleto. */
function costeFolleto(int $paginas, int $fotos, bool $color, int $dpi): int
{
    $total = TARIFAS['procesamientoEnvio'] + costePaginas($paginas);
    if ($color) {
        $total += $fotos * TARIFAS['colorPorFoto'];
    }
    if ($dpi > TARIFAS['limiteResolucionBaja']) {
        $total += $fotos * TARIFAS['resolucionAltaPorFoto'];
    }
    return $total;
}

/** Convierte céntimos en texto con formato español: 1234 → "12,34 €". */
function centimosAEuros(int $centimos): string
{
    return formatearEuros($centimos / 100);
}

/**
 * Genera la tabla de posibles costes (Figura 2 del enunciado) con PHP.
 * Filas: de 1 a 15 páginas (3 fotos por página). Columnas: B/N y color × dos rangos de resolución.
 */
function tablaCostes(int $maxPaginas = 15, int $fotosPorPagina = 3): void
{
    $rangos = [[150, 300], [450, 900]];
    $modos = ['Blanco y negro' => false, 'Color' => true];
    ?>
<table class="tabla-costes">
<caption>Posibles costes de un folleto de publicidad impreso (calculado con PHP)</caption>
<thead>
<tr>
<th scope="col" rowspan="2">Número de páginas</th>
<th scope="col" rowspan="2">Número de fotos</th>
<?php foreach ($modos as $nombre => $color): ?>
<th scope="colgroup" colspan="<?= count($rangos) ?>"><?= $nombre ?></th>
<?php endforeach; ?>
</tr>
<tr>
<?php foreach ($modos as $color): foreach ($rangos as [$min, $max]): ?>
<th scope="col"><?= $min ?>-<?= $max ?> dpi</th>
<?php endforeach; endforeach; ?>
</tr>
</thead>
<tbody>
<?php for ($paginas = 1; $paginas <= $maxPaginas; $paginas++): $fotos = $paginas * $fotosPorPagina; ?>
<tr>
<th scope="row"><?= $paginas ?></th>
<td><?= $fotos ?></td>
<?php foreach ($modos as $color): foreach ($rangos as [$min, $max]): ?>
<td><?= centimosAEuros(costeFolleto($paginas, $fotos, $color, $min)) ?></td>
<?php endforeach; endforeach; ?>
</tr>
<?php endfor; ?>
</tbody>
</table>
<?php
}
