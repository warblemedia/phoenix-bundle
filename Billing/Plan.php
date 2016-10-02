<?php

namespace WarbleMedia\PhoenixBundle\Billing;

class Plan implements PlanInterface
{
    /** @var string */
    protected $id;

    /** @var string */
    protected $name;

    /** @var integer */
    protected $price;

    /** @var string */
    protected $interval;

    /** @var int */
    protected $trialDays;

    /** @var bool */
    protected $active;

    /** @var array */
    protected $features;

    /**
     * Plan constructor.
     *
     * @param string $id
     * @param string $name
     * @param array  $options
     */
    public function __construct(string $id, string $name, array $options)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $options['price'] ?? 0;
        $this->interval = $options['interval'] ?? 'monthly';
        $this->trialDays = $options['trial_days'] ?? 0;
        $this->active = $options['active'] ?? true;
        $this->features = $options['features'] ?? [];
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @return string
     */
    public function getInterval(): string
    {
        return $this->interval;
    }

    /**
     * @return int
     */
    public function getTrialDays(): int
    {
        return $this->trialDays;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @return array
     */
    public function getFeatures(): array
    {
        return $this->features;
    }
}
