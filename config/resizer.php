<?php

// width, height, crop, filter, watermark

return [
    'dir'=>'images',
    'tmpDir'=>'tmp',
    'dirs'=>[
        'default'=>[
            'thumbs'=>[130, 130, true],
            //'small'=>[300, 300, true],
            'big'=>[1280, 1024, false],
            //'bigGrayscale'=>[640, 640, true, IMG_FILTER_GRAYSCALE],
            'source'=>[1920, 1200, false],
        ]
        ,'calendar'=>[
            'thumbs'=>[130, 130, true],
            'small'=>[300, 202, true],
            'medium'=>[768, 576, true],
            'big'=>[1280, 1024, false],
            'source'=>[1920, 1200, false],
        ]
        ,'recipe'=>[
            'thumbs'=>[130, 130, true],
            'small'=>[150, 100, true],
            'medium'=>[768, 576, true],
            'big'=>[1280, 1024, false],
            'source'=>[1920, 1200, false],
        ],
    ],
    'settings'=>[
        'quality'=>75,
        'resize'=>'auto',
    ]
];