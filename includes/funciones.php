<?php
/*
 * Funciones comunes a todo el sitio web.
 * Se incluye desde includes/cabecera.php, así que está disponible en todas las páginas.
 */

// ---------- Configuración ----------

// Entorno de desarrollo: mostrar todos los errores (en producción se pondría display_errors a 0)
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Validación en el cliente (HTML + JavaScript). En esta práctica está DESACTIVADA
// para poder comprobar las validaciones hechas con PHP. Pon true para volver a activarla.
const VALIDACION_CLIENTE = false;

// ---------- Seguridad ----------

/**
 * Escapa un valor para mostrarlo en HTML de forma segura (evita ataques XSS).
 * Se usa siempre que se imprime algo que venga del usuario: <?= e($valor) ?>
 */
function e($valor): string
{
    if ($valor === null) {
        return '';
    }
    return htmlspecialchars((string) $valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// ---------- Lectura de datos recibidos ----------

/**
 * Devuelve un dato de $_GET o $_POST como texto sin espacios ni tabuladores al principio/final.
 * Si no existe o no es texto (por ejemplo, un array manipulado), devuelve ''.
 */
function leer(array $origen, string $nombre): string
{
    $valor = $origen[$nombre] ?? '';
    return is_string($valor) ? trim($valor) : '';
}

/**
 * Lee un identificador numérico positivo de $_GET. Devuelve null si no es válido.
 */
function leerId(string $nombre = 'id'): ?int
{
    $id = filter_var($_GET[$nombre] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    return $id === false ? null : $id;
}

// ---------- Redirecciones ----------

/**
 * Redirige (en el servidor) a otra página del mismo directorio, con parámetros opcionales en la URL.
 * HTTP/1.1 exige una URL absoluta, por eso se construye con HTTP_HOST y PHP_SELF.
 */
function redirigir(string $pagina, array $parametros = []): void
{
    $host = $_SERVER['HTTP_HOST'];
    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $consulta = $parametros ? '?' . http_build_query($parametros) : '';
    header("Location: http://$host$uri/$pagina$consulta");
    exit;
}

// ---------- Formato ----------

function formatearPrecio(float $euros): string
{
    return number_format($euros, 0, ',', '.') . ' €';
}

function formatearEuros(float $euros): string
{
    return number_format($euros, 2, ',', '.') . ' €';
}

/** Convierte una fecha 'aaaa-mm-dd' en '20 de septiembre de 2026'. */
function formatearFecha(string $fecha): string
{
    $meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio',
              'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
    $partes = explode('-', $fecha);
    if (count($partes) !== 3 || !checkdate((int) $partes[1], (int) $partes[2], (int) $partes[0])) {
        return $fecha;
    }
    return (int) $partes[2] . ' de ' . $meses[(int) $partes[1] - 1] . ' de ' . $partes[0];
}

/** Devuelve la etiqueta de un valor de una lista (por ejemplo 'espana' → 'España'). */
function etiqueta(array $lista, string $valor, string $siVacio = '(no indicado)'): string
{
    if ($valor === '') {
        return $siVacio;
    }
    return $lista[$valor] ?? $valor;
}

// ---------- Generación de HTML ----------

/**
 * Genera las <option> de una lista desplegable a partir de un array valor => etiqueta.
 */
function opciones(array $lista, string $seleccionado = '', ?string $textoVacio = null): string
{
    $html = '';
    if ($textoVacio !== null) {
        $html .= '<option value="">' . e($textoVacio) . "</option>\n";
    }
    foreach ($lista as $valor => $texto) {
        $marca = ((string) $valor === $seleccionado) ? ' selected' : '';
        $html .= '<option value="' . e($valor) . '"' . $marca . '>' . e($texto) . "</option>\n";
    }
    return $html;
}

/**
 * Genera un grupo de botones de radio a partir de un array valor => etiqueta.
 */
function radios(string $nombre, array $lista, string $marcado = ''): string
{
    $html = '';
    foreach ($lista as $valor => $texto) {
        $marca = ((string) $valor === $marcado) ? ' checked' : '';
        $html .= '<label><input type="radio" name="' . e($nombre) . '" value="' . e($valor) . '"'
               . $marca . '> ' . e($texto) . "</label>\n";
    }
    return $html;
}

/** Atributo que desactiva la validación HTML de un formulario cuando la validación en cliente está apagada. */
function novalidate(): string
{
    return VALIDACION_CLIENTE ? '' : ' novalidate';
}

/** Muestra un recuadro con una lista de errores (usa los estilos .resumen-errores de la práctica 6). */
function mostrarErrores(array $errores, string $titulo = 'Hay errores en el formulario'): void
{
    if (!$errores) {
        return;
    }
    echo '<div class="resumen-errores" role="alert">', "\n";
    echo '<h3>', e($titulo), "</h3>\n<ul>\n";
    foreach ($errores as $error) {
        echo '<li>', e($error), "</li>\n";
    }
    echo "</ul>\n</div>\n";
}
