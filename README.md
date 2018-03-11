<p align="center">
    <a href="https://github.com/mzdr/micro">
        <img src="https://mzdr.github.io/assets/images/icons/micro.svg" width="256" alt="micro">
    </a><br><br>
</p>
<p align="center">A <em>tiny</em> multi-tool for your next big <a href="http://php.net/">PHP</a> adventure.<sup><a name="a1" href="#f1">1</a></sup></p><br>

## Features

- Easily manage your application’s **configuration** with [Gestalt].

    > Supports PHP, YAML, INI and JSON files out of the box.

- Handle various **databases** with [Medoo].

    > Medoo supports several SQL databases<sup><a name="a2" href="#f2">2</a></sup>, lots of common and complex SQL queries, data mapping and prevention of SQL injection.

- Have **error handling and formatting** to your preference with [BooBoo].

    > BooBoo is an error handler for PHP that allows for the execution of handlers and formatters for viewing and managing errors in development and production. It won’t end up in your stack trace, is built for logging, designed for extension and handles errors non-blocking by default.

- Set up lightning fast **routing** with [FastRoute].

    > A fast regular expression based request router for PHP. See this [article](http://nikic.github.io/2014/02/18/Fast-request-routing-using-regular-expressions.html) for more details.

- Use _native_ PHP **templates** with [Plates].

    > Plates is designed for developers who prefer to use native PHP templates over compiled template languages, such as Twig or Blade. It supports layouts, inheritance, namespaces, data sharing and comes with built-in escaping helpers.

- ⚡️ Speed up your (dynamic) web applications by **caching** with [Scrapbook].

    > PHP cache library, with adapters for e.g. Memcached, Redis, Couchbase, APC(u), SQL and additional capabilities (e.g. transactions, stampede protection) built on top.

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

**micro** is basically just a bunch of _wrapper functions_ located under a _single_ namespace called `µ`. Every file you see in [`./lib`] is also available as a function with the same name.

Let me talk in code to you… 😎

```php
<?php

// Returns the instance of the Gestalt (@samrap/gestalt) library.
µ\config();

// You can pretty much do anything you like with it.
$config = µ\config();
$special_var = $config->get('my.stored.variable');


// Need to register routes with the
// FastRoute (@nikic/FastRoute) instance?
µ\router()->get('/home', function () {

    // 🌈 Use your imagination…

    // How about we use the Plates
    // (@thephpleague/plates) template engine? 🤩
    echo µ\template()->render('home');
});


// Tired of typing µ? 😫 Join the club!
namespace µ {
    router()->get('/', function () {
        $key = 'my-heavy-op';
        $ttl = 300;
        $value = "cached for $ttl seconds.";

        if (cache()->has($key) === false) {
            sleep(2); // So so heavy…

            cache()->set($key, $value, $ttl);

            return $value;
        }

        return cache()->get($key);
    });
}

// Out there in strange places? 👽 Import it!
namespace alien {
    use function µ\config;

    $done = config()->get('get.it.done');
}
```

<br>

Just follow the official documentation of each library listed below or jump into the [`./lib`] folder to get a look under the hood.

| Function           | Documentation                            | 
| ------------------ | ---------------------------------------- | 
| `µ\config()`       | https://github.com/samrap/gestalt-docs   |
| `µ\database()`     | https://medoo.in/doc                     |
| `µ\error()`        | http://booboo.thephpleague.com/          |
| `µ\router()`       | https://github.com/nikic/FastRoute       |
| `µ\template()`     | http://platesphp.com/                    |
| `µ\cache()`        | https://www.scrapbook.cash/              |

<br>

## Bootstrapping

You’re in a hurry? Bootstrap a [blank], ready-to-view **µ** project!

1. Make new project directory and jump into it.

    ```bash
    mkdir fancy-project && cd $_
    ```

2. Install **µ**.

    ```bash
    composer require mzdr/micro
    ```

3. Boostrap it.

    ```bash
    ./vendor/mzdr/micro/bootstrap
    ```

4. **That’s it!** Now how do you check out your project?

    - Create a virtual host and point the document root to the `public` folder, _**or…**_

    - Fire up [PHP’s built-in web server], _**or**…_  
    <sup>(Doesn’t support .htaccess, you have to [include assets](https://github.com/mzdr/micro/blob/develop/boilerplates/blank/views/_layouts/default.php#L8) _without_ <strike>$this->asset(…)</strike> cache busting)</sup>

    - Just browse to the `public` folder via your local webserver.  
    <sup>(You probably need to adjust `µ.paths.public` in your `configs/master.yaml`)</sup>


<br>

## License

This project is licensed under [MIT license].

<br>
<br>
<br>

<sup>
    <a href="#a1" name="f1"><sup>1</sup></a> It may be tiny and powerful, but it’s <em>not</em> the right tool for <em>every</em> job.<br>
    <a href="#a2" name="f2"><sup>2</sup></a> MySQL, MSSQL, SQLite, MariaDB, PostgreSQL, Sybase, Oracle and many more. <a href="https://github.com/catfan/Medoo#features">catfan/Medoo#features</a>
</sup>

[Gestalt]: https://github.com/samrap/gestalt
[Medoo]: https://github.com/catfan/Medoo
[BooBoo]: https://github.com/thephpleague/booboo
[FastRoute]: https://github.com/nikic/FastRoute
[Plates]: https://github.com/thephpleague/plates
[Scrapbook]: https://github.com/matthiasmullie/scrapbook#keyvaluestore
[PHP]: http://php.net
[PHP’s built-in web server]: https://secure.php.net/manual/en/features.commandline.webserver.php
[Composer]: https://getcomposer.org/doc/00-intro.md
[URL Rewriting]: https://github.com/mzdr/micro/wiki/URL-Rewriting
[MIT license]: ./LICENSE
[`./lib`]: ./lib
[blank]: ./boilerplates/blank
