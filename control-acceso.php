<?php
/*
 * Control de acceso a la parte privada. NO es una página visible: no genera HTML, solo redirige.
 *
 * 1. Comprueba que se han escrito el usuario y la contraseña (trim() quita espacios y tabuladores,
 *    así que un campo con solo espacios o tabuladores cuenta como vacío).
 * 2. Comprueba que el usuario y la contraseña están en datos/usuarios.php (4 usuarios permitidos).
 * 3. Si todo es correcto → redirige a menu-usuario.php.
 *    Si no → redirige a index.php con un código de error en la URL (?error=vacio o ?error=incorrecto).
 */
require_once 'includes/funciones.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirigir('index.php');
}

$usuario = leer($_POST, 'usuario');
$password = leer($_POST, 'password');

if ($usuario === '' || $password === '') {
    redirigir('index.php', ['error' => 'vacio']);
}

$usuarios = require 'datos/usuarios.php';

if (!isset($usuarios[$usuario]) || $usuarios[$usuario] !== $password) {
    redirigir('index.php', ['error' => 'incorrecto']);
}

// Acceso correcto. Todavía no hay sesiones (práctica 8): el nombre se pasa por la URL para saludar al usuario.
redirigir('menu-usuario.php', ['usuario' => $usuario]);
