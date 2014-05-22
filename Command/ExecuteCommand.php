<?php
/**
 * This file is part of the kreait eZ Publish Migrations Bundle
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Kreait\EzPublish\MigrationsBundle\Command;

use Kreait\EzPublish\MigrationsBundle\Traits\CommandTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\DBAL\Migrations\Tools\Console\Command\ExecuteCommand as BaseExecuteCommand;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Command for executing single migrations up or down manually.
 */
class ExecuteCommand extends BaseExecuteCommand
{
    use CommandTrait;

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName( 'ezpublish:migrations:execute' );
    }

    /**
     * {@inheritDoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var \Symfony\Bundle\FrameworkBundle\Console\Application $app */
        $app = $this->getApplication();
        /** @var ContainerInterface $container */
        $container = $app->getKernel()->getContainer();

        $this->setMigrationConfiguration( $this->getBasicConfiguration( $container, $output ) );

        $configuration = $this->getMigrationConfiguration( $input, $output );
        $this->configureMigrations( $container, $configuration );

        parent::execute( $input, $output );
    }
}
