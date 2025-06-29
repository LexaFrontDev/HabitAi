<?php

namespace App\Infrastructure\Twig;

use Symfony\Component\HttpKernel\KernelInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ViteAssetExtension extends AbstractExtension
{
    private string $manifestPath;

    public function __construct(KernelInterface $kernel)
    {
        $this->manifestPath = $kernel->getProjectDir() . '/public/build/.vite/manifest.json';
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('vite_asset', [$this, 'getAssetPath']),
        ];
    }

    public function getAssetPath(string $entry): string
    {
        $manifest = json_decode(file_get_contents($this->manifestPath), true);

        if (!isset($manifest[$entry]['file'])) {
            throw new \InvalidArgumentException("Entry '$entry' not found in manifest.json");
        }

        return '/build/' . $manifest[$entry]['file'];
    }
}
