<?php

declare(strict_types=1);

namespace app\modules\user\models;

use app\models\User;
use Yii;
use yii\base\Model;

/**
 * Login form.
 *
 * @property \app\models\User|null $user
 */
class LoginForm extends Model
{
    public $email;
    public $password;
    public $rememberMe = true;

    private $user;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['email', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     */
    public function validatePassword($attribute): void
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect email or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided email and password.
     *
     * @throws \yii\base\InvalidArgumentException
     *
     * @return bool whether the user is logged in successfully
     */
    public function login(): bool
    {
        $daysInSeconds = 2592000; // 30 days
        if ($this->validate()) {
            return Yii::$app->user->login(
                $this->getUser(),
                $this->rememberMe
                    ? $daysInSeconds
                    : 0
            );
        }

        return false;
    }

    /**
     * Finds user by [[email]].
     *
     * @return User|null
     */
    protected function getUser(): ?User
    {
        if (null === $this->user) {
            $this->user = User::findByEmail($this->email);
        }

        return $this->user;
    }
}
