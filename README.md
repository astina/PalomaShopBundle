Paloma Shop Bundle
=====

Symfony bundle with web shop frontend for Paloma.

**Note:** this bundle is in development and is not ready for production use.

# Create a new project

See https://symfony.com/doc/current/setup.html

```
composer create-project symfony/website-skeleton my-project
```

Install the Paloma shop bundle using composer:

```
composer require paloma/shop-bundle
```

Enable the bundle by adding it in `config/bundles.php`:

```php
<?php

return [
    // ...
    Paloma\ShopBundle\PalomaShopBundle::class => ['all' => true],
];
```

# Configuration

## Backend configuration

Configure the bundle in `config/packages/paloma_shop.yaml`:

```yaml
paloma_shop:
  client:
    # this URL probably differs for each environment 
    base_url: 'https://[project].paloma.one/api'
```

Add the Paloma API key to `.env.local`:

```
# .env.local
PALOMA_API_KEY=mysecretapikey
```

## Routes

Load the routing config in `config/routes.yaml`:

```yaml
paloma:
  resource: '@PalomaShopBundle/Resources/config/routes/all.yaml'
```

## Security

Configure `config/packages/security.yaml` to use Paloma for security: 

```yaml
security:
  
  providers:
    paloma_shop.security.user_provider:
      id: paloma_shop.security.user_provider
    
  firewalls:
    
    # ...
      
    paloma:

      anonymous: true

      guard:
        authenticators:
          - paloma_shop.security.authenticator

      logout:
        path: paloma_security_logout
        success_handler: 'paloma_shop.security.logout_success_handler'
        invalidate_session: false

      remember_me:
        secret: '%kernel.secret%'
        lifetime: 604800
        path: /
      
  access_control:
    # Note: if you use locale prefixes for routes, this path needs to be something like '^/.+/customer/account'.
    - { path: '^/customer/account', roles: ROLE_CUSTOMER }
```

## Web Shop Frontend

If you want to use the Paloma shop front-end, you also need to install [Webpack Encore](https://symfony.com/doc/current/frontend.html).
See the [Symfony documentation](https://symfony.com/doc/current/frontend/encore/installation.html) for detailed instructions.

```
composer require encore
yarn install
```

That's it!

# Development

Run the development PHP server by running:

```
bin/console server:start
```

Run Yarn to build front end resources for development:

```
yarn run dev
```

or 

```
yarn run watch
```

# Customizing the front end

#### Twig templates

Overwrite templates from the `PalomaShopBundle` by putting template files in `templates/bundles/PalomaShopBundle/`.

To use the bundled error page templates, create Twig templates and like described here: 
https://symfony.com/doc/current/controller/error_pages.html#overriding-the-default-error-templates

For 404 errors:
```
{# templates/TwigBundle/Exception/error404.html.twig #}
{% extends '@PalomaShop/error/error404.html.twig' %}
```

For all other errors:
```
{# templates/TwigBundle/Exception/error.html.twig #}
{% extends '@PalomaShop/error/error.html.twig' %}
```

#### CSS

Set SCSS variables and/or overload CSS classes in `assets/css/app.scss`.
See `vendor/paloma/shop-bundle/Resources/assets/css/_config.scss` for available variables.
