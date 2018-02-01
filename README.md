<p align="center">
    <a href="https://github.com/mzdr/micro">
        <img src="https://mzdr.github.io/assets/images/icons/micro.svg" width="256" alt="micro">
    </a><br><br>
</p>
<p align="center">A tiny multi-tool for your next big <a href="http://php.net/"><em>PHP</em></a> adventure.</p><br>

## Features

- Manage (multiple) **configurations** with [Gestalt].

    > Supports PHP, YAML, INI and JSON files out of the box.

- Handle **databases** with [Medoo].

    > Medoo supports several SQL databases<sup><a name="a1" href="#f1">1</a></sup>, lots of common and complex SQL queries, data mapping and prevention of SQL injection.

- Custom **error handling and formatting** with [BooBoo].

    > BooBoo is an error handler for PHP that allows for the execution of handlers and formatters for viewing and managing errors in development and production. It won’t end up in your stack trace, is built for logging, designed for extension and handles errors non-blocking by default.

- Manage **routing** with [FastRoute].

    > A fast regular expression based request router for PHP. See this [article](http://nikic.github.io/2014/02/18/Fast-request-routing-using-regular-expressions.html) for more details.

- Use _native_ PHP **templates** with [Plates].

    > Plates is designed for developers who prefer to use native PHP templates over compiled template languages, such as Twig or Blade. It supports layouts, inheritance, namespaces, data sharing and comes with built-in escaping helpers.

<br>

## Requirements

[PHP] 7+ and preferably [URL Rewriting] enabled.

<br>

## Installation

It is recommended that you install this framework using [Composer].

```bash
composer require mzdr/micro
```

<br>

## Usage

Basically **µ** is just a bunch of wrapper functions located under a single namespace called `µ`. Every file you see in [`./lib`](./lib) is also available as a function with the same name.

For example:

```php
<?php

// Returns the instance of the config wrapper,
// which would be an object of the Gestalt (@samrap/gestalt) library.
µ\config();

// Would give you access to the FastRoute (@nikic/FastRoute) instance.
µ\router();

// You’ve guessed right!
// That would hand over the instance of the Plates
// (@thephpleague/plates) template engine.
µ\template();

// Have a look at the ./lib folder to get an overview…
```

<br>

## Bootstrapping

You’re in a hurry? Bootstrap a blank **µ** project!

1. Make new project directory and jump into it.

    ```bash
    mkdir fancy-project && cd $_
    ```

2. Install **µ**.

    ```bash
    composer require mzdr/micro
    ```

3. Boostrap blank **µ** project.

    ```bash
    ./vendor/mzdr/micro/bootstrap
    ```

4. **That’s it!** Now how do you check out your project?

    - Create a virtual host and point the document root to the `public` folder, _**or…**_

    - Fire up [PHP’s built-in web server], _**or**…_  
    <sup>(Doesn’t support .htaccess, you have to include assets without cache busting)</sup>

    - Just browse to the `public` folder via your local webserver.  
    <sup>(You probably need to adjust `paths.public` in your `configs/dev.yaml`)</sup>

<br>

## License

This project is licensed under [MIT license].

<br>
<br>
<br>

<sup>
    <a href="#a1" name="f1"><sup>1</sup></a> MySQL, MSSQL, SQLite, MariaDB, PostgreSQL, Sybase, Oracle and many more. <a href="https://github.com/catfan/Medoo#features">catfan/Medoo#features</a>
</sup>

[Gestalt]: https://github.com/samrap/gestalt
[Medoo]: https://github.com/catfan/Medoo
[BooBoo]: https://github.com/thephpleague/booboo
[FastRoute]: https://github.com/nikic/FastRoute
[Plates]: https://github.com/thephpleague/plates
[PHP]: http://php.net
[PHP’s built-in web server]: https://secure.php.net/manual/en/features.commandline.webserver.php
[Composer]: https://getcomposer.org/doc/00-intro.md
[URL Rewriting]: https://github.com/mzdr/micro/wiki/URL-Rewriting
[MIT license]: ./LICENSE
