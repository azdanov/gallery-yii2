<?php

declare(strict_types=1);

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Font Awesome asset bundle.
 */
class FontAwesomeAsset extends AssetBundle
{
    public $sourcePath = '@bower/font-awesome';
    public $css = [
        'svg-with-js/css/fa-svg-with-js.css',
    ];
    public $js = [
        'svg-with-js/js/fontawesome-all.js',
    ];
}
