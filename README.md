<p align="center">
    <a href="https://github.com/mzdr/micro">
        <img src="media/logo.svg" alt="micro">
    </a><br><br>
</p>
<p align="center">A tiny multi-tool for your next big <a href="http://php.net/"><em>PHP</em></a> adventure.</p><br>

## Features

- Manage (multiple) **configurations** with [Gestalt].

    > Supports PHP, YAML, INI and JSON files out of the box.

- Handle **databases** with [Medoo].

    > Medoo supports several SQL databases<sup><a name="a1" href="#f1">1</a></sup>, lots of common and complex SQL queries, data mapping and prevention of SQL injection.

- Custom **error handling and formatting** with [BooBoo].

    > BooBoo is an error handler for PHP that allows for the execution of handlers and formatters for viewing and managing errors in development and production. It won't end up in your stack trace, is built for logging, designed for extension and handles errors non-blocking by default.

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

`composer require mzdr/micro`

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
[Composer]: https://getcomposer.org/doc/00-intro.md
[URL Rewriting]: https://github.com/mzdr/micro/wiki/URL-Rewriting
[MIT license]: ./LICENSE
