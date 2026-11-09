<?php
/*
 * PLANTILLA 3 - Pie de página y cierre del documento: <footer>, scripts, </body></html>.
 */
$scripts = $scripts ?? [];
if (VALIDACION_CLIENTE && !empty($scriptsValidacion)) {
    $scripts = array_merge($scripts, $scriptsValidacion);
}
?>
<footer>
<p>&copy; 2026 Nexocasa · <a href="accesibilidad.php">Accesibilidad</a></p>
</footer>
<?php foreach ($scripts as $script): ?>
<script src="<?= e($script) ?>"></script>
<?php endforeach; ?>
</body>
</html>
