# Codegener

![tests](https://github.com/MilesChou/codegener/workflows/tests/badge.svg)
[![codecov](https://codecov.io/gh/MilesChou/codegener/branch/master/graph/badge.svg)](https://codecov.io/gh/MilesChou/codegener)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/28b1ac29684847789995fe1c46f598ac)](https://www.codacy.com/manual/MilesChou/codegener)
[![Latest Stable Version](https://poser.pugx.org/MilesChou/codegener/v/stable)](https://packagist.org/packages/MilesChou/codegener)
[![Total Downloads](https://poser.pugx.org/MilesChou/codegener/d/total.svg)](https://packagist.org/packages/MilesChou/codegener)
[![License](https://poser.pugx.org/MilesChou/codegener/license)](https://packagist.org/packages/MilesChou/codegener)
[![Beerpay](https://beerpay.io/MilesChou/codegener/badge.svg?style=flat)](https://beerpay.io/MilesChou/codegener)

The helper for generate code.

## Concept

Sometimes, we need to generate many code, like [Scaffold](https://en.wikipedia.org/wiki/Scaffold_(programming)), [compiled code](https://en.wikipedia.org/wiki/Code_generation_(compiler)), even document.

This package can help to write code easily.

## Usage

Writer class need [Laravel Filesystem](), it's testable, and need instance implemented PSR-3 Logger interface.

```php
public function __construct(Filesystem $filesystem, LoggerInterface $logger)
```

Use `write()` method to put code instantly. Codegener will overwrite when `$overwrite` is true.

```php
public function write(string $path, $content, bool $overwrite = false): void
```

Use `writeMass` if need generate many code.

```php
public function writeMass(iterable $contents, bool $overwrite = false): void
```

## Traits

Following traits is helper for process env and path.

* [Path](/src/Traits/Path.php)

## Example

Following is an example code.

```php
$writer->setBasePath('/path/to/your/project');

$code = [
    'some-foo' => 'foo',
    'some-bar' => 'bar',
];

$writer->writeMass($code);
```

Codegener will generate two files.

```
$ cat /path/to/your/project/some-foo
foo
$ cat /path/to/your/project/some-bar
bar
```

## Example Projects

* [Schemarkdown](https://github.com/MilesChou/schemarkdown)
* [Laravel Eloquent Generator](https://github.com/104corp/laravel-eloquent-generator)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
