<?php

namespace WarbleMedia\PhoenixBundle\Form;

use Symfony\Component\Form\FormInterface;

interface FormFactoryInterface
{
    /**
     * @param array $options
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createForm(array $options = []): FormInterface;
}
