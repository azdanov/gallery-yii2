<?php

declare(strict_types=1);

namespace app\modules\user\controllers;

use app\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Profile controller for the `user` module.
 */
class ProfileController extends Controller
{
    /**
     * @param string $id
     *
     * @throws \yii\base\InvalidArgumentException
     * @throws NotFoundHttpException
     *
     * @return string
     */
    public function actionView(string $id): string
    {
        return $this->render(
            'view',
            [
                'user' => $this->findUser($id),
            ]
        );
    }

    /**
     * @param string $id
     *
     * @throws NotFoundHttpException
     *
     * @return mixed
     */
    private function findUser(string $id)
    {
        $user = User::find()->where(['id' => $id])->one();

        if (!$user) {
            throw new NotFoundHttpException();
        }

        return $user;
    }
}
