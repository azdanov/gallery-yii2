<?php

declare(strict_types=1);

namespace app\modules\user\components;

use app\models\User;
use app\modules\user\models\Auth;
use Yii;
use yii\authclient\ClientInterface;
use yii\helpers\ArrayHelper;

/**
 * AuthHandler handles successful authentication via Yii auth component.
 */
class AuthHandler
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * AuthHandler constructor.
     *
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     *
     * @return bool|null
     */
    public function handle(): ?bool
    {
        if (!Yii::$app->user->isGuest) {
            return null;
        }

        $attributes = $this->client->getUserAttributes();

        $auth = $this->findAuth($attributes);
        if ($auth) {
            $user = $auth->user;

            return Yii::$app->user->login($user);
        }
        if ($user = $this->createAccount($attributes)) {
            return Yii::$app->user->login($user);
        }

        return null;
    }

    /**
     * @param array $attributes
     *
     * @return Auth|null
     */
    private function findAuth($attributes): ?Auth
    {
        $id = ArrayHelper::getValue($attributes, 'id');
        $params = [
            'source_id' => $id,
            'source' => $this->client->getId(),
        ];

        return Auth::find()->where($params)->one();
    }

    /**
     * @param mixed $attributes
     *
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     *
     * @return User|null
     */
    private function createAccount($attributes): ?User
    {
        $email = ArrayHelper::getValue($attributes, 'email');
        $id = ArrayHelper::getValue($attributes, 'id');
        $name = ArrayHelper::getValue($attributes, 'name');

        if (null !== $email && User::find()->where(['email' => $email])->exists()) {
            return null;
        }

        $user = $this->createUser($email, $name);

        $transaction = User::getDb()->beginTransaction();
        if ($user->save()) {
            $auth = $this->createAuth($user->id, $id);
            if ($auth->save()) {
                $transaction->commit();

                return $user;
            }
        }
        $transaction->rollBack();

        return null;
    }

    /**
     * @param string $email
     * @param string $name
     *
     * @throws \yii\base\Exception
     *
     * @return User
     */
    private function createUser($email, $name): User
    {
        return new User(
            [
                'username' => $name,
                'email' => $email,
                'auth_key' => Yii::$app->security->generateRandomString(),
                'password_hash' => Yii::$app->security->generatePasswordHash(
                    Yii::$app->security->generateRandomString()
                ),
                'created_at' => $time = \time(),
                'updated_at' => $time,
            ]
        );
    }

    /**
     * @param string $userId
     * @param string $sourceId
     *
     * @return Auth
     */
    private function createAuth($userId, $sourceId): Auth
    {
        return new Auth(
            [
                'user_id' => $userId,
                'source' => $this->client->getId(),
                'source_id' => (string) $sourceId,
            ]
        );
    }
}
