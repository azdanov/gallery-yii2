<?php

declare(strict_types=1);

namespace app\modules\user\models\forms;

use Yii;
use yii\base\Model;

/**
 * Class PictureForm.
 */
class PictureForm extends Model
{
    public $picture;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['picture'], 'file',
                'mimeTypes' => 'image/*',
                'extensions' => ['jpeg', 'jpg', 'png'],
                'maxSize' => Yii::$app->params['maxFileSize'],
            ],
        ];
    }
}
