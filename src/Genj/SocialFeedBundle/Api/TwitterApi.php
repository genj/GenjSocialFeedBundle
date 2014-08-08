<?php

namespace Genj\SocialFeedBundle\Api;

use Genj\SocialFeedBundle\Entity\Post;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Class TwitterApi
 *
 * @package Genj\SocialFeedBundle\Api
 */
class TwitterApi extends SocialApi
{
    protected $providerName = 'twitter';

    /**
     * @param array $oAuthConfig
     */
    public function __construct($oAuthConfig)
    {
        $this->api = new \tmhOAuth($oAuthConfig['twitter']);
    }

    /**
     * @param string $username
     *
     * @return array
     */
    public function getUserPosts($username)
    {
        $data = $this->requestGet(
            'statuses/user_timeline',
            array(
                'screen_name'     => $username,
                'exclude_replies' => true,
                'include_rts'     => false,
                'count'           => 20
            )
        );

        return $data;
    }

    /**
     * @param \stdClass|array $socialPost
     *
     * @return Post
     */
    protected function getMappedPostObject($socialPost)
    {
        $post = new Post();

        $post->setProvider($this->providerName);
        $post->setPostId($socialPost['id_str']);

        $post->setAuthorUsername($socialPost['user']['screen_name']);
        $post->setAuthorName($socialPost['user']['name']);
        $post->setAuthorFile($socialPost['user']['profile_image_url']);

        $text = $this->getFormattedTextFromPost($socialPost);
        $post->setHeadline($text);
        $post->setBody($text);

        if (isset($socialPost['entities']['media'][0])) {
            $post->setFile($socialPost['entities']['media'][0]['media_url']);
        }

        $post->setLink('https://twitter.com/'. $socialPost['user']['screen_name'] .'/status/'. $socialPost['id_str']);

        $post->setPublishAt(new \DateTime($socialPost['created_at']));
        $post->setIsActive(true);

        return $post;
    }

    protected function getFormattedTextFromPost($socialPost)
    {
        $text = $socialPost['text'];

        if (isset($socialPost['entities']['urls']) && !empty($socialPost['entities']['urls'])) {
            foreach ($socialPost['entities']['urls'] as $url) {
                $text = str_replace($url['url'], $url['expanded_url'], $text);
            }
        }

        // Replace &nbsp; with a normal space
        $text = str_replace(chr('0xC2').chr('0xA0'), " ", $text);
        // Add href for links prefixed with ***:// (*** is most likely to be http(s) or ftp
        $text = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t< ]*)#", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>", $text);
        // Add href for links starting with www or ftp
        $text = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r< ]*)#", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $text);
        // Add links to twitter usernames when mentions are used
        $text = preg_replace("/@(\w+)/", "<a href=\"http://www.twitter.com/\\1\" target=\"_blank\">@\\1</a>", $text);
        // Add link to hashtag pages
        $text = preg_replace("/#(\w+)/", "<a href=\"http://www.twitter.com/search?q=%23\\1\" target=\"_blank\">#\\1</a>", $text);

        return $text;
    }

    protected function requestGet($method, $parameters = array())
    {
        $responseCode = $this->api->request(
            'GET',
            $this->api->url('1.1/'. $method),
            $parameters
        );

        $responseData = json_decode($this->api->response['response'], true);
        if ($responseCode == 200) {
            return $responseData;
        } else {
            throw new Exception($responseData['errors'][0]['message'], $responseData['errors'][0]['code']);
        }
    }

}