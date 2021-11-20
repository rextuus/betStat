<?php


namespace App\Entity;


class StatisticDto
{
    /**
     * @var StatisticStep[]
     */
    private $steps;

    /**
     * @return StatisticStep[]
     */
    public function getSteps(): array
    {
        return $this->steps;
    }

    /**
     * @param StatisticStep[] $steps
     */
    public function setSteps(array $steps): void
    {
        $this->steps = $steps;
    }
}