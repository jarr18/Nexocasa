<?php
/*
 * Funciones para obtener y mostrar anuncios. Las usan:
 *   index.php, index-autenticado.php, resultado-busqueda.php  → listaAnuncios()
 *   detalle-anuncio.php, ver-anuncio.php                       → obtenerAnuncio() + fichaAnuncio()
 *   mis-anuncios.php, anadir-foto.php, solicitar-folleto.php   → anunciosDelUsuario()
 */

/**
 * Devuelve el anuncio que corresponde a un identificador: el anuncio "par" si el número es par
 * y el anuncio "impar" si es impar. Devuelve null si el identificador no es válido.
 */
function obtenerAnuncio(?int $id): ?array
{
    if ($id === null) {
        return null;
    }
    $anuncios = require __DIR__ . '/../datos/anuncios.php';
    return $id % 2 === 0 ? $anuncios['par'] : $anuncios['impar'];
}

/** Anuncios del usuario (de momento, los dos anuncios de ejemplo), indexados por su id. */
function anunciosDelUsuario(): array
{
    $lista = [];
    foreach (require __DIR__ . '/../datos/anuncios.php' as $anuncio) {
        $lista[$anuncio['id']] = $anuncio;
    }
    ksort($lista);
    return $lista;
}

/** Lista de anuncios para los <select> (id => "Título (Ciudad)"). */
function opcionesAnuncios(): array
{
    $opciones = [];
    foreach (anunciosDelUsuario() as $id => $anuncio) {
        $opciones[$id] = "{$anuncio['titulo']} ({$anuncio['ciudad']})";
    }
    return $opciones;
}

/**
 * Muestra un listado de anuncios en tarjetas (.anuncio).
 *   $anuncios   Array de anuncios (ver datos/ultimos-anuncios.php).
 *   $pagina     Página a la que enlaza cada anuncio (se le añade ?id=N).
 *   $nivel      Etiqueta del título de cada tarjeta (h2 o h3, según la jerarquía de la página).
 */
function listaAnuncios(array $anuncios, string $pagina = 'detalle-anuncio.php', string $nivel = 'h3'): void
{
    echo "<ul>\n";
    foreach ($anuncios as $a) {
        $href = $pagina . '?id=' . (int) $a['id'];
        $alt = $a['alt'] ?? $a['altFoto'] ?? $a['titulo'];
        ?>
<li>
<article class="anuncio">
<p><img src="<?= e($a['foto']) ?>" alt="<?= e($alt) ?>" width="200"></p>
<<?= $nivel ?>><a href="<?= e($href) ?>"><?= e($a['titulo']) ?></a></<?= $nivel ?>>
<p><i class="fa-solid fa-location-dot" aria-hidden="true"></i><?= e($a['ciudad']) ?>, <?= e(etiqueta(PAISES, $a['pais'])) ?></p>
<p><i class="fa-regular fa-calendar" aria-hidden="true"></i>Publicado el <time datetime="<?= e($a['fecha']) ?>"><?= e(formatearFecha($a['fecha'])) ?></time></p>
<p><?= formatearPrecio($a['precio']) ?></p>
</article>
</li>
<?php
    }
    echo "</ul>\n";
}

/**
 * Muestra toda la información de un anuncio (columna principal + columna de características).
 *   $privada  false → "Detalle anuncio" (parte pública): muestra el anunciante y el enlace para enviarle un mensaje.
 *             true  → "Ver anuncio" (parte privada): muestra el enlace para añadir una foto al anuncio.
 */
function fichaAnuncio(array $a, bool $privada = false): void
{
    $c = $a['caracteristicas'];
    ?>
<article>
<p><?= e(etiqueta(TIPOS_ANUNCIO, $a['tipoAnuncio'])) ?> &middot; <?= e(etiqueta(TIPOS_VIVIENDA, $a['tipoVivienda'])) ?></p>
<h1><?= e($a['titulo']) ?></h1>
<p><img src="<?= e($a['foto']) ?>" alt="<?= e($a['altFoto']) ?>" width="400"></p>
<p><?= e($a['texto']) ?></p>
<p><i class="fa-regular fa-calendar" aria-hidden="true"></i>Publicado el <time datetime="<?= e($a['fecha']) ?>"><?= e(formatearFecha($a['fecha'])) ?></time></p>
<p><i class="fa-solid fa-location-dot" aria-hidden="true"></i><?= e($a['ciudad']) ?>, <?= e(etiqueta(PAISES, $a['pais'])) ?></p>
<p><?= formatearPrecio($a['precio']) ?><?= $a['tipoAnuncio'] === 'alquiler' ? ' al mes' : '' ?></p>
<h2>Fotos</h2>
<ul>
<?php foreach ($a['fotos'] as $foto): ?>
<li><img src="<?= e($foto['src']) ?>" alt="<?= e($foto['alt']) ?>" width="120"></li>
<?php endforeach; ?>
</ul>
</article>
<aside>
<h2>Características</h2>
<table>
<thead>
<tr><th scope="col">Característica</th><th scope="col">Valor</th></tr>
</thead>
<tbody>
<tr><th scope="row">Superficie</th><td><?= (int) $c['superficie'] ?> m&sup2;</td></tr>
<tr><th scope="row">Número de habitaciones</th><td><?= (int) $c['habitaciones'] ?></td></tr>
<tr><th scope="row">Número de baños</th><td><?= (int) $c['banos'] ?></td></tr>
<tr><th scope="row">Planta</th><td><?= (int) $c['planta'] ?></td></tr>
<tr><th scope="row">Año de construcción</th><td><?= (int) $c['anio'] ?></td></tr>
</tbody>
</table>
<?php if ($privada): ?>
<h2>Gestión del anuncio</h2>
<p><a href="anadir-foto.php?anuncio=<?= (int) $a['id'] ?>"><i class="fa-solid fa-camera" aria-hidden="true"></i>Añadir una foto a este anuncio</a></p>
<p><a href="mis-anuncios.php"><i class="fa-solid fa-rectangle-list" aria-hidden="true"></i>Volver a mis anuncios</a></p>
<?php else: ?>
<h2>Anunciante</h2>
<p>Usuario: <?= e($a['usuario']) ?></p>
<p><a href="enviar-mensaje.php?anuncio=<?= (int) $a['id'] ?>">Enviar mensaje al anunciante</a></p>
<?php endif; ?>
</aside>
<?php
}
