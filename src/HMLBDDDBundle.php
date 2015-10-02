<?php

namespace HMLB\DDDBundle;

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
}
