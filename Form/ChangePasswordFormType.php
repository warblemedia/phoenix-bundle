<?php

namespace WarbleMedia\PhoenixBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class ChangePasswordFormType extends AbstractType
{
    /** @var string */
    private $userClass;

    /**
     * ChangePasswordFormType constructor.
     *
     * @param string $userClass
     */
    public function __construct(string $userClass)
    {
        $this->userClass = $userClass;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array                                        $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $constraintOptions = null;
        if (!empty($options['validation_groups'])) {
            $group = reset($options['validation_groups']);
            $constraintOptions = ['groups' => [$group]];
        };

        $builder
            ->add('currentPassword', PasswordType::class, [
                'label'       => 'Current password',
                'mapped'      => false,
                'constraints' => [
                    new UserPassword($constraintOptions),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type'            => PasswordType::class,
                'first_options'   => ['label' => 'Password'],
                'second_options'  => ['label' => 'Confirm password'],
                'invalid_message' => 'The entered passwords don\'t match',
            ]);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => $this->userClass,
        ]);
    }
}
