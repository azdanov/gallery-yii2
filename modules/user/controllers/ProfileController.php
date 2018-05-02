<?php

declare(strict_types=1);

namespace app\modules\user\controllers;

use app\models\User;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Profile controller for the `user` module.
 */
class ProfileController extends Controller
{
    /**
     * @param string $identifier
     *
     * @throws \yii\base\InvalidArgumentException
     * @throws NotFoundHttpException
     *
     * @return string
     */
    public function actionView(string $identifier): string
    {
        /** @var User $currentUser */
        $currentUser = Yii::$app->user->identity;

        return $this->render(
            'view',
            [
                'user' => $this->findUser($identifier),
                'currentUser' => $currentUser,
            ]
        );
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param string $id
     *
     * @throws \yii\base\InvalidArgumentException
     * @throws NotFoundHttpException
     *
     * @return mixed
     */
    public function actionUpdate(string $id)
    {
        $user = $this->findUser($id);

        $isSaved = $user->load(Yii::$app->request->post()) && $user->save();

        if ($isSaved) {
            return $this->redirect(
                ['/user/profile/view', 'identifier' => $id]
            );
        }

        return $this->render('update', [
            'user' => $user,
        ]);
    }

    /**
     * @param string $id
     *
     * @throws NotFoundHttpException
     *
     * @return Response
     */
    public function actionSubscribe(string $id): Response
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }

        $currentUser = Yii::$app->user->identity;

        if ($currentUser->getId() === (int) $id) {
            Yii::$app->session->setFlash('error', 'Cannot subscribe to your own profile.');

            return $this->redirect(
                ['/user/profile/view', 'identifier' => $id]
            );
        }

        /* @var $currentUser User */

        $userToSubscribe = $this->findUser($id);

        $currentUser->subscribeUser($userToSubscribe);

        return $this->redirect(
            ['/user/profile/view', 'identifier' => $userToSubscribe->getNickname()]
        );
    }

    /**
     * @param string $id
     *
     * @throws NotFoundHttpException
     *
     * @return Response
     */
    public function actionUnsubscribe(string $id): Response
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }

        /* @var $currentUser User */
        $currentUser = Yii::$app->user->identity;

        if ($currentUser->getId() === (int) $id) {
            Yii::$app->session->setFlash('error', 'Cannot unsubscribe from your own profile.');

            return $this->redirect(
                ['/user/profile/view', 'identifier' => $id]
            );
        }

        $userToUnsubscribe = $this->findUser($id);

        $currentUser->unsubscribeUser($userToUnsubscribe);

        return $this->redirect(
            ['/user/profile/view', 'identifier' => $userToUnsubscribe->getNickname()]
        );
    }

    /**
     * @param string $identifier
     *
     * @throws NotFoundHttpException
     *
     * @return mixed
     */
    private function findUser(string $identifier)
    {
        $user = User::find()
            ->where(['nickname' => $identifier])
            ->orWhere(['id' => $identifier])
            ->one();

        if (!$user) {
            throw new NotFoundHttpException();
        }

        return $user;
    }
}
