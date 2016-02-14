<?php

declare (strict_types = 1);

namespace HMLB\DDDBundle\Validator\Constraint;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use HMLB\DDD\Entity\Identity;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * ExistingAggregateRootValidator.
 *
 * @author Hugues Maignol <hugues@hmlb.fr>
 */
class ExistingAggregateRootValidator extends ConstraintValidator
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * ExistingAggregateRootValidator constructor.
     *
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($object, Constraint $constraint)
    {
        if (!$constraint instanceof ExistingAggregateRoot) {
            throw new UnexpectedTypeException($constraint, ExistingAggregateRoot::class);
        }

        if (null === $object) {
            $this->context->addViolation($constraint->nullMessage);

            return;
        }

        if (null === $constraint->class) {
            throw new ConstraintDefinitionException(
                'The ExistingAggregateRoot constraint must have a class parameter.'
            );
        }

        if (!$object instanceof Identity) {
            throw new UnexpectedTypeException($object, Identity::class);
        }

        /** @var ObjectRepository $repo */
        $repo = $this->om->getRepository($constraint->class);
        $aggregateRoot = $repo->find($object);

        if (!$aggregateRoot instanceof $constraint->class) {
            $this
                ->context
                ->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
