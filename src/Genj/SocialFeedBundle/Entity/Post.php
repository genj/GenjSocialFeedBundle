<?php

namespace Genj\SocialFeedBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Post
 *
 * @ORM\MappedSuperclass
 * @ORM\Table(
 *      name="genj_social_feed_post",
 *      uniqueConstraints={@UniqueConstraint(name="provider_postId_unique",columns={"provider", "post_id"})},
 *      indexes={
 *          @ORM\Index(name="provider_postId", columns={"provider", "post_id"})
 *      }
 * )
 *
 * @Vich\Uploadable
 *
 * @ORM\Entity(repositoryClass="Genj\SocialFeedBundle\Entity\PostRepository")
 */
class Post
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=50, options={"comment" = "The social network the post came from, can be Twitter, Facebook, Instagram, etc."})
     */
    protected $provider;

    /**
     * @ORM\Column(name="post_id", type="string", length=40)
     */
    protected $postId;

    /**
     * @ORM\Column(name="author_username", type="string", length=20)
     */
    protected $authorUsername;

    /**
     * @ORM\Column(name="author_name", type="string", length=20)
     */
    protected $authorName;

    /**
     * @Assert\Image(maxSize="10M")
     * @Vich\UploadableField(mapping="genj_socialfeed_post_author_file", fileNameProperty="authorFile", nullable=true)
     */
    protected $authorFileUpload;

    /**
     * @ORM\Column(name="author_file", type="string", length=255)
     */
    protected $authorFile;

    /**
     * @ORM\Column(type="text", nullable=true, options={"comment" = "Text version of the Post"})
     */
    protected $headline;

    /**
     * @ORM\Column(type="text", nullable=true, options={"comment" = "HTML version of the Post"})
     */
    protected $body;

    /**
     * @Assert\Image(maxSize="10M")
     * @Vich\UploadableField(mapping="genj_socialfeed_post_file", fileNameProperty="file", nullable=true)
     */
    protected $fileUpload;

    /**
     * @ORM\Column(type="string", nullable=true, length=255, options={"comment" = "Url to the avatar of the poser"})
     */
    protected $file;

    /**
     * @ORM\Column(type="string", nullable=true, length=255, options={"comment" = "Url to the post"})
     */
    protected $link;

    /**
     * @ORM\Column(name="publish_at", type="datetime")
     */
    protected $publishAt;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    protected $isActive;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime")
     */
    protected $updatedAt;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Post
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Creates a slug for the post entity to enable thumbnailing, and other bundles that require entities to have a slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->provider .'-'. str_replace('_', '-', $this->postId);
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Post
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set provider
     *
     * @param string $provider
     * @return Post
     */
    public function setProvider($provider)
    {
        $this->provider = $provider;

        return $this;
    }

    /**
     * Get provider
     *
     * @return string 
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * Set headline
     *
     * @param string $headline
     * @return Post
     */
    public function setHeadline($headline)
    {
        $this->headline = $headline;

        return $this;
    }

    /**
     * Get headline
     *
     * @return string 
     */
    public function getHeadline()
    {
        return $this->headline;
    }

    /**
     * Set body
     *
     * @param string $body
     * @return Post
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string 
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set file
     *
     * @param string $file
     * @return Post
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return string 
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param File|null $fileUpload
     */
    public function setFileUpload($fileUpload)
    {
        $this->fileUpload = $fileUpload;
    }

    /**
     * @return File
     */
    public function getFileUpload()
    {
        return $this->fileUpload;
    }

    /**
     * Set publishAt
     *
     * @param \DateTime $publishAt
     * @return Post
     */
    public function setPublishAt($publishAt)
    {
        $this->publishAt = $publishAt;

        return $this;
    }

    /**
     * Get publishAt
     *
     * @return \DateTime 
     */
    public function getPublishAt()
    {
        return $this->publishAt;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return Post
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set postId
     *
     * @param integer $postId
     * @return Post
     */
    public function setPostId($postId)
    {
        $this->postId = $postId;

        return $this;
    }

    /**
     * Get postId
     *
     * @return integer 
     */
    public function getPostId()
    {
        return $this->postId;
    }

    /**
     * Set authorUsername
     *
     * @param string $authorUsername
     * @return Post
     */
    public function setAuthorUsername($authorUsername)
    {
        $this->authorUsername = $authorUsername;

        return $this;
    }

    /**
     * Get authorUsername
     *
     * @return string 
     */
    public function getAuthorUsername()
    {
        return $this->authorUsername;
    }

    /**
     * Set authorName
     *
     * @param string $authorName
     * @return Post
     */
    public function setAuthorName($authorName)
    {
        $this->authorName = $authorName;

        return $this;
    }

    /**
     * Get authorName
     *
     * @return string 
     */
    public function getAuthorName()
    {
        return $this->authorName;
    }

    /**
     * Set authorFile
     *
     * @param string $authorFile
     * @return Post
     */
    public function setAuthorFile($authorFile)
    {
        $this->authorFile = $authorFile;

        return $this;
    }

    /**
     * Get authorFile
     *
     * @return string 
     */
    public function getAuthorFile()
    {
        return $this->authorFile;
    }

    /**
     * @param File|null $authorFileUpload
     */
    public function setAuthorFileUpload($authorFileUpload)
    {
        $this->authorFileUpload = $authorFileUpload;
    }

    /**
     * @return File
     */
    public function getAuthorFileUpload()
    {
        return $this->authorFileUpload;
    }

    /**
     * Set link
     *
     * @param string $link
     * @return Post
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string 
     */
    public function getLink()
    {
        return $this->link;
    }

    public function getAuthorLink()
    {
        switch ($this->getProvider()) {
            case 'twitter':
                return 'https://twitter.com/'. $this->getAuthorUsername();
                break;
            case 'facebook':
                return 'https://facebook.com/'. $this->getAuthorUsername();
                break;
            case 'instagram':
                return 'https://instagram.com/'. $this->getAuthorUsername();
                break;
        }
    }
}
