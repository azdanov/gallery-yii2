<?php

declare(strict_types=1);

namespace app\modules\user\controllers;

use app\components\StorageInterface;
use app\models\User;
use app\modules\user\models\forms\PictureForm;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

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

        $pictureForm = new PictureForm();

        return $this->render(
            'view',
            [
                'user' => $this->findUser($identifier),
                'currentUser' => $currentUser,
                'pictureForm' => $pictureForm,
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

        return $this->render('update');
    }

    /**
     * Handle profile image upload via ajax request.
     *
     * @throws \yii\base\InvalidArgumentException
     * @throws \yii\base\InvalidConfigException
     *
     * @return array
     */
    public function actionUploadPicture(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $pictureForm = new PictureForm();
        $pictureForm->picture = UploadedFile::getInstance($pictureForm, 'picture');

        if ($pictureForm->validate()) {
            /** @var User $user */
            $user = Yii::$app->user->identity; // FIXME: Make sure user is logged in
            /** @var StorageInterface $storage */
            $storage = Yii::$app->get('storage');

            $pictureLocation = $storage->saveUploadedFile($pictureForm->picture);

            $user->picture = $pictureLocation;
            $isPictureSaved = $user->save(false, ['picture']);

            if ($isPictureSaved) {
                return [
                    'success' => true,
                    'pictureUri' => $storage->getFile($user->picture),
                ];
            }
        }

        return ['success' => false, 'errors' => $pictureForm->getErrors()];
    }

    /**
     * Delete this users picture.
     *
     * @throws \yii\base\InvalidConfigException
     *
     * @return Response
     */
    public function actionDeletePicture(): Response
    {
        /** @var User $currentUser */
        $currentUser = Yii::$app->user->identity;

        if ($this->deletePicture()) {
            Yii::$app->session->setFlash('success', 'Profile picture is deleted.');
        } else {
            Yii::$app->session->setFlash('error', 'Profile picture is not deleted.');
        }

        return $this->redirect(
            ['/user/profile/view', 'identifier' => $currentUser->id]
        );
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

    /**
     * @throws \yii\base\InvalidConfigException
     *
     * @return bool
     */
    private function deletePicture(): bool
    {
        /** @var User $currentUser */
        $currentUser = Yii::$app->user->identity;

        if (!$currentUser->picture) {
            return false;
        }

        $storage = Yii::$app->get('storage');
        $isDeleted = $storage && $storage->deleteFile($currentUser->picture);

        $currentUser->picture = null;
        $isSaved = $currentUser->save(false, ['picture']);

        return $isDeleted && $isSaved;
    }
}
