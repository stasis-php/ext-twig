<?php

declare(strict_types=1);

namespace Stasis\Extension\Twig;

use Stasis\Router\Router;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @internal
 */
final class TwigExtension extends AbstractExtension
{
    private ?Router $router = null;

    #[\Override]
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
            throw new \LogicException(sprintf(
                'Router not initialized. Consider calling %s::setRouter before using.'
                . ' If you see this error, most likely, %s is not correctly registered.',
                self::class,
                StasisTwigExtension::class,
            ));
        }

        return $this->router->get($name)->path;
    }
}
