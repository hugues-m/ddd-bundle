<?php

namespace HMLB\DDDBundle\Doctrine\ODM\MongoDB\Types;

use Doctrine\ODM\MongoDB\Types\Type;
use MongoId;
use HMLB\DDD\Entity\Identity;

/**
 * IdentityType.
 *
 * @author Hugues Maignol <hugues@hmlb.fr>
 */
class IdentityType extends Type
{
    const NAME = 'ddd_identity';

    public function convertToDatabaseValue($value)
    {
        if ($value === null) {
            return $value;
        }
        if (!$value instanceof Identity) {
            $value = new Identity((string) $value);
        }

        return new MongoId((string) $value);
    }

    public function convertToPHPValue($value)
    {
        return $value === null ?: new Identity((string) $value);
    }

    public function closureToMongo()
    {
        return '$return = new \HMLB\DDD\Entity\Identity($value);';
    }

    public function closureToPHP()
    {
        return '$return = (string) $value;';
    }
}
