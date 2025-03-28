<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default search index
    |--------------------------------------------------------------------------
    |
    | This option controls the search index that gets queried when performing
    | search functions without explicitly selecting another index.
    |
    */

    'default' => env('STATAMIC_DEFAULT_SEARCH_INDEX', 'default'),

    /*
    |--------------------------------------------------------------------------
    | Search Indexes
    |--------------------------------------------------------------------------
    |
    | Here you can define all of the available search indexes.
    |
    */

    'indexes' => [

        'default' => [
            'driver' => 'local',
            'searchables' => 'all',
            'fields' => ['title'],
        ],
        'pages' => [
            'driver' => 'local',
            'searchables' => [
                'collection:pages',
                'collection:complex_pages',
                'collection:informative_pages',
            ],
            'fields' => ['title', 'categories', 'tags', 'content'],
            'min_characters' => 3,  // Mínimo de caracteres en la búsqueda
            'min_word_characters' => 1,  // Mínimo de caracteres en una palabra de la búsqueda
            'query_mode' => 'any',  // Modo de búsqueda (puede ser 'any' para cualquier palabra, 'all' para todas las palabras)
            'use_stemming' => true,  // Uso de derivación de palabras (stemming)
            'use_alternates' => true,  // Uso de alternativos de palabras
            'sort_by_score' => true,  // Ordenar por relevancia
            'fuzzy' => true,  // Búsqueda difusa para tolerancia a errores tipográficos
            'threshold' => 0.1,  // Umbral de similitud para búsqueda difusa (más bajo es más permisivo)
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Driver Defaults
    |--------------------------------------------------------------------------
    |
    | Here you can specify default configuration to be applied to all indexes
    | that use the corresponding driver. For instance, if you have two
    | indexes that use the "local" driver, both of them can have the
    | same base configuration. You may override for each index.
    |
    */

    'drivers' => [

        'local' => [
            'path' => storage_path('statamic/search'),
        ],

        'algolia' => [
            'credentials' => [
                'id' => env('ALGOLIA_APP_ID', ''),
                'secret' => env('ALGOLIA_SECRET', ''),
            ],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Search Defaults
    |--------------------------------------------------------------------------
    |
    | Here you can specify default configuration to be applied to all indexes
    | regardless of the driver. You can override these per driver or per index.
    |
    */

    'defaults' => [
        'fields' => ['title'],
    ],

];
