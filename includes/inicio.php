<?php
/*
 * PLANTILLA 2 - Inicio del cuerpo: <body>, logotipo, nombre del sitio y barra de navegación.
 */
$zona = $zona ?? 'publica';
$paginaActual = basename($_SERVER['PHP_SELF']);

// Enlaces de la barra de navegación: fichero => [icono, texto]
if ($zona === 'privada') {
    $enlacesNav = [
        'index-autenticado.php' => ['fa-house', 'Inicio'],
        'busqueda.php'          => ['fa-magnifying-glass', 'Buscar'],
        'menu-usuario.php'      => ['fa-circle-user', 'Mi cuenta'],
        'index.php'             => ['fa-right-from-bracket', 'Cerrar sesión'],
    ];
    $navInicio = 'index-autenticado.php';
} else {
    $enlacesNav = [
        'index.php'    => ['fa-house', 'Inicio'],
        'busqueda.php' => ['fa-magnifying-glass', 'Buscar'],
        'registro.php' => ['fa-user-plus', 'Registrarse'],
    ];
    $navInicio = 'index.php';
}
unset($enlacesNav[$paginaActual]);
$navUsuario = $zona === 'privada' ? leer($_GET, 'usuario') : '';
?>
<body>
<a class="saltar" href="#contenido">Saltar al contenido principal</a>
<header>
<p>
<img src="img/Logo Nexocasa.png" alt="">
<a href="<?= $navInicio ?>">Nexocasa</a>
</p>
<nav>
<ul>
<?php foreach ($enlacesNav as $navHref => [$navIcono, $navTexto]): ?>
<li><a href="<?= $navHref ?>"><i class="fa-solid <?= $navIcono ?>" aria-hidden="true"></i><?= $navTexto ?></a></li>
<?php endforeach; ?>
</ul>
</nav>
<?php if ($navUsuario !== ''): ?>
<p>Hola, <strong><?= e($navUsuario) ?></strong></p>
<?php endif; ?>
</header>
