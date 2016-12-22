<?php
namespace Genj\SocialFeedBundle\Command;

use Genj\SocialFeedBundle\Api\TwitterApi;
use Genj\SocialFeedBundle\Entity\Post;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints\DateTime;

class SocialFeedCommand extends Command {

    const NEWLINE = true;

    private $container;

    private $input;
    private $output;

    protected function configure()
    {
        parent::configure();

        $this->setName('genj:social-feed');
        $this->setDescription('Retrieve Social Media posts from Twitter, Facebook or Instagram');
        $this->addOption('provider', null, InputOption::VALUE_REQUIRED, 'Select which providers to use in a comma seperated list. (No spaces, valid options: facebook, twitter, instagram)');
    }

    /**
     * Executes the current command.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     *
     * @return integer 0 if everything went fine, or an error code
     *
     * @throws \LogicException When this abstract class is not implemented
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->container = $this->getApplication()->getKernel()->getContainer();

        $this->input = $input;
        $this->output = $output;

        if ($input->getOption('provider')) {
            $provider = $input->getOption('provider');
        } else {
            // white text on a red background
            $output->writeln("<error> You have to use the --provider option for this command to work </error>");

            return 500;
        }

        $api = $this->container->get('genj_social_feed.api.'. $provider);

        $feedAccounts = $this->container->getParameter('genj_social_feed.feed_users');
        $feedAccounts = $feedAccounts[$provider];

        foreach ($feedAccounts as $username) {
            $socialPosts = $api->getUserPostObjects($username);

            $this->storePostObjects($socialPosts, $provider, $username);
        }

        return 0;
    }

    /**
     * @param array  $socialPosts
     * @param string $provider
     * @param string $username
     *
     * @return void
     */
    protected function storePostObjects($socialPosts, $provider, $username = '')
    {
        $newPostCount = 0;

        $postRepository = $this->container->get('genj_social_feed.entity.post_repository');
        $entityManager = $this->container->get('doctrine')->getManager();

        foreach ($socialPosts as $socialPost) {
            $post = $postRepository->findOneBy(array('provider' => $provider, 'postId' => $socialPost->getPostId()));

            if (!$post) {

                if ($socialPost) {
                    $socialPostFile = $socialPost->getFile();
                    if (!empty($socialPostFile)) {
                        $socialPost->setFileUpload($this->getUploadedFileFromUrl($socialPostFile));
                    }

                    $socialPostAuthorFile = $socialPost->getAuthorFile();
                    if (!empty($socialPostAuthorFile)) {
                        $socialPost->setAuthorFileUpload($this->getUploadedFileFromUrl($socialPostAuthorFile));
                    }

                    $entityManager->persist($socialPost);
                    $entityManager->flush();
                    $newPostCount++;
                }
            }
        }

        $this->output->writeln('New '. $provider .' posts added for '. $username .': '. $newPostCount);
        $this->output->writeln('success');
    }

    protected function getUploadedFileFromUrl($url)
    {
        $storageFile = tempnam(sys_get_temp_dir(), 'GenjSocialFeedBundle');

        $fileContents = @file_get_contents($url);

        if (!$fileContents) {
            return null;
        }

        file_put_contents($storageFile, $fileContents);

        return new UploadedFile($storageFile, 'SocialPost', null, null, null, true);
    }
}