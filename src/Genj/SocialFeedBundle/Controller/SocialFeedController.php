<?php

namespace Genj\SocialFeedBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
     * @return \Genj\SocialFeedBundle\Entity\PostRepository
     */
    protected function getPostRepository()
    {
        return $this->container->get('genj_social_feed.entity.post_repository');
    }
}