<?php

declare(strict_types=1);

namespace app\modules\post\controllers;

use app\models\Post;
use app\models\User;
use app\modules\post\models\forms\PostForm;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * Default controller for the `post` module.
 */
class DefaultController extends Controller
{
    /**
     *  Render create post view.
     *
     * @return Response|string
     */
    public function actionCreate()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }

        /** @var User $user */
        $user = Yii::$app->user->identity;
        $postForm = new PostForm($user);

        $isLoaded = $postForm->load(Yii::$app->request->post());

        if ($isLoaded) {
            $postForm->picture = UploadedFile::getInstance($postForm, 'picture');

            if ($postForm->save()) {
                Yii::$app->session->setFlash('success', 'Post created!');

                return $this->goHome();
            }
        }

        return $this->render(
            'create',
            [
                'postForm' => $postForm,
            ]
        );
    }

    /**
     * Renders the create view for the module.
     *
     * @param mixed $id
     *
     * @throws NotFoundHttpException
     *
     * @return string
     */
    public function actionView($id): string
    {
        /* @var $currentUser User */
        $currentUser = Yii::$app->user->identity;

        return $this->render('view', [
            'post' => $this->findPost($id),
            'currentUser' => $currentUser,
        ]);
    }

    /**
     * Handle AJAX post like request.
     *
     * @throws NotFoundHttpException
     *
     * @return array|Response
     */
    public function actionLike()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');
        $post = $this->findPost($id);

        /* @var $currentUser User */
        $currentUser = Yii::$app->user->identity;

        $post->like($currentUser);

        return [
            'success' => true,
            'likesCount' => $post->countLikes(),
        ];
    }

    /**
     * Handle AJAX post unlike request.
     *
     * @throws NotFoundHttpException
     *
     * @return array|Response
     */
    public function actionUnlike()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');

        /* @var $currentUser User */
        $currentUser = Yii::$app->user->identity;
        $post = $this->findPost($id);

        $post->unlike($currentUser);

        return [
            'success' => true,
            'likesCount' => $post->countLikes(),
        ];
    }

    /**
     * @param int $id
     *
     * @throws NotFoundHttpException
     *
     * @return Post
     */
    private function findPost($id): Post
    {
        $post = Post::findOne($id);

        if ($post) {
            return $post;
        }

        throw new NotFoundHttpException();
    }
}
