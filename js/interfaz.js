"use strict";
/*
 * Utilidades de interfaz para formularios: mensajes de error junto a cada campo,
 * resumen de errores al principio del formulario y validación en línea.
 */
window.Nexocasa = window.Nexocasa || {};

Nexocasa.interfaz = (function () {
    const $ = id => document.getElementById(id);

    // Elemento que se resalta (el <p> del campo o el propio <fieldset>)
    const contenedorDe = campo => (campo.tagName === "FIELDSET" ? campo : campo.parentElement);

    // Elemento que recibe el foco (en un grupo de radios, el primero)
    const enfoqueDe = campo => (campo.tagName === "FIELDSET" ? campo.querySelector("input") : campo);

    function obtenerSpanError(campo) {
        const id = `error-${campo.id}`;
        let span = $(id);
        if (span) return span;

        span = document.createElement("span");
        span.id = id;
        span.className = "mensaje-error";

        const ayuda = $(`ayuda-${campo.id}`);
        if (campo.tagName === "FIELDSET") {
            campo.appendChild(span);
        } else {
            const referencia = ayuda || campo;
            referencia.parentNode.insertBefore(span, referencia.nextSibling);
        }
        campo.setAttribute("aria-describedby", ayuda ? `${ayuda.id} ${id}` : id);
        return span;
    }

    function mostrarError(campo, mensaje) {
        obtenerSpanError(campo).textContent = mensaje;
        campo.setAttribute("aria-invalid", "true");
        contenedorDe(campo).classList.add("con-error");
    }

    function limpiarError(campo) {
        obtenerSpanError(campo).textContent = "";
        campo.removeAttribute("aria-invalid");
        contenedorDe(campo).classList.remove("con-error");
    }

    function mostrarResumen(resumen, errores) {
        const titulo = document.createElement("h3");
        titulo.textContent = errores.length === 1
            ? "No se ha enviado el formulario: hay 1 campo por corregir"
            : `No se ha enviado el formulario: hay ${errores.length} campos por corregir`;

        const lista = document.createElement("ul");
        for (const { def, mensaje } of errores) {
            const enlace = document.createElement("a");
            enlace.href = `#${def.id}`;
            enlace.textContent = `${def.etiqueta}: ${mensaje}`;
            enlace.addEventListener("click", evento => {
                evento.preventDefault();
                enfoqueDe(def.elemento).focus();
            });
            const item = document.createElement("li");
            item.appendChild(enlace);
            lista.appendChild(item);
        }

        resumen.replaceChildren(titulo, lista);
        resumen.classList.remove("oculto");
    }

    function ocultarResumen(resumen) {
        resumen.replaceChildren();
        resumen.classList.add("oculto");
    }

    /**
     * Conecta un formulario con sus reglas de validación.
     * @param {HTMLFormElement} formulario
     * @param {Array<{id:string, etiqueta:string, validar:function():string, revalidar?:string[]}>} campos
     *   id: id del campo (o del fieldset de un grupo de radios); etiqueta: nombre mostrado en el resumen;
     *   validar: devuelve "" si es válido o el mensaje de error; revalidar: ids de campos que dependen de éste.
     */
    function configurarFormulario(formulario, campos) {
        const resumen = formulario.querySelector(".resumen-errores");
        const porId = {};

        for (const def of campos) {
            def.elemento = $(def.id);
            porId[def.id] = def;
        }

        // Al pulsar un botón, el campo pierde el foco y su aviso desplazaría el botón antes de soltar
        // el clic (el clic se perdería). Mientras se pulsa se omite esa validación: el envío valida todo.
        let pulsandoBoton = false;
        formulario.addEventListener("pointerdown", evento => {
            if (!evento.target.closest("button")) return;
            pulsandoBoton = true;
            document.addEventListener("pointerup", () => setTimeout(() => { pulsandoBoton = false; }, 0), { once: true });
        });

        function comprobar(def) {
            const mensaje = def.validar();
            if (mensaje) mostrarError(def.elemento, mensaje);
            else limpiarError(def.elemento);
            return mensaje;
        }

        for (const def of campos) {
            const elemento = def.elemento;
            if (elemento.tagName === "FIELDSET") {
                elemento.addEventListener("change", () => comprobar(def));
                continue;
            }
            // Al salir del campo se valida; después, mientras se corrige, se revalida al escribir
            elemento.addEventListener("blur", () => { if (!pulsandoBoton) comprobar(def); });
            elemento.addEventListener("input", () => {
                if (elemento.getAttribute("aria-invalid") === "true") comprobar(def);
                for (const idDependiente of def.revalidar || []) {
                    const dependiente = porId[idDependiente];
                    if (dependiente.elemento.value !== "") comprobar(dependiente);
                }
            });
        }

        formulario.addEventListener("submit", evento => {
            const errores = [];
            for (const def of campos) {
                const mensaje = comprobar(def);
                if (mensaje) errores.push({ def, mensaje });
            }
            if (errores.length > 0) {
                evento.preventDefault();
                mostrarResumen(resumen, errores);
                resumen.focus();
            } else {
                ocultarResumen(resumen);
            }
        });

        formulario.addEventListener("reset", () => {
            campos.forEach(def => limpiarError(def.elemento));
            ocultarResumen(resumen);
        });
    }

    return { configurarFormulario, mostrarError, limpiarError };
})();
