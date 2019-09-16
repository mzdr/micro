# [2.1.1]
###### 2019-09-16

###### Fixed
- Appending `json` or `php` configuration files could result in keys missing. (#1)

# [2.1.0]
###### 2018-05-01

###### Added
- `µ\VERSION` constant representing the version number of micro.
- [Oh Snap!] as default error formatter for BooBoo. ([e526bf0])

###### Changed
- Throw RuntimeException if configuration file is not readable. ([b287474])

###### Fixed
- `template()` resulting in a misconfigured Plates instance. ([4998a87])
- Unnecessary processing of empty/incompatible configuration files. ([97d1d0a])
- [`./bootstrap`] input always resulting in a bail out. ([56b5315])


# [2.0.2]
###### 2018-03-17

Replace [`$objectify()`] function with the one from [Jasny’s PHP functions].


# [2.0.1]
###### 2018-03-14

Fix composer not requiring correct PHP version.


# [2.0.0]
###### 2018-03-11

###### Added
- Caching using [Scrapbook]. ⚡️ ([8b9c210])

###### Changed
- **BREAKING** Put all µ configuration under single “µ” key. ([33505bf])


# [1.0.1]
###### 2018-03-03

Changing the error formatter in your configuration (e.g. `config()->set('µ.error.formatter', 'json')`), should *only* trigger an update if the formatter *is different* to the currently set one.


# 1.0.0
###### 2018-02-19

You gotta start somewhere, right? 🌟

[2.1.1]: https://github.com/mzdr/micro/compare/2.1.0...2.1.1
[2.1.0]: https://github.com/mzdr/micro/compare/2.0.2...2.1.0
[2.0.2]: https://github.com/mzdr/micro/compare/2.0.1...2.0.2
[2.0.1]: https://github.com/mzdr/micro/compare/2.0.0...2.0.1
[2.0.0]: https://github.com/mzdr/micro/compare/1.0.1...2.0.0
[1.0.1]: https://github.com/mzdr/micro/compare/1.0.0...1.0.1

[Jasny’s PHP functions]: https://github.com/jasny/php-functions
[Scrapbook]: https://github.com/matthiasmullie/scrapbook
[Oh Snap!]: https://github.com/mzdr/oh-snap

[8b9c210]: https://github.com/mzdr/micro/commit/8b9c210
[33505bf]: https://github.com/mzdr/micro/commit/33505bf
[56b5315]: https://github.com/mzdr/micro/commit/56b5315
[b287474]: https://github.com/mzdr/micro/commit/b287474
[97d1d0a]: https://github.com/mzdr/micro/commit/97d1d0a
[4998a87]: https://github.com/mzdr/micro/commit/4998a87
[e526bf0]: https://github.com/mzdr/micro/commit/e526bf0

[`$objectify()`]: https://github.com/mzdr/micro/blob/ac77047844a6fa306b742910e71834503710ac29/lib/config.php#L100
[`./bootstrap`]: https://github.com/mzdr/micro/blob/master/bootstrap
