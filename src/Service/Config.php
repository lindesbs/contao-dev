<?php declare(strict_types=1);

namespace lindesbs\ContaoDev\Service;

use Symfony\Component\Filesystem\Path;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

class Config
{

    const VERSION = '1.0';

    public ComposerTemplate $config;

    public function __construct()
    {
        $this->config = new ComposerTemplate();
    }


    public function loadConfig($configFile): bool
    {
        if (file_exists($configFile)) {
            $config = Yaml::parse(file_get_contents($configFile));

            if (!key_exists('composer', $config))
                $config->composer = [];

            $this->config = new ComposerTemplate(...$config['composer']);

            return true;
        }

        file_put_contents($configFile, Yaml::dump(['version' => [self::VERSION]]));

        return false;
    }


    public function findSkeletonVersion($version): array
    {
        $arrPath = [];

        $searchString = sprintf("#%s#", $version);
        $finder = new Finder();
        $finder->files()->in(__DIR__ . "/../../skeleton/")->directories()->depth(1);

        if ($finder->hasResults()) {
            foreach ($finder as $file) {

                $fileNameWithExtension = $file->getRelativePathname();

                if (str_starts_with(strtolower($fileNameWithExtension), strtolower($version))) {
                    $arrPath[] = $fileNameWithExtension;
                }
            }
        }

        return $arrPath;
    }

    public function getConfig(): ComposerTemplate
    {
        return $this->config;
    }

    function combinePaths(...$segments) {
        // Helper function to recursively flatten the segments
        $flatten = function($items) use (&$flatten) {
            $flat = [];
            foreach ($items as $item) {
                if (is_array($item)) {
                    $flat = array_merge($flat, $flatten($item));
                } else {
                    $flat[] = $item;
                }
            }
            return $flat;
        };

        // Flatten the input segments
        $segments = $flatten($segments);

        // Normalize and filter empty segments
        $segments = array_filter($segments, function($segment) {
            return $segment !== '';
        });

        // Combine the segments into a path
        $path = implode(DIRECTORY_SEPARATOR, $segments);

        // Normalize the path to remove any duplicate separators
        $normalizedPath = preg_replace('#' . DIRECTORY_SEPARATOR . '+#', DIRECTORY_SEPARATOR, $path);

        return $normalizedPath;
    }
}


class ComposerTemplate
{
    public string $name;
    public string $type;
    public string $description;
    public string $homepage;
    public string $license;
    public array $authors;
    public array $scripts;
    public string $rootDevDir;

    /**
     * @param string $name
     * @param string $type
     * @param string $description
     * @param string $homepage
     * @param string $license
     */
    public function __construct(string $name='', string $type="contao-bundle", string $description='', string $homepage='', string $license='', array $authors=[], array $scripts=[], string $rootDevDir='development')
    {
        $this->name = $name;
        $this->type = $type;
        $this->description = $description;
        $this->homepage = $homepage;
        $this->license = $license;
        $this->authors = $authors;
        $this->scripts = $scripts;
        $this->rootDevDir = $rootDevDir;
    }


}