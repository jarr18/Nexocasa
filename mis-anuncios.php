<?php
/* "Mis anuncios" (parte privada): listado de los anuncios del usuario con foto, título, ciudad, país y precio. */
$titulo = 'Mis anuncios';
$zona = 'privada';
require_once 'includes/cabecera.php';
require_once 'includes/inicio.php';
require_once 'includes/anuncios.php';
$anuncios = anunciosDelUsuario();
?>
<main id="contenido">
<h1>Mis anuncios</h1>
<p><a href="crear-anuncio.php"><i class="fa-solid fa-circle-plus" aria-hidden="true"></i>Crear un anuncio nuevo</a></p>
<?php if (!$anuncios): ?>
<p>Todavía no has publicado ningún anuncio.</p>
<?php else: ?>
<table>
<caption>Anuncios publicados (<?= count($anuncios) ?>)</caption>
<thead>
<tr>
<th scope="col">Foto</th>
<th scope="col">Título</th>
<th scope="col">Ciudad</th>
<th scope="col">País</th>
<th scope="col">Precio</th>
<th scope="col">Acciones</th>
</tr>
</thead>
<tbody>
<?php foreach ($anuncios as $id => $a): ?>
<tr>
<td><img src="<?= e($a['foto']) ?>" alt="<?= e($a['altFoto']) ?>" width="100"></td>
<th scope="row"><a href="ver-anuncio.php?id=<?= $id ?>"><?= e($a['titulo']) ?></a></th>
<td><?= e($a['ciudad']) ?></td>
<td><?= e(etiqueta(PAISES, $a['pais'])) ?></td>
<td><?= formatearPrecio($a['precio']) ?></td>
<td>
<a href="ver-anuncio.php?id=<?= $id ?>">Ver</a> ·
<a href="anadir-foto.php?anuncio=<?= $id ?>">Añadir foto</a>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<?php endif; ?>
</main>
<?php require_once 'includes/pie.php'; ?>
