<?php
/**
 * This file is part of the kreait eZ Publish Migrations Bundle
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Kreait\EzPublish\MigrationsBundle\Tests\Migrations;

use Doctrine\DBAL\Migrations\Version;
use Kreait\EzPublish\MigrationsBundle\Tests\TestCase;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class EzPublishMigrationTest extends TestCase
{
    /**
     * @param string $direction "up" or "down"
     * @dataProvider directionProvider
     */
    public function testMigration($direction)
    {
        $versionString = $this->generateMigrationAndReturnVersionString();
        $namespace = $this->container->getParameter( 'ezpublish_migrations.namespace' );
        $config = $this->getSqliteConfiguration();

        $fullClassName = $namespace . '\\Version'.$versionString;
        $filePath = $this->container->getParameter( 'ezpublish_migrations.dir_name' ) . '/Version' . $versionString . '.php';

        $this->assertTrue( $this->fs->exists( $filePath ) );

        require $filePath;

        $version = new Version( $config, $versionString, $fullClassName );

        $migration = $version->getMigration();
        if ( $migration instanceof ContainerAwareInterface )
        {
            $migration->setContainer( $this->container );
        }

        $version->execute( $direction, true );

        $this->assertInstanceOf( $fullClassName, $migration );
    }

    public function directionProvider()
    {
        return array(
            array( 'up' ),
            array( 'down' ),
        );
    }
}