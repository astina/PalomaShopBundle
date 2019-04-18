Paloma Shop Bundle
=====

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

Configure the bundle in `config/packages/paloma_shop.yaml`:

```yaml
paloma_shop:
  client:
    # this URL probably differs for each environment 
    base_url: 'https://my-project.paloma.one/api'
```

Add the Paloma API key to `.env.local`:

```
# .env.local
PALOMA_API_KEY=mysecretapikey
```

Load the routing config in `config/routes.yaml:
{% extends '@PalomaShop/layout.html.twig' %}

{% block title %}Log in!{% endblock %}

{% block body %}
    <form method="post">
        {% if error %}
            <div class="alert alert-danger">{{ error.messageKey|trans(error.messageData, 'security') }}</div>
        {% endif %}

        <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
        <label for="inputUsername" class="sr-only">Username</label>
        <input type="text" value="{{ last_username }}" name="username" id="inputUsername" class="form-control" placeholder="Username" required autofocus>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required>

        <input type="hidden" name="_csrf_token"
               value="{{ csrf_token('authenticate') }}"
        >

        <div class="checkbox mb-3">
            <label>
                <input type="checkbox" name="_remember_me" checked> Remember me
            </label>
        </div>

        <button class="btn btn-lg btn-primary" type="submit">
            Sign in
        </button>
    </form>
{% endblock %}

```yaml
paloma:
  resource: '@PalomaShopBundle/Resources/config/routes.yaml'
```

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