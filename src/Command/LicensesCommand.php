<?php declare(strict_types=1);

namespace lindesbs\ContaoDev\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'contao:dev:license',
    description: 'Dev Tools: Create extension',
)]
class LicensesCommand
{
    private const LICENSES_URL = 'https://licenses.opendefinition.org/licenses/groups/all.json';
    private const CACHE_KEY = 'license_list';
    private const CACHE_TTL = 3600; // Time-to-live in seconds


    public function __construct(private readonly HttpClientInterface $httpClient,
                                private readonly CacheInterface $cache)
    {
    }

    public function getLicenseList(): array
    {
        return $this->cache->get(self::CACHE_KEY, function (ItemInterface $item) {
            $item->expiresAfter(self::CACHE_TTL);

            $response = $this->httpClient->request('GET', self::LICENSES_URL);
            if ($response->getStatusCode() !== 200) {
                throw new \RuntimeException('Failed to fetch licenses list');
            }

            return $response->toArray();
        });
    }



    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input,$output);

        dump($this->getLicenseList());

        return Command::SUCCESS;
    }
}