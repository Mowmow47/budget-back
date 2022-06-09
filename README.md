# Budget-back

## Description

This project is a SPA (Single-Page Application) which consists in a back-end (Symfony) communicating through an API with the front-end (VueJS). It's meant to be a budget management application which I'll personally use.

The application will allow the user to add multiple bank accounts and import payment transactions ,manually only at first, the banks APIs are not "open to anyone". The user will also be able to see his account balance, previsions of the account balance over a certain period of time, categorize his spendings and see its largest expense categories and many more...

## Live Demo

There is no live demo at this time as the app is still in its early stages of development.

## Technologies used

The back-end uses the following technologies:

- [PHP 8.1](https://www.php.net/)
- [Composer](https://getcomposer.org/)
- [Symfony 6.1](https://symfony.com/)
    - CORS management: [NelmioCorsBundle](https://github.com/nelmio/NelmioCorsBundle)
    - Fixtures: [Nelmio Alice](https://github.com/nelmio/alice)
    - JWT authentication: [LexikJWTAuthenticationBundle](https://github.com/lexik/LexikJWTAuthenticationBundle) 
- [MariaDB 10.7](https://mariadb.org/)
- [Prettier](https://prettier.io/)
- [Git](https://git-scm.com/)

If you're curious, don't forget to take a look at the [front-end repository](https://github.com/Mowmow47/budget-front) as well.
