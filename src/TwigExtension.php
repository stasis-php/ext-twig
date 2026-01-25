<?php

declare(strict_types=1);

namespace Stasis\Ext\Twig;

use Stasis\Router\Router;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class TwigExtension extends AbstractExtension
{
    private ?Router $router = null;

    public function getFunctions(): array
    {
        return [
            new TwigFunction('path', $this->getPath(...)),
        ];
    }

    public function setRouter(Router $router): void
    {
        $this->router = $router;
    }

    private function getPath(string $name): string
    {
        if ($this->router === null) {
            throw new \LogicException(sprintf('Router not initialized. Consider calling %s::setRouter before using.', self::class));
        }

        return $this->router->get($name)->path;
    }
}
