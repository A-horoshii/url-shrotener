# ShortUrl Bundle

This bundle adds `/{hash}` endpoints for shortUrl redirect to project.

## Installation

You can install it via composer: 

```
composer require horoshii/url-shortener-bundle
```

For using this bundle you have to add configuration files:

* `config/packages/horoshii_url_shortener.yaml` (optional)
```yaml
horoshii_url_shortener:
  hash_min_length: 5  (Url short hash min length)
  hash_salt: WERS#$%^3fDSw123 (Url hash salt)
```