<?php /** @noinspection PhpMissingParentCallCommonInspection */

declare(strict_types=1);

namespace app\modules\post\models\forms;

// phpcs:disable Zend.NamingConventions.ValidVariableName.NotCamelCaps

use app\components\StorageInterface;
use app\models\Post;
use app\models\User;
use Yii;
use yii\base\Model;

/**
 * Class PostForm.
 */
class PostForm extends Model
{
    public const MAX_DESCRIPTION_LENGTH = 1000;

    public $picture;
    public $description;

    private $user;

    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        parent::__construct();

        $this->user = $user;
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [
                ['picture'],
                'file',
                'skipOnEmpty' => false,
                'extensions' => ['jpeg', 'jpg', 'png'],
                'checkExtensionByMimeType' => true,
                'maxSize' => $this->getMaxFileSize(),
            ],
            [['description'], 'string', 'max' => self::MAX_DESCRIPTION_LENGTH],
        ];
    }

    /**
     * @return bool
     */
    public function save(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        /** @var StorageInterface $storage */
        $storage = Yii::$app->storage;
        $post = new Post();
        $post->description = $this->description;
        $post->created_at = \time();
        $post->filename = $storage->saveUploadedFile($this->picture);
        $post->user_id = $this->user->getId();

        return $post->save(false);
    }

    /**
     * Maximum size of the uploaded file.
     *
     * @return int
     */
    private function getMaxFileSize(): int
    {
        return Yii::$app->params['maxFileSize'];
    }
}
