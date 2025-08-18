<?php

namespace App\Infrastructure\Twig;

use App\Domain\Exception\Message\MessageException;
use Symfony\Component\HttpKernel\KernelInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ViteAssetExtension extends AbstractExtension
{
    private string $manifestPath;

    public function __construct(KernelInterface $kernel)
    {
        $this->manifestPath = $kernel->getProjectDir().'/public/build/.vite/manifest.json';
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('vite_asset', [$this, 'getAssetPath']),
        ];
    }

    public function getAssetPath(string $entry): string
    {
        $json = file_get_contents($this->manifestPath);
        if (false === $json) {
            throw new MessageException("Cannot read Vite manifest at {$this->manifestPath}");
        }
        $manifest = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        if (!isset($manifest[$entry]['file'])) {
            throw new MessageException("Entry '$entry' not found in manifest.json");
        }

        return '/build/'.$manifest[$entry]['file'];
    }
}
