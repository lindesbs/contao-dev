<?php declare(strict_types=1);

namespace lindesbs\ContaoDev\Command;


use Contao\CoreBundle\Framework\ContaoFramework;
use Doctrine\DBAL\Connection;
use lindesbs\ContaoDev\Service\Config;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'contao:dev:make-extension',
    description: 'Dev Tools: Create extension',
)]
class CreateExtensionsCommand extends Command
{
    public function __construct(
        private readonly ContaoFramework $framework,
        private readonly Connection      $connection,
        private readonly Config $config
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('version', InputArgument::REQUIRED, 'Skeleton version')
            ->addArgument('namespace', InputArgument::REQUIRED, 'Desired namespace')
            ->addArgument('configFile', InputArgument::OPTIONAL, 'configfile', ".contao-dev.yaml")
            ;

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input,$output);

        $this->config->findSkeletonVersion($input->getArgument('version'));
        $this->config->loadConfig($input->getArgument('configFile'));

        $io->writeln("Use skeleton version <options=bold>{$input->getArgument('version')}</>");
        $io->writeln("namespace <options=bold>{$input->getArgument('namespace')}</>");



        return Command::SUCCESS;
    }
}