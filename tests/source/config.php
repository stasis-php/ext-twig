<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Stasis\Config\ConfigInterface;
use Stasis\Extension\Twig\StasisTwigExtension;
use Stasis\Generator\Distribution\DistributionInterface;
use Stasis\Generator\Distribution\FilesystemDistribution;
use Stasis\Router\Route\Route;
use Stasis\ServiceLocator\NoContainer;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

return new class implements ConfigInterface {
    private readonly Environment $twig;

    public function __construct()
    {
        $loader = new FilesystemLoader();
        $loader->addPath(__DIR__ . '/templates');
        $this->twig = new Environment($loader);
    }

    #[\Override]
    public function routes(): iterable
    {
        return [
            new Route('/', fn() => $this->twig->render('home.html.twig'), 'home'),
            new Route('/about', fn() => 'Page About', 'about'),
        ];
    }

    #[\Override]
    public function container(): ContainerInterface
    {
        return new NoContainer();
    }

    #[\Override]
    public function distribution(): DistributionInterface
    {
        return new FilesystemDistribution(__DIR__ . '/../dist');
    }

    #[\Override]
    public function extensions(): iterable
    {
        return [
            new StasisTwigExtension($this->twig),
        ];
    }
};
