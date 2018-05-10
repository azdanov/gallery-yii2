<?php

declare(strict_types=1);

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Picturefill asset bundle.
 */
class PicturefillAsset extends AssetBundle
{
    public $sourcePath = '@bower/picturefill';
    public $js = [
        'dist/picturefill.js',
    ];
}
