<?php

declare(strict_types=1);

namespace Stasis\Extension\Twig\Tests;

use PHPUnit\Framework\TestCase;
use Stasis\Extension\Twig\TwigExtension;
use Stasis\Router\RouteData;
use Stasis\Router\Router;
use Twig\Environment;
use Twig\Error\RuntimeError;
use Twig\Loader\ArrayLoader;

final class TwigExtensionTest extends TestCase
{
    private Environment $twig;
    private TwigExtension $extension;

    #[\Override]
    public function setUp(): void
    {
        $this->twig = new Environment(new ArrayLoader());
        $this->extension = new TwigExtension();
        $this->twig->addExtension($this->extension);
    }

    public function testPath(): void
    {
        $router = $this->createMock(Router::class);
        $router
            ->expects($this->once())
            ->method('get')
            ->with('home')
            ->willReturn(new RouteData('/home', 'home'));

        $this->extension->setRouter($router);

        $template = 'Path: {{ route_path("home") }}';
        $actual = $this->twig->createTemplate($template)->render();
        self::assertSame('Path: /home', $actual);
    }

    public function testRouterNotSet(): void
    {
        $this->expectException(RuntimeError::class);
        $this->expectExceptionMessage(sprintf(
            'Router not initialized. Consider calling %s::setRouter before using.',
            TwigExtension::class,
        ));

        $template = 'Path: {{ route_path("home") }}';
        $this->twig->createTemplate($template)->render();
    }
}
