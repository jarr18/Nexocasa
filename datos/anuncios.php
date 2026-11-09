<?php
/*
 * Datos de los DOS anuncios de ejemplo que se muestran en "Detalle anuncio" y "Ver anuncio".
 * Este fichero SOLO guarda estos datos. En una práctica posterior se leerán de una base de datos.
 *
 *   'par'   → se muestra cuando el identificador recibido es un número par.
 *   'impar' → se muestra cuando el identificador recibido es un número impar.
 *
 * También se usan como anuncios del usuario en "Mis anuncios" (con el identificador 'id').
 */
return [
    'impar' => [
        'id'           => 1,
        'tipoAnuncio'  => 'venta',
        'tipoVivienda' => 'vivienda',
        'foto'         => 'img/casa-madrid.svg',
        'altFoto'      => 'Fachada del chalet adosado de diseño en Madrid',
        'titulo'       => 'Chalet adosado de diseño',
        'precio'       => 340000,
        'texto'        => 'Chalet adosado de reciente construcción situado en una zona tranquila de Madrid, '
                        . 'con jardín privado y a pocos minutos del centro.',
        'fecha'        => '2026-09-20',
        'ciudad'       => 'Madrid',
        'pais'         => 'espana',
        'caracteristicas' => [
            'superficie'   => 180,
            'habitaciones' => 4,
            'banos'        => 3,
            'planta'       => 2,
            'anio'         => 2021,
        ],
        'fotos' => [
            ['src' => 'img/casa-madrid.svg',   'alt' => 'Fachada del chalet'],
            ['src' => 'img/casa-madrid-2.svg', 'alt' => 'Balcón del chalet'],
            ['src' => 'img/casa-madrid-3.svg', 'alt' => 'Cocina del chalet'],
            ['src' => 'img/casa-madrid-4.svg', 'alt' => 'Salón del chalet'],
        ],
        'usuario' => 'paquito123',
    ],
    'par' => [
        'id'           => 2,
        'tipoAnuncio'  => 'alquiler',
        'tipoVivienda' => 'vivienda',
        'foto'         => 'img/casa-barcelona.svg',
        'altFoto'      => 'Ático luminoso con terraza en Barcelona',
        'titulo'       => 'Ático luminoso con terraza',
        'precio'       => 1350,
        'texto'        => 'Ático exterior muy luminoso en el Eixample, con una terraza de 30 m² con vistas, '
                        . 'cocina equipada y cerca del metro.',
        'fecha'        => '2026-09-19',
        'ciudad'       => 'Barcelona',
        'pais'         => 'espana',
        'caracteristicas' => [
            'superficie'   => 95,
            'habitaciones' => 2,
            'banos'        => 2,
            'planta'       => 7,
            'anio'         => 2008,
        ],
        'fotos' => [
            ['src' => 'img/casa-barcelona.svg', 'alt' => 'Terraza del ático'],
            ['src' => 'img/casa-madrid-3.svg',  'alt' => 'Cocina del ático'],
            ['src' => 'img/casa-madrid-4.svg',  'alt' => 'Salón del ático'],
        ],
        'usuario' => 'mlopez23',
    ],
];
