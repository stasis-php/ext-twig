<?php

declare(strict_types=1);

namespace Stasis\Ext\Twig;

use Stasis\EventDispatcher\Event\SiteGenerate\SiteGenerateData;
use Stasis\EventDispatcher\Listener\SiteGenerateInterface;
use Stasis\Extension\ExtensionInterface;
use Twig\Environment;

class StasisTwigExtension implements ExtensionInterface, SiteGenerateInterface
{
    private readonly TwigExtension $twigExtension;

    public function __construct(
        public readonly Environment $twig,
    ) {
        $this->twigExtension = new TwigExtension();
        $this->twig->addExtension($this->twigExtension);
    }

    public function listeners(): iterable
    {
        return [$this];
    }

    public function onSiteGenerate(SiteGenerateData $data): void
    {
        $this->twigExtension->setRouter($data->router);
    }
}
