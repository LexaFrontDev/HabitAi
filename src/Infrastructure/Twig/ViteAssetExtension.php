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

    /**
     * @param string $entry имя entry (например, "main.tsx")
     * @param string $type "js" или "css"
     *
     * @return string|string[]
     *
     * @throws \JsonException
     */
    public function getAssetPath(string $entry, string $type = 'js'): string|array
    {
        $json = file_get_contents($this->manifestPath);
        if (false === $json) {
            throw new MessageException("Cannot read Vite manifest at {$this->manifestPath}");
        }

        if (false !== strpos($json, "\0")) {
            throw new \RuntimeException('manifest.json содержит нулевые байты!');
        }


        $manifest = json_decode($json, true, 512, JSON_THROW_ON_ERROR);



        if (!isset($manifest[$entry])) {
            throw new MessageException("Entry '$entry' not found in manifest.json");
        }

        $data = $manifest[$entry];

        if ('js' === $type) {
            if (!isset($data['file'])) {
                throw new MessageException("No JS file found for entry '$entry'");
            }

            return '/build/'.$data['file'];
        }

        if ('css' === $type) {
            if (!isset($data['css'])) {
                throw new MessageException("No CSS files found for entry '$entry'");
            }

            return array_map(fn ($css) => '/build/'.$css, $data['css']);
        }

        throw new MessageException("Unsupported type '$type'. Use 'js' or 'css'.");
    }
}
