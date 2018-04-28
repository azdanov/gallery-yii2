<?php

/** @noinspection PhpMissingParentCallCommonInspection */

declare(strict_types=1);

namespace app\controllers;

use app\models\User;
use yii\web\Controller;
use yii\web\ErrorAction;

/**
 * Site controller.
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => ErrorAction::class,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @throws \yii\base\InvalidArgumentException
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $users = User::find()->all();

        return $this->render(
            'index',
            [
                'users' => $users,
            ]
        );
    }
}
