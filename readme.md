# CSD

Este paquete te permite encriptar, validar y convertir archivos .cer y .key en .pem para la facturacion electronica en méxico.

- [Introduction](#introduction)
- [Installation](#installation)
  - [Enabling cache](#enabling-cache)
- [Usage](#usage)
  - [Creating roles and abilities](#creating-roles-and-abilities)
  - [Assigning roles to a user](#assigning-roles-to-a-user)
  - [Giving a user an ability directly](#giving-a-user-an-ability-directly)
  - [Restricting an ability to a model](#restricting-an-ability-to-a-model)
  - [Retracting a role from a user](#retracting-a-role-from-a-user)
  - [Removing an ability](#removing-an-ability)
  - [Checking a user's roles](#checking-a-users-roles)
  - [Getting all abilities for a user](#getting-all-abilities-for-a-user)
  - [Authorizing users](#authorizing-users)
  - [Refreshing the cache](#refreshing-the-cache)
  - [Seeding roles and abilities](#seeding-roles-and-abilities)
- [Cheat sheet](#cheat-sheet)
- [License](#license)

## Introduction

Bouncer provides a mechanism to handle roles and abilities in [Laravel's ACL](http://laravel.com/docs/5.1/authorization). With an expressive and fluent syntax, it stays out of your way as much as possible: use it when you want, ignore it when you don't.

For a quick, glanceable list of Bouncer's features, check out [the cheat sheet](#cheat-sheet).

Bouncer works well with other abilities you have hard-coded in your own app. Your code always takes precedence: if your code allows an action, the bouncer will not interfere.


Once installed, you can simply tell the bouncer what you want to allow at the gate:

```php
// Give a user the ability to create posts
Bouncer::allow($user)->to('create', Post::class);

// Alternatively, do it through a role
Bouncer::allow('admin')->to('create', Post::class);
Bouncer::assign('admin')->to($user);

// You can also grant an ability only to a specific model
Bouncer::allow($user)->to('edit', $post);
```