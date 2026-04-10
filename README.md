# phpnomad/facade

[![Latest Version](https://img.shields.io/packagist/v/phpnomad/facade.svg)](https://packagist.org/packages/phpnomad/facade)
[![Total Downloads](https://img.shields.io/packagist/dt/phpnomad/facade.svg)](https://packagist.org/packages/phpnomad/facade)
[![PHP Version](https://img.shields.io/packagist/php-v/phpnomad/facade.svg)](https://packagist.org/packages/phpnomad/facade)
[![License](https://img.shields.io/packagist/l/phpnomad/facade.svg)](https://packagist.org/packages/phpnomad/facade)

`phpnomad/facade` provides the base classes and interfaces for building static-bound service facades in PHPNomad. The abstract `Facade` class resolves its target through the DI container on demand, and the `HasFacades` interface lets an initializer register its facades with the framework during bootstrap. The pattern is how you end up calling `Cache::get($key)` or `Logger::error($message)` from anywhere in your code without wiring up the container at each call site.

## Installation

```bash
composer require phpnomad/facade
```

Most applications pull this in as a transitive dependency of `phpnomad/core`, which ships concrete facades for cache, events, logging, templates, and URL and path resolution.

## Overview

- `PHPNomad\Facade\Abstracts\Facade` is a generic, container-aware base class. A concrete subclass implements `abstractInstance()` to name the bound interface, and the base pulls the resolved implementation from the DI container when a facade method is called.
- `PHPNomad\Facade\Interfaces\HasFacades` is the contract initializers implement to advertise their facades. Return an array of facade instances from `getFacades()` and the framework hands each one a container reference during the loader phase.
- Resolution is lazy. The container is queried when a facade method fires, not when the facade is registered, so services with expensive dependencies stay cheap until something actually calls them.
- When container resolution throws a `DiException`, the base attempts to log a critical error through `LoggerStrategy` with the failing abstraction and the container reference before rethrowing, so you get a breadcrumb instead of a bare stack trace.

For working examples of concrete facades built on this base, see the `Facades/` directory in `phpnomad/core` (Cache, Event, Logger, Template, UrlResolver, PathResolver, InstanceProvider).

## Documentation

Full framework documentation lives at [phpnomad.com](https://phpnomad.com).

## License

MIT. See [LICENSE.txt](LICENSE.txt).
