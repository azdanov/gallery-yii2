<?php /** @noinspection PhpMissingParentCallCommonInspection */

declare(strict_types=1);

namespace app\modules\user\models;

// phpcs:disable Zend.NamingConventions.ValidVariableName.NotCamelCaps

use app\models\User;
use Yii;
use yii\base\Model;

/**
 * Password reset request form.
 */
class PasswordResetRequestForm extends Model
{
    public $email;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            [
                'email',
                'exist',
                'targetClass' => User::class,
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => 'There is no user with this email address.',
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @throws \yii\base\Exception
     *
     * @return bool whether the email was send
     */
    public function sendEmail(): bool
    {
        /* @var $user User */
        $user = User::findOne(
            [
                'status' => User::STATUS_ACTIVE,
                'email' => $this->email,
            ]
        );

        if (!$user) {
            return false;
        }

        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();

            if (!$user->save()) {
                return false;
            }
        }

        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'passwordResetTokenHtml', 'text' => 'passwordResetTokenText'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name.' robot'])
            ->setTo($this->email)
            ->setSubject('Password reset for '.Yii::$app->name)
            ->send();
    }
}
