"use strict";
/*
 * Página "Solicitar folleto": tabla de posibles costes calculada con las tarifas
 * y construida nodo a nodo con el DOM (sin innerHTML). Se muestra/oculta con un botón.
 */
document.addEventListener("DOMContentLoaded", () => {
    // ---------- Tarifas (importes en céntimos para evitar errores de redondeo) ----------
    const TARIFAS = {
        procesamientoEnvio: 1000,
        // Sistema de bloques: cada página se cobra según el tramo en el que cae
        tramos: [
            { hasta: 4, porPagina: 200 },        // < 5 páginas
            { hasta: 10, porPagina: 180 },       // entre 5 y 10 páginas
            { hasta: Infinity, porPagina: 160 }  // > 10 páginas
        ],
        colorPorFoto: 50,
        resolucionAltaPorFoto: 20,
        limiteResolucionBaja: 300                // <= 300 dpi no tiene recargo
    };

    // ---------- Parámetros de la tabla ----------
    const MAX_PAGINAS = 15;
    const FOTOS_POR_PAGINA = 3;
    const RANGOS_DPI = [{ min: 150, max: 300 }, { min: 450, max: 900 }];
    const MODOS = [{ nombre: "Blanco y negro", color: false }, { nombre: "Color", color: true }];

    // ---------- Cálculo ----------
    function costePaginas(paginas) {
        let total = 0;
        let anterior = 0;
        for (const tramo of TARIFAS.tramos) {
            const enTramo = Math.max(0, Math.min(paginas, tramo.hasta) - anterior);
            total += enTramo * tramo.porPagina;
            anterior = tramo.hasta;
        }
        return total;
    }

    function costeFolleto(paginas, fotos, color, dpi) {
        let total = TARIFAS.procesamientoEnvio + costePaginas(paginas);
        if (color) total += fotos * TARIFAS.colorPorFoto;
        if (dpi > TARIFAS.limiteResolucionBaja) total += fotos * TARIFAS.resolucionAltaPorFoto;
        return total;
    }

    const formatoEuros = new Intl.NumberFormat("es-ES", { style: "currency", currency: "EUR" });
    const formatear = centimos => formatoEuros.format(centimos / 100);

    // ---------- Construcción de la tabla con el DOM ----------
    function crear(etiqueta, texto, atributos = {}) {
        const elemento = document.createElement(etiqueta);
        if (texto !== undefined) elemento.textContent = texto;
        for (const [nombre, valor] of Object.entries(atributos)) elemento.setAttribute(nombre, valor);
        return elemento;
    }

    function crearCabecera() {
        const cabecera = document.createElement("thead");

        const fila1 = document.createElement("tr");
        fila1.append(
            crear("th", "Número de páginas", { scope: "col", rowspan: 2 }),
            crear("th", "Número de fotos", { scope: "col", rowspan: 2 })
        );
        for (const modo of MODOS) {
            fila1.appendChild(crear("th", modo.nombre, { scope: "colgroup", colspan: RANGOS_DPI.length }));
        }

        const fila2 = document.createElement("tr");
        for (let i = 0; i < MODOS.length; i++) {
            for (const rango of RANGOS_DPI) {
                fila2.appendChild(crear("th", `${rango.min}-${rango.max} dpi`, { scope: "col" }));
            }
        }

        cabecera.append(fila1, fila2);
        return cabecera;
    }

    function crearCuerpo() {
        const cuerpo = document.createElement("tbody");
        for (let paginas = 1; paginas <= MAX_PAGINAS; paginas++) {
            const fotos = paginas * FOTOS_POR_PAGINA;
            const fila = document.createElement("tr");
            fila.append(crear("th", paginas, { scope: "row" }), crear("td", fotos));
            for (const modo of MODOS) {
                for (const rango of RANGOS_DPI) {
                    // La resolución más baja del rango representa a todo el rango
                    fila.appendChild(crear("td", formatear(costeFolleto(paginas, fotos, modo.color, rango.min))));
                }
            }
            cuerpo.appendChild(fila);
        }
        return cuerpo;
    }

    function crearTabla() {
        const tabla = document.createElement("table");
        tabla.className = "tabla-costes";
        tabla.append(
            crear("caption", "Posibles costes de un folleto de publicidad impreso"),
            crearCabecera(),
            crearCuerpo()
        );
        return tabla;
    }

    // ---------- Mostrar / ocultar ----------
    const contenedor = document.getElementById("contenedor-costes");
    const boton = document.getElementById("boton-costes");
    const textoBoton = document.getElementById("texto-boton-costes");

    contenedor.appendChild(crearTabla());

    boton.addEventListener("click", () => {
        // Atributo hidden: funciona sin depender de ninguna hoja de estilos
        contenedor.hidden = !contenedor.hidden;
        const oculta = contenedor.hidden;
        boton.setAttribute("aria-expanded", String(!oculta));
        textoBoton.textContent = oculta ? "Mostrar tabla de costes" : "Ocultar tabla de costes";
    });
});
