<?php /** @noinspection PhpMissingParentCallCommonInspection */

declare(strict_types=1);

namespace app\modules\user\controllers;

use app\modules\user\components\AuthHandler;
use app\modules\user\models\LoginForm;
use app\modules\user\models\PasswordResetRequestForm;
use app\modules\user\models\ResetPasswordForm;
use app\modules\user\models\SignupForm;
use Yii;
use yii\authclient\AuthAction;
use yii\authclient\ClientInterface;
use yii\base\InvalidArgumentException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

/**
 * Default controller for the `user` module.
 */
class DefaultController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions(): array
    {
        return [
            'auth' => [
                'class' => AuthAction::class,
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }

    /**
     * Renders the index view for the module.
     *
     * @throws \yii\base\InvalidArgumentException
     *
     * @return string
     */
    public function actionIndex(): string
    {
        return $this->render('index');
    }

    /**
     * @param ClientInterface $client
     *
     * @throws \yii\base\Exception
     */
    public function onAuthSuccess(ClientInterface $client): void
    {
        (new AuthHandler($client))->handle();
    }

    /**
     * Logs in a user.
     *
     * @throws \yii\base\InvalidArgumentException
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        $isLoginSuccessful = $model->load(Yii::$app->request->post()) && $model->login();

        if ($isLoginSuccessful) {
            return $this->goBack();
        }
        $model->password = '';

        return $this->render(
            'login',
            [
                'model' => $model,
            ]
        );
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Signs user up.
     *
     * @throws \yii\base\InvalidArgumentException
     * @throws \yii\base\Exception
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        $isSignupSuccessful = $model->load(Yii::$app->request->post())
            && ($user = $model->signup())
            && Yii::$app->getUser()->login($user);

        if ($isSignupSuccessful) {
            return $this->goHome();
        }

        return $this->render(
            'signup',
            [
                'model' => $model,
            ]
        );
    }

    /**
     * Requests password reset.
     *
     * @throws \yii\base\InvalidArgumentException
     * @throws \yii\base\Exception
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        $isEmailValid = $model->load(Yii::$app->request->post()) && $model->validate();

        if ($isEmailValid) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash(
                    'success',
                    'Check your email for further instructions.'
                );

                return $this->goHome();
            }

            Yii::$app->session->setFlash(
                'error',
                'Sorry, we are unable to reset password for the provided email address.'
            );
        }

        return $this->render(
            'requestPasswordResetToken',
            [
                'model' => $model,
            ]
        );
    }

    /**
     * Resets password.
     *
     * @param string $token
     *
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidArgumentException
     * @throws BadRequestHttpException
     * @throws \Exception
     *
     * @return mixed
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        $isPasswordSaved = $model->load(Yii::$app->request->post())
            && $model->validate() && $model->resetPassword();

        if ($isPasswordSaved) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render(
            'resetPassword',
            [
                'model' => $model,
            ]
        );
    }
}
