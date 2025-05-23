<?php

return [
    'simple'       => [
        'key'   => 'simple',
        'name'  => 'product::app.type.simple',
        'class' => 'Modules\Product\Type\Simple',
        'sort'  => 1,
    ],

    'configurable' => [
        'key'   => 'configurable',
        'name'  => 'product::app.type.configurable',
        'class' => 'Modules\Product\Type\Configurable',
        'sort'  => 2,
    ],

    'virtual'      => [
        'key'   => 'virtual',
        'name'  => 'product::app.type.virtual',
        'class' => 'Modules\Product\Type\Virtual',
        'sort'  => 3,
    ],

    'grouped'      => [
        'key'   => 'grouped',
        'name'  => 'product::app.type.grouped',
        'class' => 'Modules\Product\Type\Grouped',
        'sort'  => 4,
    ],

    'downloadable' => [
        'key'   => 'downloadable',
        'name'  => 'product::app.type.downloadable',
        'class' => 'Modules\Product\Type\Downloadable',
        'sort'  => 5,
    ],

    'bundle'       => [
        'key'   => 'bundle',
        'name'  => 'product::app.type.bundle',
        'class' => 'Modules\Product\Type\Bundle',
        'sort'  => 6,
    ],
];
