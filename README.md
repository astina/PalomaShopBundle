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

      remember_me:
        secret: '%kernel.secret%'
        lifetime: 604800
        path: /
      
  access_control:
  
    # ...
```

## Web Shop Frontend

If you want to use the Paloma shop front-end, you also need to install [Webpack Encore](https://symfony.com/doc/current/frontend.html).
See the [Symfony documentation](https://symfony.com/doc/current/frontend/encore/installation.html) for detailed instructions.

```
composer require encore
yarn install
```

TODO
- yarn add vue vue-loader@^15.0.11 vue-template-compiler --dev
- Encore.enableVueLoader()


That's it!


# Localization

TODO JMSI18nRoutingBundle

# Customizing the front end

TODO
