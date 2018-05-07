<?php

declare(strict_types=1);

namespace app\components;

// phpcs:disable Zend.NamingConventions.ValidVariableName.NotCamelCaps

use app\models\events\PostCreatedEvent;
use app\models\Feed;
use app\models\Post;
use app\models\User;
use yii\base\Component;
use yii\base\InvalidConfigException;

/**
 * Feed component.
 */
class FeedService extends Component
{
    /**
     * Add post to feed of author subscribers.
     *
     * @param PostCreatedEvent $event
     *
     * @throws InvalidConfigException
     */
    public function addToFeeds(PostCreatedEvent $event): void
    {
        /* @var $user User */
        $user = $event->getUser();
        /* @var $user Post */
        $post = $event->getPost();

        $followers = $user->getFollowers();

        foreach ($followers as $follower) {
            $feedItem = new Feed();
            $feedItem->user_id = $follower['id'];
            $feedItem->author_id = $user->id;
            $feedItem->author_name = $user->username;
            $feedItem->author_nickname = $user->getNickname();
            $feedItem->author_picture = $user->getPicture();
            $feedItem->post_id = $post->id;
            $feedItem->post_filename = $post->filename;
            $feedItem->post_description = $post->description;
            $feedItem->post_created_at = $post->created_at;
            $feedItem->save();
        }
    }
}
