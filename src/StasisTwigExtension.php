<?php

declare(strict_types=1);

namespace Stasis\Extension\Twig;

use Stasis\EventDispatcher\RouterReady\RouterReadyData;
use Stasis\EventDispatcher\RouterReady\RouterReadyListenerInterface;
use Stasis\Extension\ExtensionInterface;
use Twig\Environment;

final class StasisTwigExtension implements ExtensionInterface, RouterReadyListenerInterface
{
    private readonly TwigExtension $twigExtension;

    public function __construct(
        public readonly Environment $twig,
    ) {
        $this->twigExtension = new TwigExtension();
        $this->twig->addExtension($this->twigExtension);
    }

    #[\Override]
    public function listeners(): iterable
    {
        return [$this];
    }

    #[\Override]
    public function onRouterReady(RouterReadyData $data): void
    {
        $this->twigExtension->setRouter($data->router);
    }
}
