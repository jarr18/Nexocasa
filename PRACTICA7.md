# Práctica 7 — Servidor web y PHP (formularios)

Este documento explica **dónde está** y **cómo funciona** cada cosa que pide el enunciado de la práctica 7.
Los números de línea son orientativos (`fichero.php:NN`).

---

## 1. Cómo ponerlo en marcha

1. Copia la carpeta `Nexocasa` dentro de `C:\xampp\htdocs\` (o crea un `Alias` en `httpd.conf`, apartado 4.2 del enunciado).
2. Arranca **Apache** desde `xampp-control.exe`.
3. Abre en el navegador **`http://localhost/Nexocasa/`**: se carga `index.php` automáticamente.

> Las páginas **no funcionan** si se abren con doble clic (`file:///...`): el código PHP tiene que pasar por el servidor.

> **Importante:** si ya tenías la versión antigua en `htdocs`, **borra la carpeta entera antes de copiar la nueva**.
> Si quedan los `.html` antiguos, Apache abre `index.html` antes que `index.php` y se ve la web vieja
> (por ejemplo, siempre sale «Hola, mlopez23», que estaba escrito a mano en `index-autenticado.html`).
> Para evitarlo, el fichero **`.htaccess`** de la raíz fija `DirectoryIndex index.php`.

**Usuarios para probar el acceso** (están en `datos/usuarios.php`):

| Usuario      | Contraseña   |
|--------------|--------------|
| `mlopez23`   | `Casa_2026`  |
| `paquito123` | `Piso_1234`  |
| `jgarcia10`  | `Garaje_99`  |
| `amartinez`  | `Atico_2026` |

---

## 2. Estructura de carpetas

```
Nexocasa/
├── index.php ... (todas las páginas, ahora .php)   ← una página = un fichero
├── includes/            ← trozos reutilizables (plantilla y funciones)
│   ├── cabecera.php        PLANTILLA 1/3: DOCTYPE, <head>, hojas de estilo
│   ├── inicio.php          PLANTILLA 2/3: <body>, logotipo y barra de navegación
│   ├── pie.php             PLANTILLA 3/3: <footer>, scripts, </body></html>
│   ├── funciones.php       Funciones comunes: e(), leer(), redirigir(), formatos...
│   ├── listas.php          Listas de valores (países, tipos de vivienda, tipos de mensaje...)
│   ├── acceso.php          Bloque del formulario de login
│   ├── busqueda-rapida.php Bloque de la búsqueda rápida
│   ├── anuncios.php        Funciones para obtener y pintar anuncios
│   ├── tarifas.php         Tarifas del folleto y cálculo del coste
│   └── registro-validacion.php  Validación del registro (códigos de error)
├── datos/               ← ficheros que SOLO guardan datos (sustituyen a la futura base de datos)
│   ├── usuarios.php        Los 4 usuarios permitidos
│   ├── anuncios.php        Los 2 anuncios (par / impar)
│   └── ultimos-anuncios.php Listado de ejemplo para la portada y los resultados
├── css/  js/  img/      ← sin cambios respecto a las prácticas anteriores
└── PRACTICA7.md         ← este documento
```

Los antiguos `.html` se han borrado: cada uno tiene ahora su `.php` equivalente con el mismo nombre.

**¿Por qué los includes son `.php` y no `.inc`?** (pregunta del enunciado, apartado 5). Si alguien abre
`http://localhost/Nexocasa/datos/usuarios.php` en el navegador, Apache **ejecuta** el fichero y no
muestra nada. Con extensión `.inc`, Apache lo enviaría como texto plano y se verían las contraseñas.

---

## 3. Plantilla (dividir la página en varios ficheros)

**Dónde:** `includes/cabecera.php`, `includes/inicio.php`, `includes/pie.php`.

**Cómo funciona:** todas las páginas siguen el mismo esquema (por ejemplo `busqueda.php`):

```php
<?php
$titulo = 'Buscar inmuebles';          // se muestra en <title>
// $zona = 'privada';                  // opcional: cambia la barra de navegación
require_once 'includes/cabecera.php';  // DOCTYPE + <head>
require_once 'includes/inicio.php';    // <body> + <header> + <nav>
?>
<main id="contenido"> ... lo único que cambia entre páginas ... </main>
<?php require_once 'includes/pie.php'; ?>   // <footer> + </body></html>
```

- `cabecera.php` también incluye `funciones.php` y `listas.php`, así que todas las páginas los tienen disponibles.
- `inicio.php` pinta una barra de navegación distinta según `$zona`:
  - `'publica'`: Inicio · Buscar · Registrarse
  - `'privada'`: Inicio · Buscar · Mi cuenta · Cerrar sesión

  No enlaza a la página en la que ya estás (`unset($enlacesNav[$paginaActual])`).
- `pie.php` carga los `<script>` que indique la página en `$scripts`.

Antes, cada página repetía unas 30 líneas de `<head>` y `<header>`, y algunas ni siquiera tenían todos los estilos alternativos. Ahora un cambio en la plantilla afecta a todo el sitio.

---

## 4. Funciones comunes y seguridad

**Dónde:** `includes/funciones.php`

| Función | Línea | Para qué sirve |
|---|---|---|
| `e($valor)` | 24 | Escapa con `htmlspecialchars()` todo lo que se imprime y viene del usuario. **Evita XSS** (apartado 4.6). Se usa como `<?= e($dato) ?>`. |
| `leer($_POST, 'campo')` | 38 | Lee un dato recibido y le quita los espacios y tabuladores del principio y del final con `trim()`. Si no existe o no es texto, devuelve `''`, así que nunca da *undefined index*. |
| `leerId('id')` | 47 | Lee un número entero positivo de la URL con `filter_var(FILTER_VALIDATE_INT)`. Si no es válido, devuelve `null`. |
| `redirigir($pagina, $parametros)` | 59 | Redirección en el servidor con `header('Location: ...')`. Monta una URL **absoluta** con `HTTP_HOST` y `PHP_SELF`, como pide HTTP/1.1, añade los parámetros con `http_build_query()` y termina con `exit`. |
| `opciones()` / `radios()` | 106 / 122 | Generan `<option>` y botones de radio a partir de las listas de `listas.php`, y marcan el valor elegido. |
| `mostrarErrores()` | 140 | Pinta el recuadro rojo de errores. Reutiliza la clase `.resumen-errores` de la práctica 6. |

Al principio de `funciones.php` se activa la visualización de errores (`display_errors`, `error_reporting(E_ALL)`), que es la configuración de **desarrollo** del apartado 4.10.

### Desactivar la validación HTML y JavaScript

El enunciado pide desactivarla para poder comprobar la de PHP.

**Dónde:** `includes/funciones.php:16` → `const VALIDACION_CLIENTE = false;`

- Con `false`, los formularios que valida PHP (login, registro y enviar mensaje) llevan el atributo `novalidate`. Lo añade la función `novalidate()`.
- Además, `pie.php:10` **no carga** `login.js` ni `registro.js`.
- Si se pone `true`, vuelve a funcionar la validación de las prácticas anteriores. Los ficheros JS **no se han borrado**.

---

## 5. Páginas modificadas

### 5.1 «Solicitar folleto»: tabla de costes con PHP
**Dónde:** `solicitar-folleto.php` (tabla en la línea 124) y `includes/tarifas.php`.

**Cómo funciona:**
- `TARIFAS` (`tarifas.php:8`) guarda las tarifas **en céntimos** para evitar errores de redondeo con decimales.
- `costePaginas()` (línea 22) aplica el **sistema de bloques**. Las páginas 1 a 4 se cobran a 2 €, las páginas 5 a 10 a 1,80 € y de la 11 en adelante a 1,60 €.
- `costeFolleto()` (línea 35) suma 10 € de procesamiento y envío, el coste de las páginas, 0,50 € por foto si es a color y 0,20 € por foto si la resolución supera los 300 dpi.
- `tablaCostes()` (línea 57) genera la tabla de la Figura 2 con dos bucles `for`/`foreach`. Las filas van de 1 a 15 páginas, con 3 fotos por página. Las columnas combinan blanco y negro o color con los rangos de 150-300 y 450-900 dpi.
- La tabla de **JavaScript** de la práctica 6 se ha **mantenido**, debajo de la de PHP, para comparar. Las dos dan los mismos importes; por ejemplo, 15 páginas a color y 450-900 dpi da 68,30 € en ambas.

### 5.2 Respuesta «Solicitar folleto»
**Dónde:** `respuesta-solicitar-folleto.php`

**Cómo funciona:**
- Recibe el formulario por **POST**. Si se abre directamente, sin enviar el formulario, redirige a `solicitar-folleto.php` (línea 15).
- **Valores ficticios** (líneas 19-20): `PAGINAS_FICTICIAS = 5` y `FOTOS_FICTICIAS = 15`, porque todavía no hay base de datos.
- Del formulario sí se usan el color o blanco y negro, la resolución y el número de copias. Se comprueba que sean valores válidos: de 1 a 99 copias y una resolución múltiplo de 150 entre 150 y 900. Si no lo son, se usa un valor por defecto.
- Coste total = `costeFolleto(...)` × número de copias (línea 42).
- Muestra todos los datos recibidos y una tabla con el desglose del coste.

### 5.3 Resultado de la búsqueda
**Dónde:** `resultado-busqueda.php`

**Cómo funciona:**
- Los datos llegan por **GET**, así que la búsqueda se puede guardar en favoritos (apartado 4.7). Llegan desde dos sitios:
  - la búsqueda rápida (`includes/busqueda-rapida.php`), que solo envía `ciudad`;
  - el formulario completo (`busqueda.php`).
- A partir de la línea 13 se monta el array `$criterios` solo con los campos que el usuario ha rellenado. Los valores internos se traducen a texto: por ejemplo, `espana` → «España», con `etiqueta()`.
- Muestra un recuadro «Criterios de búsqueda» con los datos reales y, debajo, el listado de ejemplo (estático) que ya tenía la página.

### 5.4 Detalle de anuncio (par / impar)
**Dónde:** `detalle-anuncio.php`, `includes/anuncios.php` y `datos/anuncios.php`

**Cómo funciona:**
- Se llama como `detalle-anuncio.php?id=N`. Los enlaces de la portada y de los resultados ya llevan el `id`.
- `obtenerAnuncio()` (`anuncios.php:13`) carga `datos/anuncios.php` y devuelve:
  - el anuncio `'par'` (ático en Barcelona) si `N % 2 === 0`;
  - el anuncio `'impar'` (chalet en Madrid) en otro caso (línea 19).
- `datos/anuncios.php` **solo guarda los datos** de los dos anuncios. Incluye el tipo de anuncio, el tipo de vivienda, la foto principal, el título, el precio, el texto, la fecha, la ciudad, el país, las 5 características que pide la nota 8, las miniaturas y el usuario.
- `fichaAnuncio()` (`anuncios.php:75`) pinta la ficha. La misma función sirve para «Ver anuncio».
- Si el `id` falta o no es un número (`?id=abc`), se devuelve el código **404** y el mensaje «Anuncio no encontrado».

### 5.5 Respuesta «Enviar mensaje»
**Dónde:** `respuesta-enviar-mensaje.php` (formulario en `enviar-mensaje.php`)

**Cómo funciona:**
- Recibe los datos por POST.
- Línea 19: comprueba que el tipo de mensaje esté en la lista `TIPOS_MENSAJE`, con `array_key_exists`. Si alguien manipula el valor, da error.
- Línea 22: comprueba que el texto no esté vacío. Después de `trim()`, un texto con solo espacios cuenta como vacío.
- Si hay errores, muestra la lista y un enlace para volver al formulario. Si no, muestra el tipo y el texto, escapados con `e()`.
- En el formulario se ha quitado el `checked` por defecto del tipo de mensaje para poder probar el error.

---

## 6. Páginas nuevas

### 6.1 Control de acceso (no visible)
**Dónde:** `control-acceso.php`. El formulario está en `includes/acceso.php` y se muestra en `index.php`.

**Cómo funciona:** no genera HTML, solo valida y redirige.
1. Lee el usuario y la contraseña con `leer()`, que aplica `trim()`. Si solo se han escrito espacios o tabuladores, el campo queda vacío.
2. **Línea 20:** si algún campo está vacío, redirige a `index.php?error=vacio`.
3. **Línea 26:** carga `datos/usuarios.php`, el fichero con **solo** los 4 usuarios, y comprueba el usuario y la contraseña. Si no coinciden, redirige a `index.php?error=incorrecto`.
4. **Línea 31:** si todo es correcto, redirige a `menu-usuario.php?usuario=...`.
5. Al llegar al menú, `includes/inicio.php` lee `?usuario=` y muestra **«Hola, *usuario*»** en la cabecera, siempre con el nombre del usuario que ha entrado. Sin sesiones, el nombre solo llega a esa página; en la práctica 8 se guardará en la sesión.
6. En `includes/acceso.php:11` se lee `?error=` y se muestra el mensaje correspondiente. **Solo viaja el código del error**, no el nombre de usuario ni la contraseña: el enunciado pide pasar por la URL la información que identifica el error, y no hace falta exponer más datos.

El error viaja **por la URL**, sin sesiones ni cookies, como exige el enunciado.

### 6.2 «Mis anuncios»
**Dónde:** `mis-anuncios.php`

Es una tabla con la foto en miniatura, el título, la ciudad, el país y el precio de cada anuncio del usuario. Cada fila enlaza a «Ver» (`ver-anuncio.php?id=N`) y a «Añadir foto» (`anadir-foto.php?anuncio=N`). Los anuncios salen de `anunciosDelUsuario()` (`anuncios.php:23`).

### 6.3 «Ver anuncio»
**Dónde:** `ver-anuncio.php`

Es igual que el detalle: llama a `fichaAnuncio($anuncio, true)`. Con `true`, en lugar del anunciante muestra el enlace **«Añadir una foto a este anuncio»**, que lleva a `anadir-foto.php?anuncio=N`.

### 6.4 «Añadir foto a anuncio»
**Dónde:** `anadir-foto.php`

**Cómo funciona:**
- Los campos son: foto, texto alternativo (obligatorio, mínimo 10 caracteres con `minlength="10"`), título y anuncio.
- **Desde el menú** (`anadir-foto.php`, sin parámetro): la lista desplegable aparece con «(Elige un anuncio)», sin ninguno seleccionado.
- **Desde «Mis anuncios» o «Ver anuncio»** (`anadir-foto.php?anuncio=N`): la línea 17 obtiene el anuncio. Entonces:
  - el `<select>` se marca como **`disabled`** (línea 27), así que no se puede cambiar;
  - el valor se envía en un `<input type="hidden">` (línea 30), porque un `<select disabled>` **no se envía** con el formulario.
- La subida de la foto y la página de respuesta no se piden en esta práctica. Por eso, de momento, el formulario apunta a `error-404.php`.

### 6.5 «Crear anuncio»
**Dónde:** `crear-anuncio.php`

Es un formulario con todos los datos de un anuncio: tipo de anuncio, tipo de vivienda, título, precio, texto, ciudad, país y las características (superficie, habitaciones, baños, planta y año). La respuesta se hará en otra práctica; de momento el formulario envía a `error-404.php`.

### 6.6 Respuesta del registro (con vuelta al formulario)
**Dónde:** `respuesta-registro.php`, `registro.php` e `includes/registro-validacion.php`

Se ha implementado la **estrategia 2**, la recomendada: si hay un error, se redirige al formulario de registro.
1. `registro.php` envía el formulario por POST a `respuesta-registro.php`.
2. `validarRegistro()` (`registro-validacion.php:18`) comprueba lo que pide el enunciado:
   - que el usuario, la contraseña y repetir contraseña no estén vacíos;
   - que la contraseña y repetir contraseña coincidan.
   Devuelve **códigos** de error: `usuario-vacio`, `password-vacia`, `repetir-vacia` y `no-coinciden`.
3. **Si hay errores** (`respuesta-registro.php:23`), redirige a:
   `registro.php?errores[0]=usuario-vacio&errores[1]=no-coinciden&email=...&sexo=...`
4. `registro.php` lee esos parámetros:
   - Línea 16: lee los códigos de error. Solo acepta los que están en `ERRORES_REGISTRO`.
   - Línea 56: muestra un **resumen con todos los errores** al principio.
   - Además, pinta **un mensaje junto a cada campo erróneo**, que se marca en rojo con `aria-invalid`.
   - Línea 29: **rellena cada campo** con lo que había escrito el usuario.
5. **Si todo es correcto:** muestra los datos recibidos. Como pide el enunciado, **no** muestra la foto.

**Decisión de seguridad:** las **contraseñas no se devuelven en la URL**. Quedarían en el historial del navegador y en los registros del servidor. El formulario avisa al usuario de que tiene que volver a escribirlas.

---

## 7. Otras páginas convertidas a PHP

Estas páginas solo se han pasado a la plantilla, sin lógica nueva: `index-autenticado.php`, `menu-usuario.php` (con las nuevas opciones del menú), `mis-mensajes.php`, `busqueda.php`, `enviar-mensaje.php`, `accesibilidad.php`, `aviso-no-registrado.php` y `error-404.php` (devuelve el código HTTP 404).

Los listados de anuncios de la portada y de los resultados se generan con `listaAnuncios()` (`anuncios.php:49`) a partir de `datos/ultimos-anuncios.php`. Ya no se repite el HTML de cada tarjeta.

---

## 8. Cómo comprobar cada punto

| Qué probar | Qué hacer | Resultado esperado |
|---|---|---|
| Login vacío | Escribir solo espacios o tabuladores y pulsar «Iniciar sesión» | Vuelve a la portada con «Debes escribir tu nombre de usuario y tu contraseña» |
| Login incorrecto | `mlopez23` / `mala` | Vuelve a la portada con «El nombre de usuario o la contraseña no son correctos» |
| Login correcto | `mlopez23` / `Casa_2026` (y después `amartinez` / `Atico_2026`) | Menú de usuario con «Hola, mlopez23» (o «Hola, amartinez») en la cabecera |
| Registro con errores | Dejar el usuario vacío y poner contraseñas distintas | Vuelve al formulario con 2 errores y el resto de datos rellenados |
| Registro correcto | Rellenar el usuario y dos contraseñas iguales | Página con los datos introducidos |
| Detalle par / impar | `detalle-anuncio.php?id=1` y `?id=2` | Chalet de Madrid / Ático de Barcelona |
| Mensaje sin texto | Enviar un mensaje vacío o sin tipo | Lista de errores y enlace para volver |
| Búsqueda | Búsqueda rápida «Alicante» | «Criterios de búsqueda: Ciudad: Alicante» |
| Folleto | Comparar la tabla PHP con la tabla JS | Mismos importes |
| Respuesta folleto | 3 copias, color, 600 dpi | Un folleto 30,30 € → total 90,90 € |
| Añadir foto | Desde el menú / desde «Mis anuncios» | Lista libre / anuncio fijo y desactivado |
| XSS | Escribir `<script>alert(1)</script>` en un mensaje | Se muestra como texto, no se ejecuta |

---

## 9. Cambios del compañero incorporados (después de la práctica 6)

- `js/folleto.js`: la tabla de costes de JavaScript se muestra y oculta con el atributo `hidden` (no depende de ninguna hoja de estilos). En `solicitar-folleto.php` el contenedor `#contenedor-costes` empieza con `hidden`.
- `js/interfaz.js`: corrige que el clic en un botón se perdiera cuando, al salir de un campo, aparecía su mensaje de error y desplazaba el botón.

## 10. Lo que queda para prácticas posteriores

- **Sesiones** (práctica 8): ahora la parte privada no está protegida y el nombre de usuario solo se pasa por la URL al menú. No se usan sesiones porque el enunciado lo prohíbe en esta práctica.
- **Base de datos:** los ficheros de `datos/` se sustituirán por consultas.
- **Subida de ficheros:** la foto de perfil y la foto del anuncio.
- **Miniframework** (apartado 3.2, opcional): no se ha implementado. Se ha usado la técnica de la plantilla del apartado 4.4.
