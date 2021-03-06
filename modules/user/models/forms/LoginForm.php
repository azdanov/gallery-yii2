<?php

declare(strict_types=1);

namespace app\modules\user\models\forms;

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
     *
     * @throws \yii\base\InvalidArgumentException
     */
    public function validatePassword($attribute): void
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            $isPasswordInvalid = !$user || !$user->validatePassword($this->password);

            if ($isPasswordInvalid) {
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
        $secondsToRemember = $this->rememberMe
            ? Yii::$app->params['secondsToRemember']
            : 0;

        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $secondsToRemember);
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
