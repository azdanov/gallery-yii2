<?php

/** @noinspection PhpMissingParentCallCommonInspection */

declare(strict_types=1);

namespace app\models;

use app\components\StorageInterface;
use Yii;
use yii\redis\Connection;

/**
 * This is the model class for table "post".
 *
 * @property int    $id
 * @property int    $user_id
 * @property string $filename
 * @property string $description
 * @property int    $created_at
 */
class Post extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'post';
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'filename' => 'Filename',
            'description' => 'Description',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Get image location.
     *
     * @return string
     */
    public function getImage(): string
    {
        /** @var StorageInterface $storage */
        $storage = Yii::$app->storage;

        return $storage->getFile($this->filename);
    }

    /**
     * Get post author.
     *
     * @return User|\yii\db\ActiveQuery|null
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Check if user has liked the current post.
     *
     * @param \app\models\User $user
     *
     * @return mixed
     */
    public function isLikedBy(User $user)
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;

        return $redis->sismember("post:{$this->getId()}:likes", $user->getId());
    }

    /**
     * @return mixed
     */
    public function countLikes()
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;

        return $redis->scard("post:{$this->getId()}:likes");
    }

    /**
     * Like current post by user.
     *
     * @param \app\models\User $user
     */
    public function like(User $user): void
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;

        $redis->sadd("post:{$this->getId()}:likes", $user->getId());
        $redis->sadd("user:{$user->getId()}:likes", $this->getId());
    }

    /**
     * Unlike current post by user.
     *
     * @param \app\models\User $user
     */
    public function unlike(User $user): void
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;

        $redis->srem("post:{$this->getId()}:likes", $user->getId());
        $redis->srem("user:{$user->getId()}:likes", $this->getId());
    }

    /**
     * Get this posts id.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}
