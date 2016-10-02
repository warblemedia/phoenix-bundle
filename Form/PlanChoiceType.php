<?php

namespace WarbleMedia\PhoenixBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use WarbleMedia\PhoenixBundle\Billing\PlanInterface;
use WarbleMedia\PhoenixBundle\Billing\PlanManagerInterface;

class PlanChoiceType extends AbstractType
{
    /** @var \WarbleMedia\PhoenixBundle\Billing\PlanManagerInterface */
    private $planManager;

    /**
     * PlanChoiceType constructor.
     *
     * @param \WarbleMedia\PhoenixBundle\Billing\PlanManagerInterface $planManager
     */
    public function __construct(PlanManagerInterface $planManager)
    {
        $this->planManager = $planManager;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'choices'      => $this->createChoices(),
            'choice_label' => function (PlanInterface $plan) {
                return $plan->getName();
            },
        ]);
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return ChoiceType::class;
    }

    /**
     * @return array
     */
    private function createChoices()
    {
        $choices = [];

        foreach ($this->planManager->getPaidActivePlans() as $plan) {
            $choices[$plan->getId()] = $plan;
        }

        return $choices;
    }
}
