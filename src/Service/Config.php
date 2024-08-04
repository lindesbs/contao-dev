<?php declare(strict_types=1);

namespace lindesbs\ContaoDev\Service;

use Symfony\Component\Filesystem\Path;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

class Config
{

    const VERSION = '1.0';

    private array $config;

    public function __construct()
    {
    }


    public function loadConfig($configFile): bool
    {
        if (file_exists($configFile)) {
            try {
                $this->config = Yaml::parse(file_get_contents($configFile));
            } catch (\Exception $e) {
                return false;
            }


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
}


class ComposerTemplate
{
    public string $name;
    public string $type = "contao-bundle";
    public string $description;
    public string $homepage;
    public string $license;

}