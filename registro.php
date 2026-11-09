<?php
/*
 * Formulario de registro como nuevo usuario. Envía los datos por POST a respuesta-registro.php.
 * Si hay errores, respuesta-registro.php redirige aquí con los errores y los datos en la URL:
 *   registro.php?errores[0]=usuario-vacio&usuario=...&email=...
 * Entonces se muestra un resumen de errores al principio, un mensaje junto a cada campo erróneo
 * y cada campo se rellena con lo que el usuario había escrito (salvo las contraseñas).
 */
$titulo = 'Registro de nuevo usuario';
require_once 'includes/cabecera.php';
require_once 'includes/inicio.php';
require_once 'includes/registro-validacion.php';
$scriptsValidacion = ['js/validaciones.js', 'js/interfaz.js', 'js/registro.js'];

// Errores recibidos por la URL (solo se aceptan los códigos conocidos)
$codigos = isset($_GET['errores']) && is_array($_GET['errores']) ? $_GET['errores'] : [];
$errores = [];      // campo => mensaje
$resumen = [];      // lista de mensajes
foreach ($codigos as $codigo) {
    if (is_string($codigo) && isset(ERRORES_REGISTRO[$codigo])) {
        $errores[ERRORES_REGISTRO[$codigo]['campo']] = ERRORES_REGISTRO[$codigo]['texto'];
        $resumen[] = ERRORES_REGISTRO[$codigo]['texto'];
    }
}

// Valores previos de los campos (vacíos la primera vez)
$v = [];
foreach (CAMPOS_REGISTRO as $campo) {
    $v[$campo] = leer($_GET, $campo);
}
if ($v['pais'] === '') {
    $v['pais'] = 'espana';
}

/** Atributos y mensaje de error de un campo, si tiene error. */
function claseError(array $errores, string $campo): string
{
    return isset($errores[$campo]) ? ' class="con-error"' : '';
}
function atributosError(array $errores, string $campo): string
{
    return isset($errores[$campo]) ? ' aria-invalid="true" aria-describedby="error-' . $campo . '"' : '';
}
function mensajeError(array $errores, string $campo): string
{
    return isset($errores[$campo])
        ? '<span class="mensaje-error" id="error-' . $campo . '">' . e($errores[$campo]) . '</span>'
        : '';
}
?>
<main id="contenido">
    <h1>Regístrate como nuevo usuario</h1>
    <p>Los campos marcados con un asterisco (*) son obligatorios.</p>
    <form id="formulario-registro" class="conBorde" action="respuesta-registro.php" method="post"
        enctype="multipart/form-data" <?= novalidate() ?>>
                <?php if ($resumen): ?>
                        <?php mostrarErrores($resumen, 'Corrige los siguientes errores'); ?>
                <?php else: ?>
            <div class="resumen-errores oculto" role="alert" tabindex="-1"></div>
                <?php endif; ?>

        <p<?= claseError($errores, 'usuario') ?>>
            <label for="usuario"><i class="fa-solid fa-user" aria-hidden="true"></i>Nombre de usuario *</label><br>
            <input type="text" id="usuario" name="usuario" autocomplete="username" value="<?= e($v['usuario']) ?>"
                <?= atributosError($errores, 'usuario') ?>>
            <small class="ayuda" id="ayuda-usuario">Entre 3 y 15 caracteres: letras del alfabeto inglés y números, sin
                empezar por un número. Ejemplo: Maria2026</small>
                        <?= mensajeError($errores, 'usuario') ?>
            </p>

            <p<?= claseError($errores, 'password') ?>>
                <label for="password"><i class="fa-solid fa-lock" aria-hidden="true"></i>Contraseña *</label><br>
                <input type="password" id="password" name="password" autocomplete="new-password"
                    <?= atributosError($errores, 'password') ?>>
                <small class="ayuda" id="ayuda-password">Entre 6 y 15 caracteres: letras del alfabeto inglés, números,
                    guion (-) y guion bajo (_). Debe incluir al menos una mayúscula, una minúscula y un número. Ejemplo:
                    Casa_2026</small>
                                <?= mensajeError($errores, 'password') ?>
                </p>

                <p<?= claseError($errores, 'repetir-password') ?>>
                    <label for="repetir-password"><i class="fa-solid fa-lock" aria-hidden="true"></i>Repetir contraseña
                        *</label><br>
                    <input type="password" id="repetir-password" name="repetir-password" autocomplete="new-password"
                        <?= atributosError($errores, 'repetir-password') ?>>
                    <small class="ayuda" id="ayuda-repetir-password">Escribe de nuevo la misma
                        contraseña.<?= $resumen ? ' Por seguridad, las contraseñas no se guardan entre intentos: vuelve a escribirlas.' : '' ?></small>
                                        <?= mensajeError($errores, 'repetir-password') ?>
                    </p>


                    <p>
                        <label for="email">&#9993; Dirección de email *</label><br>
                        <input type="text" id="email" name="email" inputmode="email" autocomplete="email"
                            value="<?= e($v['email']) ?>">
                        <small class="ayuda" id="ayuda-email">Ejemplo: nombre.apellido@dominio.es</small>
                    </p>

                    <fieldset id="grupo-sexo">
                        <legend>Sexo *</legend>
                                                <?= radios('sexo', SEXOS, $v['sexo']) ?>
                    </fieldset>

                    <p>
                        <label for="fecha-nacimiento">Fecha de nacimiento *</label><br>
                        <input type="text" id="fecha-nacimiento" name="fecha-nacimiento" inputmode="numeric"
                            value="<?= e($v['fecha-nacimiento']) ?>">
                        <small class="ayuda" id="ayuda-fecha-nacimiento">Formato dd/mm/aaaa. Debes tener al menos 18
                            años cumplidos. Ejemplo: 25/03/1990</small>
                    </p>

                    <p>
                        <label for="ciudad">Ciudad de residencia</label><br>
                        <input type="text" id="ciudad" name="ciudad" value="<?= e($v['ciudad']) ?>">
                    </p>

                    <p>
                        <label for="pais">País de residencia</label><br>
                        <select id="pais" name="pais">
                                                        <?= opciones(PAISES, $v['pais']) ?>
                        </select>
                    </p>

                    <p>
                        <label for="foto">Foto</label><br>
                        <input type="file" id="foto" name="foto" accept="image/*">
                    </p>

                    <p><button type="submit"><i class="fa-solid fa-user-plus"
                                aria-hidden="true"></i>Registrarse</button></p>
    </form>
</main>
<?php require_once 'includes/pie.php'; ?>