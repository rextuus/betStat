<?php


namespace App\Service\FormTableStatistic;


use App\Entity\FormTableStatistic;

class FormTableStatisticFactory
{
    /**
     * @param FormTableStatisticData $data
     * @return FormTableStatistic
     */
    public function createByData(FormTableStatisticData $data)
    {
        $formTableStatistic = $this->createNewInstance();
        $this->mapData($data, $formTableStatistic);
        return $formTableStatistic;
    }

    /**
     * @param FormTableStatisticData $data
     * @param FormTableStatistic $formTableStatistic
     * @return FormTableStatistic
     */
    public function mapData(FormTableStatisticData $data, FormTableStatistic $formTableStatistic)
    {
        $formTableStatistic->setClub($data->getClub());
        $formTableStatistic->setSeason($data->getSeason());
        $formTableStatistic->setEndedWith($data->getEndWith());
        $formTableStatistic->setWinSeries($data->getWinSeries());
        $formTableStatistic->setStartMatchDay($data->getStartMatchDay());
        $formTableStatistic->setEndMatchDay($data->getEndMatchDay());

        return $formTableStatistic;
    }

    /**
     * @return FormTableStatistic
     */
    private function createNewInstance()
    {
        return new FormTableStatistic();
    }
}