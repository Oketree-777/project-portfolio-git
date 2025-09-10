<?php

return [

    'pdf' => [
        'enabled' => true,
        'binary'  => env('WKHTMLTOPDF_BINARY', '"C:\Program Files\wkhtmltopdf\bin\wkhtmltopdf.exe"'),
        'timeout' => false,
        'options' => [
            'encoding'      => 'UTF-8',
            'page-size'     => 'A4',
            'margin-top'    => 0,
            'margin-right'  => 0,
            'margin-bottom' => 0,
            'margin-left'   => 0,
            'encoding'      => 'UTF-8',
            'no-outline'    => true,
            'enable-local-file-access' => true,
        ],
        'env'     => [],
    ],

    'image' => [
        'enabled' => true,
        'binary'  => env('WKHTMLTOIMAGE_BINARY', '"C:\Program Files\wkhtmltopdf\bin\wkhtmltoimage.exe"'),
        'timeout' => false,
        'options' => [
            'encoding'      => 'UTF-8',
            'format'        => 'png',
            'quality'       => 100,
            'enable-local-file-access' => true,
        ],
        'env'     => [],
    ],

];
