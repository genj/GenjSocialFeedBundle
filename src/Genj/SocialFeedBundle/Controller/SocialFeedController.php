<?php

namespace Genj\SocialFeedBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class ArticleController
 *
 * @package Genj\ArticleBundle\Controller
 */
class SocialFeedController extends Controller
{
    /**
     * @param string $slug
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($max = 5, $provider = null)
    {
        $posts = $this->getPostRepository()->retrieveMostRecentPublicPosts($max, $provider)->getResult();

        return $this->render(
            'GenjSocialFeedBundle:Feed:show.html.twig',
            array('posts' => $posts)
        );
    }

    /**
     * @return \Genj\ArticleBundle\Entity\ArticleRepository
     */
    protected function getPostRepository()
    {
        return $this->container->get('genj_social_feed.entity.post_repository');
    }
}