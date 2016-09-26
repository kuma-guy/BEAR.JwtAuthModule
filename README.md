# BEAR.JwtAuthModule

[![Build Status](https://travis-ci.org/kuma-guy/BEAR.JwtAuthModule.svg?branch=master)](https://travis-ci.org/kuma-guy/BEAR.JwtAuthModule) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/kuma-guy/BEAR.JwtAuthModule/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/kuma-guy/BEAR.JwtAuthModule/?branch=master)

JSON Web Token Authentication for BEAR.Sunday

## Installation

### Composer install

    $ composer require kuma-guy/jwt-auth-module
 
### Module install

This package contains two modules for installing JSON Web Token Authentication.

#### Symmetric way

```php
use Ray\Di\AbstractModule;
use BEAR\JwtAuth\Auth\Auth;
use BEAR\JwtAuth\SymmetricJwtAuthModule;

class AppModule extends AbstractModule
{
    protected function configure()
    {
        $this->install(new SymmetricJwtAuthModule('symmetric algorithm', 'token time-to-live', 'secret'));
        $this->bind(Auth::class)->toProvider(AuthProvider::class)->in(Scope::SINGLETON);
    }
}
```

#### Asymmetric way

```php
use Ray\Di\AbstractModule;
use BEAR\JwtAuth\Auth\Auth;
use BEAR\JwtAuth\AsymmetricJwtAuthModule;

class AppModule extends AbstractModule
{
    protected function configure()
    {
        $this->install(new AsymmetricJwtAuthModule('asymmetric algorithm', 'token time-to-live', 'private key', 'public key', 'pass phrase'));
        $this->bind(Auth::class)->toProvider(AuthProvider::class)->in(Scope::SINGLETON);
    }
}
```

### Authentication

your user is injected by auth provider, you need to add this line in your resource class

```
use AuthInject;
```










