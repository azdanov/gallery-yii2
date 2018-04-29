<?php

declare(strict_types=1);

namespace app\modules\user\controllers;

use app\models\User;
use Faker\Factory;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

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
        return $this->render(
            'view',
            [
                'user' => $this->findUser($identifier),
            ]
        );
    }

//    /**
//     * Generate fake user.
//     *
//     * @throws \yii\base\Exception
//     */
//    public function actionGenerate(): void
//    {
//        $faker = Factory::create();
//
//        for ($i = 0; $i < 1000; $i++) {
//            $user = new User(
//                [
//                    'username' => $faker->name,
//                    'email' => $faker->email,
//                    'about' => $faker->text(200),
//                    'nickname' => $faker->regexify('[A-Za-z0-9_]{5,15}'),
//                    'auth_key' => Yii::$app->security->generateRandomString(),
//                    'password_hash' => Yii::$app->security->generateRandomString(),
//                    'created_at' => $time = \time(),
//                    'updated_at' => $time,
//                ]
//            );
//
//            $user->save();
//        }
//    }

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
