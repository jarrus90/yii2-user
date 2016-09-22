# Yii2-user

[![Build Status](https://travis-ci.org/jarrus90/yii2-user.svg?branch=master)](https://travis-ci.org/jarrus90/yii2-user)

Most of web applications provide a way for users to register, log in or reset
their forgotten passwords. Rather than re-implementing this on each application,
you can use Yii2-user which is a flexible user management module for Yii2 that
handles common tasks such as registration, authentication and password retrieval.
The latest version includes following features:

* Registration with an optional confirmation per mail
* Registration via social networks
* Password recovery
* Account and profile management
* Console commands
* User management interface

> **NOTE:** Module is in initial development. Anything may change at any time.

## Contributing to this project

Anyone and everyone is welcome to contribute. Please take a moment to review the [guidelines for contributing](CONTRIBUTING.md).

## License

Yii2-user is released under the BSD-3-Clause License. See the bundled [LICENSE.md](LICENSE.md) for details.

##Requirements

YII 2.0

##Usage

1) Install with Composer

~~~php

"require": {
    "jarrus90/yii2-user": "1.*",
},

php composer.phar update

~~~

### Thanks to

[dektrium/yii2-user](https://github.com/dektrium/yii2-user) and [dektrium/yii2-rbac](https://github.com/dektrium/yii2-rbac)
Main code and ideas are taken from this projects.