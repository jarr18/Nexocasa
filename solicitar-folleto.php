<?php
/*
 * "Solicitar folleto" (parte privada): tabla de tarifas, formulario de solicitud y dos tablas de
 * posibles costes: una calculada con PHP (nueva en la práctica 7) y la de la práctica 6 hecha con
 * JavaScript, para comprobar que ambas dan los mismos importes.
 * El formulario envía los datos por POST a respuesta-solicitar-folleto.php.
 */
$titulo = 'Solicitar folleto publicitario';
$zona = 'privada';
require_once 'includes/cabecera.php';
require_once 'includes/inicio.php';
require_once 'includes/anuncios.php';
require_once 'includes/tarifas.php';
$scripts = ['js/folleto.js'];   // tabla de costes con JavaScript (práctica 6)
$idAnuncio = obtenerAnuncio(leerId('anuncio'))['id'] ?? '';
?>
<main id="contenido" class="columnas">
       <h1>Solicitud de impresión de folleto publicitario</h1>
       <p>
              Mediante esta opción puedes solicitar la impresión y el envío postal de un folleto publicitario
              de uno de tus anuncios. Rellena el formulario con tus datos de envío y las opciones de impresión
              que prefieras.
       </p>
       <p>Los campos marcados con un asterisco (*) son obligatorios.</p>

       <aside>
              <h2>Tarifas</h2>
              <table>
                     <caption>Tarifas del folleto publicitario</caption>
                     <tr>
                            <th scope="col">Concepto</th>
                            <th scope="col">Tarifa</th>
                     </tr>
                     <tr>
                            <th scope="row">Coste procesamiento y envío</th>
                            <td>10 €</td>
                     </tr>
                     <tr>
                            <th scope="row">&lt; 5 páginas</th>
                            <td>2 € por pág.</td>
                     </tr>
                     <tr>
                            <th scope="row">entre 5 y 10 páginas</th>
                            <td>1,8 € por pág.</td>
                     </tr>
                     <tr>
                            <th scope="row">&gt; 10 páginas</th>
                            <td>1,6 € por pág.</td>
                     </tr>
                     <tr>
                            <th scope="row">Blanco y negro</th>
                            <td>0 €</td>
                     </tr>
                     <tr>
                            <th scope="row">Color</th>
                            <td>0,5 € por foto</td>
                     </tr>
                     <tr>
                            <th scope="row">Resolución &lt;= 300 dpi</th>
                            <td>0 € por foto</td>
                     </tr>
                     <tr>
                            <th scope="row">Resolución &gt; 300 dpi</th>
                            <td>0,2 € por foto</td>
                     </tr>
              </table>
       </aside>

       <form class="conBorde" action="respuesta-solicitar-folleto.php" method="post">
              <h2>Formulario de solicitud</h2>
              <p>
                     <label for="texto-adicional">Texto adicional</label><br>
                     <textarea id="texto-adicional" name="texto-adicional" rows="4" cols="50"
                            maxlength="4000"></textarea>
              </p>
              <p>
                     <label for="nombre">Nombre *</label><br>
                     <input type="text" id="nombre" name="nombre" maxlength="200" required>
              </p>
              <p>
                     <label for="email">&#9993; Correo electrónico *</label><br>
                     <input type="email" id="email" name="email" maxlength="200" required>
              </p>
              <fieldset>
                     <legend>Dirección de envío *</legend>
                     <p><label for="calle">Calle</label> <input type="text" id="calle" name="calle" required></p>
                     <p><label for="numero">Número</label> <input type="text" id="numero" name="numero" required></p>
                     <p><label for="piso">Piso</label> <input type="text" id="piso" name="piso"></p>
                     <p><label for="puerta">Puerta</label> <input type="text" id="puerta" name="puerta"></p>
                     <p><label for="cp">Código postal</label> <input type="text" id="cp" name="cp" required></p>
                     <p><label for="localidad">Localidad</label> <input type="text" id="localidad" name="localidad"
                                   required></p>
                     <p>
                            <label for="provincia">Provincia</label>
                            <select id="provincia" name="provincia" required>
                                   <?= opciones(PROVINCIAS, 'madrid') ?>
                            </select>
                     </p>
                     <p>
                            <label for="pais-envio">País</label>
                            <select id="pais-envio" name="pais-envio" required>
                                   <?= opciones(PAISES, 'espana') ?>
                            </select>
                     </p>
              </fieldset>
              <p>
                     <label for="telefono">&#9742; Teléfono</label><br>
                     <input type="tel" id="telefono" name="telefono">
              </p>
              <p>
                     <label for="color-portada">Color de la portada *</label><br>
                     <input type="color" id="color-portada" name="color-portada" value="#000000">
              </p>
              <p>
                     <label for="num-copias">Número de copias *</label><br>
                     <input type="number" id="num-copias" name="num-copias" min="1" max="99" value="1" required>
              </p>
              <p>
                     <label for="resolucion">Resolución de impresión *</label><br>
                     <input type="range" id="resolucion" name="resolucion" min="150" max="900" step="150" value="150"
                            list="valores-resolucion" required
                            oninput="document.getElementById('salida-resolucion').value = this.value + ' dpi'">
                     <datalist id="valores-resolucion">
                            <option value="150">
                            <option value="300">
                            <option value="450">
                            <option value="600">
                            <option value="750">
                            <option value="900">
                     </datalist>
                     <output id="salida-resolucion" for="resolucion">150 dpi</output>
              </p>
              <p>
                     <label for="anuncio">Anuncio *</label><br>
                     <select id="anuncio" name="anuncio" required>
                            <?= opciones(opcionesAnuncios(), (string) $idAnuncio) ?>
                     </select>
              </p>
              <p>
                     <label for="fecha-recepcion">Fecha de recepción</label><br>
                     <input type="date" id="fecha-recepcion" name="fecha-recepcion">
              </p>
              <fieldset>
                     <legend>¿Impresión a color?</legend>
                     <?= radios('impresion-color', MODOS_IMPRESION) ?>
              </fieldset>
              <fieldset>
                     <legend>¿Impresión del precio?</legend>
                     <?= radios('impresion-precio', SI_NO) ?>
              </fieldset>
              <p><button type="submit"><i class="fa-solid fa-print" aria-hidden="true"></i>Solicitar</button></p>
       </form>

       <section class="conBorde" aria-labelledby="titulo-costes-php">
              <h2 id="titulo-costes-php">Posibles costes de un folleto (PHP)</h2>
              <p>Tabla calculada en el servidor con PHP aplicando las tarifas (se supone una media de 3 fotos por
                     página).</p>
              <div class="tabla-desplazable">
                     <?php tablaCostes(); ?>
              </div>
       </section>

       <section class="conBorde" aria-labelledby="titulo-costes">
              <h2 id="titulo-costes">Posibles costes de un folleto (JavaScript)</h2>
              <p>La misma tabla calculada en el navegador con JavaScript (práctica 6), para comparar los resultados.</p>
              <p>
                     <button type="button" id="boton-costes" aria-expanded="false" aria-controls="contenedor-costes"><i
                                   class="fa-solid fa-table" aria-hidden="true"></i><span
                                   id="texto-boton-costes">Mostrar tabla de costes</span></button>
              </p>
              <div id="contenedor-costes" class="tabla-desplazable" hidden></div>
       </section>
</main>
<?php require_once 'includes/pie.php'; ?>