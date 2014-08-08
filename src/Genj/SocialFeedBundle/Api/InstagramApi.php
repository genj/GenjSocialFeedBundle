<?php

namespace Genj\SocialFeedBundle\Api;

use Genj\SocialFeedBundle\Entity\Post;
use Symfony\Component\Config\Definition\Exception\Exception;
use Instagram\Auth;
use Instagram\Instagram;

/**
 * Class FacebookApi
 *
 * @package Genj\SocialFeedBundle\Api
 */
class InstagramApi extends SocialApi
{
    protected $providerName = 'instagram';

    /**
     * @param array $oAuthConfig
     */
    public function __construct($oAuthConfig)
    {
        $this->api = new Instagram;
        $this->api->setClientID($oAuthConfig['instagram']['client_id']);
    }

    /**
     * @param string $username
     *
     * @return array
     */
    public function getUserPosts($username)
    {
        $users = $this->api->searchUsers($username);
        $user = $users->getData()[0];
        $media = $user->getMedia();

        return $media->getData();
    }

    /**
     * @param \stdClass|array $socialPost
     *
     * @return Post
     */
    protected function getMappedPostObject($socialPost)
    {
        $post = new Post();
        $socialPost = $socialPost->getData();

        if (!isset($socialPost->caption->text)) {
            return false;
        }

        $post->setProvider($this->providerName);
        $post->setPostId($socialPost->id);
        $post->setAuthorUsername($socialPost->user->username);
        $post->setAuthorName($socialPost->user->full_name);
        $post->setAuthorFile($socialPost->user->profile_picture);

        $text = $this->getFormattedTextFromPost($socialPost);
        $post->setHeadline($text);
        $post->setBody($text);
        $post->setFile($socialPost->images->standard_resolution->url);
        $post->setLink($socialPost->link);

        $publishAt = new \DateTime();
        $publishAt->setTimestamp($socialPost->created_time);

        $post->setPublishAt($publishAt);
        $post->setIsActive(true);

        return $post;
    }

    protected function getFormattedTextFromPost($socialPost)
    {
        $text = $socialPost->caption->text;

        // Add href for links prefixed with ***:// (*** is most likely to be http(s) or ftp
        $text = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t< ]*)#", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>", $text);
        // Add href for links starting with www or ftp
        $text = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r< ]*)#", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $text);

        return $text;
    }

}