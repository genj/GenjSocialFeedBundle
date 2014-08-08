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

### SVN External

Add this SVN Repo to your vendor/Genj external property:

```
http://svn.genj.nl/svn/intern/bundles/GenjSocialFeedBundle/trunk/ SocialFeedBundle
```

### Composer

Add this to your composer.json:

```
    ...
    "require": {
        ...
        "themattharris/tmhoauth": "*",
        "facebook/php-sdk-v4" : "4.0.*",
        "php-instagram-api/php-instagram-api": "dev-master"
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

Check all Possible configuration by dumping the config
```
app/console config:dump-reference GenjSocialFeedBundle
```

Add the needed configuration to your config.yml

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