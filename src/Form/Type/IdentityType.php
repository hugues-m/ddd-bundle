<?php

declare (strict_types = 1);

namespace HMLB\DDDBundle\Form\Type;

use HMLB\DDDBundle\Form\DataTransformer\IdentityTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * IdentityType.
 *
 * @author Hugues Maignol <hugues@hmlb.fr>
 */
class IdentityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addModelTransformer(new IdentityTransformer());
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'HMLB\DDD\Entity\Identity',
            ]
        );
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'ddd_identity';
    }

    public function getParent()
    {
        return HiddenType::class;
    }
}
