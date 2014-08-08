<?php

namespace Genj\SocialFeedBundle\Tests\Entity;

use Genj\SocialFeedBundle\Entity\Post;

/**
 * Class PostTest
 *
 * @package Genj\SocialFeedBundle\Tests\Entity
 */
class PostTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test for Post::__toString()
     */
    public function testToString()
    {
        $post = new Post();
        $post->setHeadline('Hello World!');

        $postToString = (string) $post;

        $this->assertEquals('Hello World!', $postToString);
    }
}