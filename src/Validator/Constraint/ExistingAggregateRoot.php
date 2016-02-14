<?php

declare (strict_types = 1);

namespace HMLB\DDDBundle\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * ExistingAggregateRoot.
 *
 * @author Hugues Maignol <hugues@hmlb.fr>
 */
class ExistingAggregateRoot extends Constraint
{
    /**
     * @var string
     */
    public $class;

    public $message = 'Object not found.';

    public $nullMessage = 'This cannot be empty.';

    public function validatedBy()
    {
        return 'hmlb_ddd.existing_aggregate_root';
    }

    /**
     * {@inheritdoc}
     */
    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOption()
    {
        return 'class';
    }
}
