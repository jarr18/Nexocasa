<?php
/*
 * Validación del formulario de registro, compartida por registro.php y respuesta-registro.php.
 * Cada error tiene un código corto que viaja por la URL (registro.php?errores[]=usuario-vacio...).
 */
const ERRORES_REGISTRO = [
    'usuario-vacio'   => ['campo' => 'usuario',          'texto' => 'Debes escribir un nombre de usuario.'],
    'password-vacia'  => ['campo' => 'password',         'texto' => 'Debes escribir una contraseña.'],
    'repetir-vacia'   => ['campo' => 'repetir-password', 'texto' => 'Debes repetir la contraseña.'],
    'no-coinciden'    => ['campo' => 'repetir-password', 'texto' => 'Las dos contraseñas no coinciden.'],
];

// Campos que se devuelven al formulario para rellenarlo de nuevo.
// Las contraseñas NO se devuelven: no deben viajar nunca en una URL (quedan en el historial y en los logs).
const CAMPOS_REGISTRO = ['usuario', 'email', 'sexo', 'fecha-nacimiento', 'ciudad', 'pais'];

/** Devuelve la lista de códigos de error para los datos recibidos. */
function validarRegistro(array $datos): array
{
    $errores = [];
    $usuario  = leer($datos, 'usuario');
    $password = leer($datos, 'password');
    $repetir  = leer($datos, 'repetir-password');

    if ($usuario === '') {
        $errores[] = 'usuario-vacio';
    }
    if ($password === '') {
        $errores[] = 'password-vacia';
    }
    if ($repetir === '') {
        $errores[] = 'repetir-vacia';
    } elseif ($password !== '' && $password !== $repetir) {
        $errores[] = 'no-coinciden';
    }
    return $errores;
}
