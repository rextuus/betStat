<?php


namespace App\Service\FormTableStatistic;


use App\Repository\FormTableStatisticRepository;
use phpDocumentor\Reflection\Types\Array_;

class FormTableStatisticService
{
    /**
     * @var FormTableStatisticRepository
     */
    private $formTableStatisticRepository;

    /**
     * @var FormTableStatisticFactory
     */
    private $formTableStatisticFactory;

    /**
     * FormTableStatisticService constructor.
     * @param FormTableStatisticRepository $formTableStatisticRepository
     * @param FormTableStatisticFactory $formTableStatisticFactory
     */
    public function __construct(FormTableStatisticRepository $formTableStatisticRepository, FormTableStatisticFactory $formTableStatisticFactory)
    {
        $this->formTableStatisticRepository = $formTableStatisticRepository;
        $this->formTableStatisticFactory = $formTableStatisticFactory;
    }

    /**
     * @param FormTableStatisticData $data
     * @return \App\Entity\FormTableStatistic
     * @throws \Doctrine\ORM\ORMException
     */
    public function createByData(FormTableStatisticData $data)
    {
        $formTableStatisticData = $this->formTableStatisticFactory->createByData($data);
        $this->formTableStatisticRepository->persist($formTableStatisticData);
        return $formTableStatisticData;
    }

    public function getAllFormTablesWithLength(string $leagueIdent, int $start, int $end)
    {
        $quote = array();
        foreach (range(1, 10) as $winSeries) {
            if ($winSeries === 1) {
                $result = $this->formTableStatisticRepository->getAllStatisticsForLength($winSeries, $leagueIdent, $start, $end);
                $quote[$winSeries]['loose'] = $result[0][1];
                $quote[$winSeries]['draw'] = $result[1][1];
                $quote[$winSeries]['win'] = 0;
            } else {
                $result = $this->formTableStatisticRepository->getAllStatisticsForLength($winSeries, $leagueIdent, $start, $end);
                $loose = count($result) > 0 ? $result[0][1] : 0;
                $draw = count($result) > 1 ? $result[1][1] : 0;
                $quote[$winSeries]['loose'] = $loose;
                $quote[$winSeries]['draw'] = $draw;
                $quote[$winSeries - 1]['win'] = $quote[$winSeries]['loose'] + $quote[$winSeries]['draw'];
                $total = $quote[$winSeries - 1]['loose'] + $quote[$winSeries - 1]['draw'] + $quote[$winSeries - 1]['win'];
                $quote[$winSeries - 1]['loose%'] = $total > 0 ? $quote[$winSeries - 1]['loose'] / $total * 100 : 0;
                $quote[$winSeries - 1]['draw%'] = $total > 0 ? $quote[$winSeries - 1]['draw'] / $total * 100 : 0;
                $quote[$winSeries - 1]['win%'] = $total > 0 ? $quote[$winSeries - 1]['win'] / $total * 100 : 0;
            }
        }

        return $quote;
    }
}