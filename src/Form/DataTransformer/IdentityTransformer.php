<?php

namespace HMLB\DDDBundle\Form\DataTransformer;

use HMLB\DDD\Entity\Identity;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * IdentityTransformer.
 *
 * @author Hugues Maignol <hugues@hmlb.fr>
 */
class IdentityTransformer implements DataTransformerInterface
{
    /**
     * {@inheritdoc}
     */
    public function transform($value)
    {
        if (null === $value) {
            return;
        }

        return (string) $value;
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value)
    {
        if (null === $value) {
            return;
        }

        return new Identity($value);
    }
}
