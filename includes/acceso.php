<?php

$mensajesAcceso = [
    'vacio' => 'Debes escribir tu nombre de usuario y tu contraseña (no valen solo espacios o tabuladores).',
    'incorrecto' => 'El nombre de usuario o la contraseña no son correctos.',
];
$errorAcceso = $mensajesAcceso[leer($_GET, 'error')] ?? null;
?>
<section class="conBorde">
    <h2>Acceder como usuario registrado</h2>
    <p>Los campos marcados con un asterisco (*) son obligatorios.</p>
    <form id="formulario-login" action="control-acceso.php" method="post" <?= novalidate() ?>>
        <?php if ($errorAcceso): ?>
            <?php mostrarErrores([$errorAcceso], 'No se ha podido iniciar sesión'); ?>
        <?php else: ?>
            <div class="resumen-errores oculto" role="alert" tabindex="-1"></div>
        <?php endif; ?>
        <p>
            <label for="usuario-login"><i class="fa-solid fa-user" aria-hidden="true"></i>Nombre de usuario
                *</label><br>
            <input type="text" id="usuario-login" name="usuario" autocomplete="username">
        </p>
        <p>
            <label for="password-login"><i class="fa-solid fa-lock" aria-hidden="true"></i>Contraseña *</label><br>
            <input type="password" id="password-login" name="password" autocomplete="current-password">
        </p>
        <p><button type="submit"><i class="fa-solid fa-right-to-bracket" aria-hidden="true"></i>Iniciar sesión</button>
        </p>
    </form>
</section>