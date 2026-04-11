<?php

return [
    'disable' => env('CAPTCHA_DISABLE', false),
    'characters' => '2346789abcdefghjmnpqrtuxyzABCDEFGHJMNPQRTUXYZ',
    'default' => [
    'length' => 4,
    'width' => 120,
    'height' => 36,
    'quality' => 90,
    'math' => false,
    'charset' => '0123456789', // <== THIS IS KEY
    'characters' => '0123456789', // older versions use this
    'bgColor' => '#ffffff',
    'fontColors' => ['000000'],
    'contrast' => 0,
],
    'math' => [
        'length' => 9,
        'width' => 120,
        'height' => 36,
        'quality' => 90,
        'math' => true,
        'bgColor' => '#ecf2f4',
    ],

    'flat' => [
        'length' => 6,
        'width' => 160,
        'height' => 46,
        'quality' => 90,
        'lines' => 6,
        'bgImage' => false,
        'bgColor' => '#ecf2f4',
        'fontColors' => ['#2c3e50', '#c0392b', '#16a085', '#c0392b', '#8e44ad', '#303f9f', '#f57c00', '#795548'],
        'contrast' => -5,
    ],
    'mini' => [
        'length' => 3,
        'width' => 60,
        'height' => 32,
    ],
    'inverse' => [
        'length' => 5,
        'width' => 120,
        'height' => 36,
        'quality' => 90,
        'sensitive' => true,
        'angle' => 12,
        'sharpen' => 10,
        'blur' => 2,
        'invert' => true,
        'contrast' => -5,
    ],

    'fonts' => [
    resource_path('fonts/Roboto-Bold.ttf')
],
    'fontColors' => ['000000'], // Ensure dark color
    'contrast'   => 0, // Remove contrast blur
];
