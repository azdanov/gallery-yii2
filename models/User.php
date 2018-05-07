<?php

/** @noinspection PhpMissingParentCallCommonInspection */

declare(strict_types=1);

// phpcs:disable Zend.NamingConventions.ValidVariableName.NotCamelCaps

namespace app\models;

use app\components\StorageInterface;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\redis\Connection;
use yii\web\IdentityInterface;

/**
 * User model.
 *
 * @property int    $id                   [int(11)]
 * @property string $username             [varchar(255)]
 * @property string $auth_key             [varchar(32)]
 * @property string $password_hash        [varchar(255)]
 * @property string $password_reset_token [varchar(255)]
 * @property string $email                [varchar(255)]
 * @property int    $status               [smallint(6)]
 * @property int    $created_at           [int(11)]
 * @property int    $updated_at           [int(11)]
 * @property string $about
 * @property int    $type                 [int(3)]
 * @property string $picture              [varchar(255)]
 * @property string $nickname             [varchar(70)]
 */
class User extends ActiveRecord implements IdentityInterface
{
    public const STATUS_DELETED = 0;
    public const STATUS_ACTIVE = 10;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }

    /**
     * Find user by the provided email.
     *
     * @param string $email
     *
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Find user by the provided password reset token.
     *
     * @param string $token password reset token
     *
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne(
            [
                'password_reset_token' => $token,
                'status' => self::STATUS_ACTIVE,
            ]
        );
    }

    /**
     * Find out if the provided password reset token is valid.
     *
     * @param string $token password reset token
     *
     * @return bool
     */
    public static function isPasswordResetTokenValid($token): bool
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) \substr($token, \strrpos($token, '_') + 1);
        $expire = Yii::$app->params['passwordResetTokenExpire'];

        return $timestamp + $expire >= \time();
    }

    /**
     * Get this users nickname, or id.
     *
     * @return string
     */
    public function getNickname(): string
    {
        return $this->nickname ?: (string) $this->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey(): string
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey): bool
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     * @throws NotSupportedException
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Get users profile picture.
     * Return a default picture if not found.
     *
     * @throws \yii\base\InvalidConfigException
     *
     * @return string
     */
    public function getPicture(): string
    {
        if ($this->picture) {
            /** @var StorageInterface $storage */
            $storage = Yii::$app->get('storage');

            return $storage->getFile($this->picture);
        }

        return Yii::$app->params['defaultPicture'];
    }

    /**
     * Validate password.
     *
     * @param string $password password to validate
     *
     * @throws \yii\base\InvalidArgumentException
     *
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generate password hash from password for this user.
     *
     * @param string $password
     *
     * @throws \yii\base\Exception
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generate "remember me" authentication key for this user.
     *
     * @throws \yii\base\Exception
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generate new password reset token for this user.
     *
     * @throws \yii\base\Exception
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString().'_'.\time();
    }

    /**
     * Remove password reset token on this user.
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * Subscribe current user to given user.
     *
     * @param self $user
     */
    public function subscribeUser(self $user)
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;

        $redis->sadd("user:{$this->getId()}:subscriptions", $user->getId());
        $redis->sadd("user:{$user->getId()}:followers", $this->getId());
    }

    /**
     * Unsubscribe current user from given user.
     *
     * @param self $user
     */
    public function unsubscribeUser(self $user)
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;

        $redis->srem("user:{$this->getId()}:subscriptions", $user->getId());
        $redis->srem("user:{$user->getId()}:followers", $this->getId());
    }

    /**
     * Fetch all this users subscriptions.
     *
     * @return array
     */
    public function getSubscriptions(): array
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;
        $key = "user:{$this->getId()}:subscriptions";
        $ids = $redis->smembers($key);

        return static::find()
            ->select('id, username, nickname')
            ->where(['id' => $ids])
            ->orderBy('username')
            ->asArray()
            ->all();
    }

    /**
     * Fetch all this users followers.
     *
     * @return array
     */
    public function getFollowers(): array
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;
        $key = "user:{$this->getId()}:followers";
        $ids = $redis->smembers($key);

        return static::find()
            ->select('id, username, nickname')
            ->where(['id' => $ids])
            ->orderBy('username')
            ->asArray()
            ->all();
    }

    /**
     * Count this users followers.
     *
     * @return mixed
     */
    public function countFollowers()
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;

        return $redis->scard("user:{$this->getId()}:followers");
    }

    /**
     * Count this users subscriptions.
     *
     * @return mixed
     */
    public function countSubscriptions()
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;

        return $redis->scard("user:{$this->getId()}:subscriptions");
    }

    /**
     * Find the intersection of my subscriptions and given users followers.
     *
     * @param self $user
     *
     * @return array
     */
    public function mutualSubscriptions(self $user): array
    {
        $currentUserSubscriptions = "user:{$this->getId()}:subscriptions";
        $givenUserFollowers = "user:{$user->getId()}:followers";

        /* @var $redis Connection */
        $redis = Yii::$app->redis;

        $ids = $redis->sinter($currentUserSubscriptions, $givenUserFollowers);

        return static::find()
            ->select('id, username, nickname')
            ->where(['id' => $ids])
            ->orderBy('username')
            ->asArray()
            ->all();
    }

    /**
     * Check if the user is subscribed to provided user.
     *
     * @param self $user
     *
     * @return bool
     */
    public function isSubscribedTo(self $user): bool
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;

        return (bool) $redis->sismember(
            "user:{$this->getId()}:subscriptions",
            $user->getId()
        );
    }

    /**
     * Get data for feed.
     *
     * @param int $limit
     *
     * @return ActiveRecord[]|array|User[]
     */
    public function getFeed(int $limit): array
    {
        $order = ['post_created_at' => SORT_DESC];

        return $this
            ->hasMany(Feed::class, ['user_id' => 'id'])
            ->orderBy($order)
            ->limit($limit)
            ->all();
    }

    /**
     * Check whether current user likes post with given id.
     *
     * @param int $postId
     *
     * @return bool
     */
    public function likesPost(int $postId): bool
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;

        return (bool) $redis->sismember("user:{$this->getId()}:likes", $postId);
    }
}
