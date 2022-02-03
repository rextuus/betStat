<?php


namespace App\Service\Evaluation\Entity;


class Form
{
    /**
     * @var string[]
     */
    private $fixtureSeries;

    /**
     * Form constructor.
     */
    public function __construct()
    {
        $this->fixtureSeries = array();
    }

    public function addFixtureToSeries(string $fixtureResult)
    {
        $this->fixtureSeries[] = $fixtureResult;
    }

    public function getSeriesString(): string
    {
        $series = '';
        foreach ($this->fixtureSeries as $seriesEntry){
            $series = $series . $seriesEntry;
        }
        return $series;
    }

    public function checkIfSeriesFitsCondition(): bool
    {
        if (count($this->fixtureSeries) > 1){
            $currentFixture = $this->fixtureSeries[0];
            $lastFixture = $this->fixtureSeries[1];
            if (($lastFixture == "D" || $lastFixture == "L") && $currentFixture == "W"){
                return true;
            }
        }
        return false;
    }
}