<?php declare(strict_types=1);

namespace lindesbs\ContaoDev;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class ContaoDevBundle extends AbstractBundle
{
    public function loadExtension(
        array                 $config,
        ContainerConfigurator $containerConfigurator,
        ContainerBuilder      $containerBuilder,
    ): void
    {
        $containerConfigurator->import('../config/services.yaml');
    }
}
