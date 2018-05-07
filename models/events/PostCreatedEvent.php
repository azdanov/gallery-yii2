<?php

declare(strict_types=1);

namespace app\models\events;

use app\models\Post;
use app\models\User;
use yii\base\Event;

/**
 * Class PostCreatedEvent.
 */
class PostCreatedEvent extends Event
{
    /**
     * Author of created post.
     *
     * @var User
     */
    private $user;

    /**
     * Created post.
     *
     * @var Post
     */
    private $post;

    /**
     * Get author of created post.
     *
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * Get created post.
     *
     * @return Post
     */
    public function getPost(): Post
    {
        return $this->post;
    }

    /**
     * Set author of created post.
     *
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * Set created post.
     *
     * @param Post $post
     */
    public function setPost(Post $post): void
    {
        $this->post = $post;
    }
}
