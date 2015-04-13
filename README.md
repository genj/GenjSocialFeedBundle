# GenjSocialFeedBundle

The GenjSocialFeedBundle. Features:

* Scrape social posts from Twitter, Instagram & Facebook pages.
* Display most recent posts


## Requirements

* Symfony 2.5
* PHP 5.4
* GedmoDoctrineExtensions - https://packagist.org/packages/gedmo/doctrine-extensions
* themattharris/tmhoauth
* facebook/php-sdk-v4
* php-instagram-api/php-instagram-api



## Installation


Add this bundle and the facebook/instagram/twitter libraries to your composer.json:

```
    ...
    "require": {
        ...
        "genj/social-feed-bundle": "dev-master"
        ...
```

Then run `composer update`. After that is done, enable the bundle in your AppKernel.php:


### General

```
# app/AppKernel.php

class AppKernel extends Kernel
{

    public function registerBundles() {

        $bundles = array(
            ...
            new Genj\SocialFeedBundle\GenjSocialFeedBundle()
            ...

```

Update your database schema:

```
php app/console doctrine:schema:update --force
```


## Configuration

Copy the social_feed.yml.dist to app/config/social_feed.yml
Add all the needed configurations.
Remove the providers that you don't need.

Check all Possible configuration by dumping the config
```
app/console config:dump-reference GenjSocialFeedBundle
```

Add your basic API tokens to your parameters.yml by adding this to your parameters.yml.dist

    genj_social_feed.twitter.consumer_key: null
    genj_social_feed.twitter.consumer_secret: null
    genj_social_feed.twitter.user_token: null
    genj_social_feed.twitter.user_secret: null

    genj_social_feed.facebook.app_id: null
    genj_social_feed.facebook.app_secret: null
    genj_social_feed.facebook.client_token: null

    genj_social_feed.instagram.client_id: null

and then run the install command of composer

```
php composer.phar install
```

Your config.yml will look like this: (Leave out the providers that you don't use)


    genj_social_feed:
        oAuth:
            twitter:
                consumer_key:    %genj_social_feed.twitter.consumer_key%
                consumer_secret: %genj_social_feed.twitter.consumer_secret%
                user_token:      %genj_social_feed.twitter.user_token%
                user_secret:     %genj_social_feed.twitter.user_secret%
            facebook:
                app_id:          %genj_social_feed.facebook.app_id%
                app_secret:      %genj_social_feed.facebook.app_secret%
                client_token:    %genj_social_feed.facebook.client_token%
            instagram:
                client_id:       %genj_social_feed.instagram.client_id%
        feed_users:
            twitter:
                - nicokaag
            facebook:
                - nicokaag
            instagram:
                - nicokaag


Add the needed vich mapping configuration to your config.yml

    ...
    vich_uploader:
        ...
        mappings:
            ...
            genj_socialfeed_post_file:
                uri_prefix:         /uploads/genjsocialfeedpost
                upload_destination: %kernel.root_dir%/../web/uploads/genjsocialfeedpost
                namer:              vich_uploader.namer_origname
                inject_on_load:     true
            genj_socialfeed_post_author_file:
                uri_prefix:         /uploads/genjsocialfeedpostauthor
                upload_destination: %kernel.root_dir%/../web/uploads/genjsocialfeedpostauthor
                namer:              vich_uploader.namer_origname
                inject_on_load:     true

If you want to make use of the JSON api call to get the social posts, add the following routing:

    genj_social_feed_posts_get:
        pattern:  /api/social-feed/posts
        defaults:
            _controller: GenjSocialFeedBundle:SocialFeed:getPosts
            _format: ~
            max: 5
            provider: ""
        requirements:
            _method: GET
            max: '^\d+$'

## Run feed scraper task

```
php app/console genj:social-feed --provider=facebook
```

provider can be:

* facebook
* twitter
* instagram

## ToDo

* Add tests
