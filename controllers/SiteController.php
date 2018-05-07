<?php

/** @noinspection PhpMissingParentCallCommonInspection */

declare(strict_types=1);

namespace app\controllers;

use app\models\User;
use Yii;
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
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }

        /* @var $currentUser User */
        $currentUser = Yii::$app->user->identity;

        $limit = Yii::$app->params['feedPostLimit'];
        $feedItems = $currentUser->getFeed($limit);

        return $this->render(
            'index',
            compact('feedItems', 'currentUser')
        );
    }
}
