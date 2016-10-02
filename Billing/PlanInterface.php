<?php

namespace WarbleMedia\PhoenixBundle\Billing;

interface PlanInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return int
     */
    public function getPrice(): int;

    /**
     * @return string
     */
    public function getInterval(): string;

    /**
     * @return int
     */
    public function getTrialDays(): int;

    /**
     * @return bool
     */
    public function isActive(): bool;

    /**
     * @return array
     */
    public function getFeatures(): array;
}
