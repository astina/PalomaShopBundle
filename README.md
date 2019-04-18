Paloma Shop Bundle
=====

# Installation

Install the bundle using composer:

```
composer require paloma/shop-bundle
```

# Configuration

TODO 

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
        always_remember_me: false
      
  access_control:
    # TODO
```