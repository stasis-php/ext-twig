# Stasis Twig Extension

Stasis Twig Extension is an adapter that integrates [Twig](https://twig.symfony.com/) templating engine with [Stasis](https://github.com/stasis-php/stasis).
Extension integrates with a Stasis routing system and adds `route_path` twig function that returns a path to a page.

## Installation
Install the latest version with [Composer](https://getcomposer.org/):
```shell
composer require stasis-php/ext-twig
```

## Configuration
Register extension in your `stasis.php` configuration file. For example:
```php
<?php

declare(strict_types=1);

use Stasis\Config\ConfigInterface;
use Stasis\Extension\Twig\StasisTwigExtension;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

return new class implements ConfigInterface {
    private readonly Environment $twig;

    // 1. Create a Twig environment. You can do this in a separate method or factory. This is just an example.
    public function __construct()
    {
        $loader = new FilesystemLoader();
        $loader->addPath(__DIR__ . '/templates');
        $this->twig = new Environment($loader);
    }

    // 2. Register the extension. It's important to register it after creating a Twig environment.
    public function extensions(): iterable
    {
        return [
            new StasisTwigExtension($this->twig),
        ];
    }

    // 3. Register a route that renders a template.
    public function routes(): iterable
    {
        return [
            new Route('/', fn() => $this->twig->render('home.html.twig'), 'home'),
        ];
    }
};
```

> [!NOTE]  
> The configuration example above shows only the relevant configuration, omitting unrelated parts.

## Usage
In template, use `route_path` function to generate a path to a page:
```twig
<a href="{{ route_path('home') }}">Home</a>
```

> [!NOTE]  
> To generate a path to a page - page name must be specified.

## Credits
[Volodymyr Stelmakh](https://github.com/vstelmakh)  
Licensed under the MIT License. See [LICENSE](LICENSE) for more information.  
