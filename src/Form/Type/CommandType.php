<?php

namespace HMLB\DDDBundle\Form\Type;

use HMLB\DDD\Message\Command\Command;
use ReflectionClass;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * CommandType.
 *
 * @author Hugues Maignol <hugues@hmlb.fr>
 */
abstract class CommandType extends AbstractType
{
    /**
     * The class name of the Command.
     *
     * @return string
     */
    abstract protected function getCommandClass();

    /**
     * Override this to pre-populate the default options of the OptionsResolver.
     *
     * @return array
     */
    protected function getDefaultOptions()
    {
        return [];
    }

    /**
     * @param FormInterface $form
     * @param Options       $options
     *
     * @return Command
     */
    protected function createCommandFromData(FormInterface $form, Options $options)
    {
        $reflection = new ReflectionClass($this->getCommandClass());
        $data = [];
        /** @var FormInterface $field */
        foreach ($form as $field) {
            $data[$field->getName()] = $field->getData();
        }

        return $reflection->newInstanceArgs($data);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $setup = [
            'data_class' => 'OH\Core\Command\AskQuestion',
            'empty_data' => function (Options $options) {
                return function (FormInterface $form) use ($options) {
                    return $this->createCommandFromData($form, $options);
                };
            },
        ];

        $merged = array_merge($setup, $this->getDefaultOptions());

        $resolver->setDefaults($merged);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return call_user_func($this->getCommandClass().'::messageName');
    }
}
