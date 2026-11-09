<?php
/* Menú del usuario registrado (parte privada). Se llega aquí desde control-acceso.php tras un acceso correcto. */
$titulo = 'Menú de usuario';
$zona = 'privada';
require_once 'includes/cabecera.php';
require_once 'includes/inicio.php';
$usuario = leer($_GET, 'usuario');

$opciones = [
    'error-404.php' => ['fa-user-pen', 'Modificar mis datos'],
    'error-404.php?baja' => ['fa-user-minus', 'Darme de baja'],
    'mis-anuncios.php' => ['fa-rectangle-list', 'Ver mis anuncios'],
    'crear-anuncio.php' => ['fa-circle-plus', 'Crear anuncio nuevo'],
    'anadir-foto.php' => ['fa-camera', 'Añadir foto a anuncio'],
    'mis-mensajes.php' => ['fa-envelope', 'Mis mensajes'],
    'solicitar-folleto.php' => ['fa-print', 'Solicitar folleto publicitario'],
    'index.php' => ['fa-right-from-bracket', 'Salir'],
];
?>
<main id="contenido">
    <h1>Menú de usuario registrado</h1>
    <?php if ($usuario !== ''): ?>
        <p>Has iniciado sesión correctamente como <strong><?= e($usuario) ?></strong>.</p>
    <?php endif; ?>
    <ul>
        <?php foreach ($opciones as $href => [$icono, $texto]): ?>
            <li><a href="<?= e($href) ?>"><i class="fa-solid <?= $icono ?>" aria-hidden="true"></i><?= $texto ?></a></li>
        <?php endforeach; ?>
    </ul>
</main>
<?php require_once 'includes/pie.php'; ?>