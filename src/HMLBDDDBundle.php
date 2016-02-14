<?php

declare (strict_types = 1);

namespace HMLB\DDDBundle;

use Doctrine\Bundle\CouchDBBundle\DependencyInjection\Compiler\DoctrineCouchDBMappingsPass;
use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Doctrine\Bundle\MongoDBBundle\DependencyInjection\Compiler\DoctrineMongoDBMappingsPass;
use HMLB\DDDBundle\DependencyInjection\HMLBDDDExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use HMLB\DDDBundle\Doctrine\ORM\DBAL\Types\IdentityType as DBALIdType;
use HMLB\DDDBundle\Doctrine\ODM\MongoDB\Types\IdentityType as MongoIdType;

/**
 * HMLBDDDBundle.
 *
 * @author Hugues Maignol <hugues@hmlb.fr>
 */
class HMLBDDDBundle extends Bundle
{
    public function __construct()
    {
        $mongoType = '\Doctrine\ODM\MongoDB\Types\Type';
        if (class_exists($mongoType) && !call_user_func($mongoType.'::hasType', MongoIdType::NAME)) {
            call_user_func($mongoType.'::addType', MongoIdType::NAME, MongoIdType::class);
        }

        $DBALType = '\Doctrine\DBAL\Types\Type';
        if (class_exists($DBALType) && !call_user_func($DBALType.'::hasType', DBALIdType::NAME)) {
            call_user_func($DBALType.'::addType', DBALIdType::NAME, DBALIdType::class);
        }
    }

    public function build(ContainerBuilder $container)
    {
        $this->addRegisterMappingsPass($container);
    }

    /**
     * @param ContainerBuilder $container
     */
    private function addRegisterMappingsPass(ContainerBuilder $container)
    {
        $mappings = [
            realpath(__DIR__.'/Resources/config/doctrine') => 'HMLB\DDD',
        ];

        if (class_exists('Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass')) {
            $container->addCompilerPass(
                DoctrineOrmMappingsPass::createXmlMappingDriver(
                    $mappings,
                    ['fos_user.model_manager_name'],
                    'fos_user.backend_type_orm'
                )
            );
        }

        if (class_exists('Doctrine\Bundle\MongoDBBundle\DependencyInjection\Compiler\DoctrineMongoDBMappingsPass')) {
            $container->addCompilerPass(
                DoctrineMongoDBMappingsPass::createXmlMappingDriver(
                    $mappings,
                    ['fos_user.model_manager_name'],
                    'fos_user.backend_type_mongodb'
                )
            );
        }

        if (class_exists('Doctrine\Bundle\CouchDBBundle\DependencyInjection\Compiler\DoctrineCouchDBMappingsPass')) {
            $container->addCompilerPass(
                DoctrineCouchDBMappingsPass::createXmlMappingDriver(
                    $mappings,
                    ['fos_user.model_manager_name'],
                    'fos_user.backend_type_couchdb'
                )
            );
        }
    }

    public function getContainerExtension()
    {
        return new HMLBDDDExtension();
    }
}
