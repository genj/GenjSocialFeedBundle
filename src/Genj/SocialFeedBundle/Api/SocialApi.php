<?php

namespace Genj\SocialFeedBundle\Api;

use Genj\SocialFeedBundle\Entity\Post;

/**
 * Class SocialApi
 */
abstract class SocialApi
{
    /**
     * @var API Class
     */
    protected $api;

    protected $providerName;

    /**
     * @param string $username
     *
     * @return array
     */
    abstract public function getUserPosts($username);

    /**
     * @param \stdClass|array $socialPost
     *
     * @return Post
     */
    abstract protected function getMappedPostObject($socialPost);

    /**
     * @param string $username
     *
     * @return Post[]
     */
    public function getUserPostObjects($username)
    {
        $socialPosts = $this->getUserPosts($username);
        $postObjectList = array();

        foreach ($socialPosts as $socialPost) {
            try {
                $postObject = $this->getMappedPostObject($socialPost);
                if (is_array($postObject) || is_object($postObject)) {
                    $postObjectList[] = $postObject;
                }
            } catch (\Exception $e) { }
        }

        return $postObjectList;
    }
}