# ShortUrl Bundle

This bundle adds `/{hash}` endpoints for shortUrl redirect to project.

## Installation

You can install it via composer.json
 ```
 "repositories": [
     {
         "type": "vcs",
         "url": "https://github.com/A-horoshii/url-shrotener"
     }
 ]
 ```

add in you project composer.
```
composer require horoshii/url-shortener-bundle dev-master
```

For using this bundle you have to add configuration files:

* `config/packages/url_shortener.yaml` (optional)
```yaml
url_shortener:
  hash_min_length: 5  (Url short hash min length)
  hash_salt: WERS#$%^3fDSw123 (Url hash salt)
```

* Add migration and database
```
php bin/console make:migration
```
review it and use
```
php bin/console doctrine:migrations:migrate
```

* Configuration routes  `config\routes.yaml` (required)
```yaml
horoshii_short_url_create:
  controller:  Horoshii\UrlShortenerBundle\Controller\UrlShortenerController:shortUrlCreateAction
  path: /short_urls/create
horoshii_short_url_list:
  controller:  Horoshii\UrlShortenerBundle\Controller\UrlShortenerController:shortUrlListAction
  path: /short_urls
  methods: ["GET"]
horoshii_short_url_redirect:
  controller:  Horoshii\UrlShortenerBundle\Controller\UrlShortenerController:shortUrlRedirectAction
  path: /{hash}
  methods: ["GET"]
```