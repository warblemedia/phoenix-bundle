<?php

namespace WarbleMedia\PhoenixBundle\Form;

use Symfony\Component\Form\FormFactoryInterface as BaseFormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class FormFactory implements FormFactoryInterface
{
    /** @var \Symfony\Component\Form\FormFactoryInterface */
    private $factory;

    /** @var string */
    private $name;

    /** @var string */
    private $type;

    /** @var array|null */
    private $validationGroups;

    /**
     * FormFactory constructor.
     *
     * @param \Symfony\Component\Form\FormFactoryInterface $factory
     * @param string                                       $name
     * @param string                                       $type
     * @param array|null                                   $validationGroups
     */
    public function __construct(BaseFormFactoryInterface $factory, string $name, string $type, array $validationGroups = null)
    {
        $this->factory = $factory;
        $this->name = $name;
        $this->type = $type;
        $this->validationGroups = $validationGroups;
    }

    /**
     * @param array $options
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createForm(array $options = []): FormInterface
    {
        $options = array_merge(['validation_groups' => $this->validationGroups], $options);

        return $this->factory->createNamed($this->name, $this->type, null, $options);
    }
}
