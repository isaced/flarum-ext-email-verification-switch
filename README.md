# Email verification switch
[![Latest Stable Version](https://poser.pugx.org/isaced/flarum-ext-email-verification-switch/version)](https://packagist.org/packages/isaced/flarum-ext-email-verification-switch) [![Total Downloads](https://poser.pugx.org/isaced/flarum-ext-email-verification-switch/downloads)](https://packagist.org/packages/isaced/flarum-ext-email-verification-switch)

A [Flarum](http://flarum.org) extension. Disable new registered user email verification, will be activated automatically.

Compatible with Flarum 1.x and 2.x (Flarum 2 requires PHP 8.3+).

### How it works

- The account is activated during registration (before it is saved), so **no confirmation email is sent** at all since v1.0.1.
- It only handles activation — it does **not** change group assignment. New users still land in the default group as usual.

### Tip

Skipping email verification lowers the barrier for spam registrations. On public forums, pair this extension with anti-spam protection (e.g. Cloudflare Turnstile / reCAPTCHA).

### Installation

Install with composer:

```sh
composer require isaced/flarum-ext-email-verification-switch
```

### Updating

```sh
composer update isaced/flarum-ext-email-verification-switch
```

### Links

- GitHub Releases - [Changelog](https://github.com/isaced/flarum-ext-email-verification-switch/releases)
- Packagist - [isaced/flarum-ext-email-verification-switch](https://packagist.org/packages/isaced/flarum-ext-email-verification-switch)
- Flarum Discuss Post: [[Extensions] Email verification switch](https://discuss.flarum.org/d/6485)
