<?php

namespace Genj\SocialFeedBundle\Tests\Fixtures\Entity;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Genj\SocialFeedBundle\Entity\Post;

/**
 * Class PostFixture
 *
 * @package Genj\SocialFeedBundle\Tests\Fixtures\Entity
 */
class PostFixture extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        for ($ctr = 0; $ctr < 10; $ctr++) {
            $post = new Post();
            $post->setHeadline('A very interesting social feed post  ' . $ctr);
            $post->setProvider(array_rand(array('facebook', 'twitter', 'instagram')));
            $post->setAuthorName('Author name');
            $post->setAuthorUsername('Author username');
            $post->setAuthorFile('author-file.jpg');
            $post->setBody('Lorem ipsum dolor sit amet,
consectetur adipiscing elit. Curabitur posuere orci eu mi fermentum pulvinar. Curabitur laoreet, mi ac dictum mattis.');
            $post->setIsActive(true);
            $post->setPostId('123abc' . $ctr);
            $post->setPublishAt(new \DateTime());

            $manager->persist($post);
            $manager->flush();
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }
}
