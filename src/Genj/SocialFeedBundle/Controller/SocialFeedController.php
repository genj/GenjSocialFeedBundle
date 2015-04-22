<?php

namespace Genj\SocialFeedBundle\Controller;

use Genj\SocialFeedBundle\Entity\Post;
use Genj\ThumbnailBundle\Twig\ThumbnailExtension;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class SocialFeedController
 *
 * @package Genj\SocialFeedBundle\Controller
 */
class SocialFeedController extends Controller
{
    /**
     * @param int    $max
     * @param string $provider
     * @param array  $authorUsernames
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($max = 5, $provider = null, $authorUsernames = array())
    {
        $posts = $this->getPostRepository()->retrieveMostRecentPublicPosts($max, $provider, $authorUsernames);

        return $this->render(
            'GenjSocialFeedBundle:Feed:show.html.twig',
            array('posts' => $posts)
        );
    }

    /**
     * @param Request $request
     * @param int     $max
     * @param string  $provider
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getPostsAction(Request $request, $max = 5, $provider = null)
    {
        $posts = $this->getPostRepository()->retrieveMostRecentPublicPosts($max, $provider);

        /** @var ThumbnailExtension $thumbnailService */
        $thumbnailService = $this->get('genj_thumbnail.twig.thumbnail_extension');

        $postList = array();

        /** @var Post $post */
        foreach ($posts as $post) {
            $postData = array(
                'provider' => $post->getProvider(),
                'postId'   => $post->getPostId(),
                'author' => array(
                    'username' => $post->getAuthorUsername(),
                    'name'     => $post->getAuthorName(),
                    'avatar'   => (string) $thumbnailService->getThumbnailPath($post, 'authorFileUpload', 'teaser')
                ),
                'body'      => $post->getHeadline(),
                'image'     => (string) $thumbnailService->getThumbnailPath($post, 'fileUpload', 'teaser'),
                'link'      => $post->getLink(),
                'publishAt' => $post->getPublishAt()->format('Y-m-d H:i:s')
            );

            $postList[] = $postData;
        }

        $response = new JsonResponse(array('posts' => $postList), 200, array());

        $callback = $request->get('callback', false);
        if ($callback) {
            $response->setCallback($callback);
        }

        return $response;
    }



    /**
     * @return \Genj\SocialFeedBundle\Entity\PostRepository
     */
    protected function getPostRepository()
    {
        return $this->container->get('genj_social_feed.entity.post_repository');
    }
}