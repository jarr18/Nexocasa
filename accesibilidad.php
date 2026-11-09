<?php
/* Declaración de accesibilidad (contenido estático). */
$titulo = 'Declaración de accesibilidad';
require_once 'includes/cabecera.php';
require_once 'includes/inicio.php';
?>
<main id="contenido">
<h1>Declaración de accesibilidad</h1>
<p>Nexocasa quiere que cualquier persona pueda buscar y consultar anuncios de viviendas, con independencia de
sus capacidades o del dispositivo que use. Esta declaración explica qué hemos hecho para lograrlo. Fecha de
la última revisión: <time datetime="2026-10-26">26 de octubre de 2026</time>.</p>

<section>
<h2>Etiquetado semántico</h2>
<p>Las páginas usan las etiquetas HTML según su significado: <code>header</code>, <code>nav</code>,
<code>main</code>, <code>section</code>, <code>article</code> y <code>footer</code> para la estructura;
<code>h1</code> a <code>h3</code> para los títulos, en orden jerárquico; <code>ul</code> para las listas
y <code>time</code> para las fechas. Cada página declara su idioma (<code>lang="es"</code>) y tiene un
único <code>h1</code>. Así, un lector de pantalla puede saltar entre regiones y títulos, y el contenido
tiene sentido incluso sin hojas de estilo.</p>
</section>

<section>
<h2>Texto alternativo de las imágenes</h2>
<p>Las fotografías de los anuncios llevan un texto alternativo que describe su contenido. Las imágenes
meramente decorativas, como el logotipo junto al nombre, tienen el atributo <code>alt</code> vacío para
que los lectores de pantalla las ignoren. Los iconos que acompañan a un texto están ocultos a las
tecnologías de apoyo (<code>aria-hidden="true"</code>) porque el texto ya transmite la información.</p>
</section>

<section>
<h2>Uso correcto de los colores</h2>
<p>Ninguna información se transmite solo mediante el color: los enlaces están subrayados en los estilos
accesibles y los iconos siempre van acompañados de texto. Las combinaciones de color del estilo
accesible cumplen el criterio 1.4.6 de las WCAG 2.1 (relación de contraste de al menos 7:1). Los
enlaces, los botones y los campos de formulario tienen un indicador de foco visible al navegar con el
teclado.</p>
</section>

<section>
<h2>Formularios y navegación con teclado</h2>
<p>Todos los campos de formulario tienen una etiqueta <code>label</code> asociada. Al principio de cada
página hay un enlace «Saltar al contenido principal» que permite omitir la cabecera. Toda la web se
puede recorrer con el teclado y la maquetación es adaptable, de modo que se puede ampliar el zoom sin
que el contenido se corte.</p>
</section>

<section id="estilos">
<h2>Cómo activar un estilo accesible</h2>
<p>La web ofrece cuatro estilos alternativos además del estilo principal:</p>
<ul>
<li><strong>Modo noche</strong>: fondo oscuro y texto gris claro, para ambientes con poca luz.</li>
<li><strong>Alto contraste</strong>: texto negro sobre fondo blanco, enlaces azules subrayados y
cabecera y pie azul marino.</li>
<li><strong>Letra grande</strong>: tamaño de texto un 50 % mayor, tipo de letra Atkinson Hyperlegible,
más interlineado y líneas de como máximo 80 caracteres.</li>
<li><strong>Alto contraste y letra grande</strong>: ambos estilos a la vez.</li>
</ul>
<p>Para elegirlo en el navegador:</p>
<ol>
<li>En Mozilla Firefox, pulsa la tecla <kbd>Alt</kbd> para mostrar la barra de menús.</li>
<li>Abre el menú <em>Ver</em> y después <em>Estilo de página</em>.</li>
<li>Selecciona el estilo que quieras. «Estilo principal» restablece el diseño original y «Sin estilo»
muestra solo el contenido.</li>
</ol>
<p>Los navegadores basados en Chromium, como Google Chrome, no incluyen esta opción. La selección tampoco se
conserva al cambiar de página; lo resolveremos en una futura mejora.</p>
</section>

<section>
<h2>Versión para impresión</h2>
<p>Al imprimir una página se aplica automáticamente un estilo propio: texto negro sobre blanco con tipo de
letra serif de 12 puntos, sin menús ni formularios, con la dirección de cada enlace entre paréntesis y
un aviso de derechos de uso al pie. Se mantienen las fotografías de los anuncios y se eliminan las
imágenes decorativas.</p>
</section>

<section>
<h2>Contenidos no accesibles y contacto</h2>
<p>Hacemos todo lo posible por que la web sea accesible, pero puede haber partes que todavía no lo sean. Si
encuentras algún problema, quieres hacer una sugerencia o necesitas la información en otro formato,
escríbenos a <a href="mailto:contacto@nexocasa.example">contacto@nexocasa.example</a> e intentaremos
responderte lo antes posible.</p>
</section>
</main>
<?php require_once 'includes/pie.php'; ?>
