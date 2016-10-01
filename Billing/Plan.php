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

    /**
     * Plan constructor.
     *
     * @param string $id
     * @param string $name
     * @param int    $price
     * @param string $interval
     * @param int    $trialDays
     * @param bool   $active
     */
    public function __construct(string $id, string $name, int $price, string $interval, int $trialDays, bool $active)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->interval = $interval;
        $this->trialDays = $trialDays;
        $this->active = $active;
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
}
