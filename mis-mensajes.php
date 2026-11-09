<?php
/* Mis mensajes (parte privada). De momento muestra mensajes de ejemplo. */
$titulo = 'Mis mensajes';
$zona = 'privada';
require_once 'includes/cabecera.php';
require_once 'includes/inicio.php';
?>
<main id="contenido">
<h1>Mis mensajes</h1>

<table>
<caption>Mensajes enviados</caption>
<tr>
<th scope="col">Tipo</th>
<th scope="col">Texto</th>
<th scope="col">Fecha</th>
<th scope="col">Usuario</th>
</tr>
<tr>
<td>Más información</td>
<td>¿Sigue disponible el piso?</td>
<td>2026-09-25</td>
<td>jgarcia10</td>
</tr>
<tr>
<td>Solicitar una cita</td>
<td>Me gustaría visitarlo este sábado por la mañana.</td>
<td>2026-09-22</td>
<td>amartinez</td>
</tr>
</table>

<table>
<caption>Mensajes recibidos</caption>
<tr>
<th scope="col">Tipo</th>
<th scope="col">Texto</th>
<th scope="col">Fecha</th>
<th scope="col">Usuario</th>
</tr>
<tr>
<td>Comunicar una oferta</td>
<td>Le ofrezco 330.000 € por el chalet.</td>
<td>2026-09-24</td>
<td>lrodriguez</td>
</tr>
<tr>
<td>Más información</td>
<td>¿Cuántos metros cuadrados tiene la terraza?</td>
<td>2026-09-21</td>
<td>cperez</td>
</tr>
</table>

</main>
<?php require_once 'includes/pie.php'; ?>
